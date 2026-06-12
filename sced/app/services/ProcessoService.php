<?php
// app/Services/ProcessoService.php

namespace App\Services;

use App\Models\Documento;
use App\Models\HistoricoMovimentacao;
use App\Models\ArquivoAnexo;
use App\Models\LogAuditoria;
use App\Models\User;
use App\Exceptions\StatusTransitionException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProcessoService
{
    // 💡 Ajustado: Mapeamento Direcional [Status Atual => Destinos Permitidos]
    private const TRANSICOES_MANUAIS = [
        'novo'        => ['em_analise', 'pendente', 'desativado'],
        'em_analise'  => ['novo', 'pendente', 'finalizado', 'desativado'],
        'pendente'    => ['em_analise', 'novo', 'desativado'],
        'finalizado'  => ['em_analise', 'desativado'],
        'desativado'  => ['novo', 'em_analise'],
    ];

    public static array $statusLabels = [
        'novo'       => 'Novo',
        'em_analise' => 'Em Análise',
        'pendente'   => 'Pendente',
        'finalizado' => 'Finalizado',
        'desativado' => 'Desativado',
    ];

    // ════════════════════════════════════════════════════════
    // 1. ASSUMIR (novo | pendente → em_analise)
    // ════════════════════════════════════════════════════════

    public function assumir(Documento $doc, User $user, ?string $observacoes = null): Documento
    {
        if (!in_array($doc->status, ['novo', 'pendente'])) {
            throw StatusTransitionException::transicaoInvalida($doc->status, 'em_analise');
        }

        return DB::transaction(function () use ($doc, $user, $observacoes) {
            $anterior = $doc->status;

            $doc->update([
                'status'         => 'em_analise',
                'atribuido_a_id' => $user->id,
                'atribuido_em'   => now(),
            ]);

            $this->registrarHistorico($doc, $anterior, [
                'tipo'                => 'atribuicao',
                'usuario_id'          => $user->id,
                'usuario_destino_id'  => $user->id,
                'status_novo'         => 'em_analise',
                'observacoes'         => $observacoes ?? "Processo assumido por {$user->nome}.",
            ]);

            LogAuditoria::registrar('ASSUMIR_PROCESSO', 'documentos', $doc->id, [
                'modulo'           => 'processos',
                'status_anterior'  => $anterior,
                'status_novo'      => 'em_analise',
                'campos_alterados' => [
                    'status' => ['de' => $anterior, 'para' => 'em_analise'],
                    'atribuido_a_id' => ['de' => null, 'para' => $user->id]
                ],
                'descricao_legivel'=> "Processo {$doc->numero_protocolo} assumido por {$user->nome}.",
            ]);

            return $doc->fresh();
    });
    }

    // ════════════════════════════════════════════════════════
    // 2. DEVOLVER (em_analise → pendente)
    // ════════════════════════════════════════════════════════

    public function devolver(Documento $doc, User $user, string $motivo): Documento
    {
        if ($doc->status !== 'em_analise') {
            throw StatusTransitionException::transicaoInvalida($doc->status, 'pendente');
        }

        if (trim($motivo) === '') {
            throw new StatusTransitionException('O motivo da devolução é obrigatório.');
        }

        return DB::transaction(function () use ($doc, $user, $motivo) {
            $anterior = $doc->status;

            $doc->update([
                'status'           => 'pendente',
                'motivo_pendencia' => $motivo,
            ]);

            $this->registrarHistorico($doc, $anterior, [
                'tipo'               => 'devolucao',
                'usuario_id'         => $user->id,
                'usuario_destino_id' => $doc->usuario_registro_id,
                'status_novo'        => 'pendente',
                'observacoes'        => $motivo,
            ]);

            LogAuditoria::registrar('DEVOLVER_PROCESSO', 'documentos', $doc->id, [
                'modulo'           => 'processos',
                'status_anterior'  => $anterior,
                'status_novo'      => 'pendente',
                'campos_alterados' => [
                    'status'           => ['de' => $anterior, 'para' => 'pendente'],
                    'motivo_pendencia' => ['de' => null, 'para' => $motivo]
                ],
                'descricao_legivel'=> "Processo {$doc->numero_protocolo} devolvido por {$user->nome}: {$motivo}",
            ]);

            return $doc->fresh();
    });
    }

    // ════════════════════════════════════════════════════════
    // 3. RETORNAR (pendente → em_analise) — pelo solicitante
    // ════════════════════════════════════════════════════════

    public function retornar(
        Documento $doc,
        User      $user,
        ?string   $observacoes  = null,
        array     $novosAnexos  = [],
        array     $tiposAnexo   = []
    ): Documento {
        if ($doc->status !== 'pendente') {
            throw StatusTransitionException::transicaoInvalida($doc->status, 'em_analise');
        }

        return DB::transaction(function () use ($doc, $user, $observacoes, $novosAnexos, $tiposAnexo) {
            $anterior = $doc->status;
            $uploadados = $this->processarAnexos($doc, $user, $novosAnexos, $tiposAnexo);

            $doc->update([
                'status'          => 'em_analise',
                'motivo_pendencia'=> null,
                'atribuido_em'    => now(), 
            ]);

            $this->registrarHistorico($doc, $anterior, [
                'tipo'            => 'retorno',
                'usuario_id'      => $user->id,
                'usuario_destino_id' => $doc->atribuido_a_id, // Retorna direto para o analista responsável se houver
                'status_novo'     => 'em_analise',
                'observacoes'     => $observacoes ?? 'Ajustes realizados e processo reenviado.',
            ]);

            LogAuditoria::registrar('RETORNAR_PROCESSO', 'documentos', $doc->id, [
                'modulo'             => 'processos',
                'status_anterior'    => $anterior,
                'status_novo'        => 'em_analise',
                'uploads_realizados' => $uploadados,
                'descricao_legivel'  => "Processo {$doc->numero_protocolo} retornado pelo solicitante {$user->nome}.",
            ]);

            return $doc->fresh();
    });
    }

    // ════════════════════════════════════════════════════════
    // 4. FINALIZAR (em_analise → finalizado)
    // ════════════════════════════════════════════════════════

    public function finalizar(Documento $doc, User $user, ?string $observacoes = null): Documento
    {
        if ($doc->status !== 'em_analise') {
            throw StatusTransitionException::transicaoInvalida($doc->status, 'finalizado');
        }

        return DB::transaction(function () use ($doc, $user, $observacoes) {
            $anterior = $doc->status;
            $doc->update(['status' => 'finalizado']);

            $this->registrarHistorico($doc, $anterior, [
                'tipo'            => 'finalizacao',
                'usuario_id'      => $user->id,
                'status_novo'     => 'finalizado',
                'observacoes'     => $observacoes ?? 'Processo finalizado.',
            ]);

            LogAuditoria::registrar('FINALIZAR_PROCESSO', 'documentos', $doc->id, [
                'modulo'           => 'processos',
                'status_anterior'  => $anterior,
                'status_novo'      => 'finalizado',
                'descricao_legivel'=> "Processo {$doc->numero_protocolo} finalizado por {$user->nome}.",
            ]);

            return $doc->fresh();
    });
    }

    // ════════════════════════════════════════════════════════
    // 5. DESATIVAR (qualquer → desativado) — admin/N3
    // ════════════════════════════════════════════════════════

    public function desativar(Documento $doc, User $user, string $motivo): Documento
    {
        if ($doc->status === 'desativado') {
            throw new StatusTransitionException('O processo já está desativado.');
        }

        if (trim($motivo) === '') {
            throw new StatusTransitionException('O motivo da desativação é obrigatório.');
        }

        return DB::transaction(function () use ($doc, $user, $motivo) {
            $anterior = $doc->status;

            $doc->update([
                'status'             => 'desativado',
                'motivo_desativacao' => $motivo,
            ]);

            $this->registrarHistorico($doc, $anterior, [
                'tipo'            => 'desativacao',
                'usuario_id'      => $user->id,
                'status_novo'     => 'desativado',
                'observacoes'     => $motivo,
            ]);

            LogAuditoria::registrar('DESATIVAR_PROCESSO', 'documentos', $doc->id, [
                'modulo'           => 'processos',
                'status_anterior'  => $anterior,
                'status_novo'      => 'desativado',
                'campos_alterados' => [
                    'status'             => ['de' => $anterior, 'para' => 'desativado'],
                    'motivo_desativacao' => ['de' => null, 'para' => $motivo]
                ],
                'descricao_legivel'=> "Processo {$doc->numero_protocolo} desativado por {$user->nome}: {$motivo}",
            ]);

            return $doc->fresh();
    });
    }

    // ════════════════════════════════════════════════════════
    // 6. REABRIR (finalizado|desativado → novo|em_analise) — admin/N3
    // ════════════════════════════════════════════════════════

    public function reabrir(Documento $doc, User $user, ?string $observacoes = null): Documento
    {
        if (!in_array($doc->status, ['finalizado', 'desativado'])) {
            throw StatusTransitionException::transicaoInvalida($doc->status, 'em_analise');
        }

        return DB::transaction(function () use ($doc, $user, $observacoes) {
            $anterior    = $doc->status;
            $novoStatus  = $doc->atribuido_a_id ? 'em_analise' : 'novo';

            $doc->update([
                'status'             => $novoStatus,
                'motivo_desativacao' => null,
                'motivo_pendencia'   => null,
                'reaberto_em'        => now(),
            ]);

            $this->registrarHistorico($doc, $anterior, [
                'tipo'               => 'reabertura',
                'usuario_id'         => $user->id,
                'usuario_destino_id' => $doc->atribuido_a_id,
                'status_novo'        => $novoStatus,
                'observacoes'        => $observacoes ?? "Processo reaberto por {$user->nome}.",
            ]);

            LogAuditoria::registrar('REABRIR_PROCESSO', 'documentos', $doc->id, [
                'modulo'           => 'processos',
                'status_anterior'  => $anterior,
                'status_novo'      => $novoStatus,
                'descricao_legivel'=> "Processo {$doc->numero_protocolo} reaberto por {$user->nome}.",
            ]);

            return $doc->fresh();
    });
    }

    // ════════════════════════════════════════════════════════
    // 7. ALTERAÇÃO MANUAL DE STATUS — somente admin/N3
    // ════════════════════════════════════════════════════════

    public function alterarStatusManual(
        Documento $doc,
        User      $user,
        string    $novoStatus,
        ?string   $observacoes = null
    ): Documento {
        $permitidos = self::TRANSICOES_MANUAIS[$doc->status] ?? [];

        if (!in_array($novoStatus, $permitidos)) {
            throw StatusTransitionException::transicaoInvalida($doc->status, $novoStatus);
        }

        return DB::transaction(function () use ($doc, $user, $novoStatus, $observacoes) {
            $anterior = $doc->status;
            $doc->update(['status' => $novoStatus]);

            $this->registrarHistorico($doc, $anterior, [
                'tipo'            => 'alteracao_manual',
                'usuario_id'      => $user->id,
                'status_novo'     => $novoStatus,
                'observacoes'     => $observacoes ?? "Status alterado manualmente de '{$anterior}' para '{$novoStatus}'.",
            ]);

            LogAuditoria::registrar('ALTERAR_STATUS_MANUAL', 'documentos', $doc->id, [
                'modulo'           => 'processos',
                'status_anterior'  => $anterior,
                'status_novo'      => $novoStatus,
                'campos_alterados' => ['status' => ['de' => $anterior, 'para' => $novoStatus]],
                'descricao_legivel'=> "Status manual: {$anterior} → {$novoStatus} por {$user->nome}.",
            ]);

            return $doc->fresh();
    });
    }

    // ════════════════════════════════════════════════════════
    // 8. EDITAR DADOS DO PROCESSO
    // ════════════════════════════════════════════════════════

    public function editarDados(Documento $doc, User $user, array $dados): Documento
    {
        return DB::transaction(function () use ($doc, $user, $dados) {
            $alterados = [];
            $camposEditaveis = ['remetente', 'descricao', 'setor_destino'];
            $anterior = $doc->status;

            foreach ($camposEditaveis as $campo) {
                if (isset($dados[$campo]) && (string)$doc->$campo !== (string)$dados[$campo]) {
                    $alterados[$campo] = ['de' => $doc->$campo, 'para' => $dados[$campo]];
                }
            }

            if (empty($alterados)) {
                return $doc;
            }

            $doc->update(array_intersect_key($dados, array_flip($camposEditaveis)));

            $this->registrarHistorico($doc, $anterior, [
                'tipo'        => 'edicao_dados',
                'usuario_id'  => $user->id,
                'observacoes' => 'Dados editados: ' . implode(', ', array_keys($alterados)),
            ]);

            LogAuditoria::registrar('EDITAR_DADOS_PROCESSO', 'documentos', $doc->id, [
                'modulo'           => 'processos',
                'campos_alterados' => $alterados,
                'descricao_legivel'=> "Dados do processo {$doc->numero_protocolo} editados por {$user->nome}.",
            ]);

            return $doc->fresh();
    });
    }

    // ════════════════════════════════════════════════════════
    // 9. SUBSTITUIR ANEXO
    // ════════════════════════════════════════════════════════

    public function substituirAnexo(
        Documento    $doc,
        User         $user,
        ArquivoAnexo $anexoAntigo,
        UploadedFile $novoArquivo,
        string       $tipoAnexo = 'outros'
    ): ArquivoAnexo {
        if ($anexoAntigo->documento_id !== $doc->id) {
            throw new \InvalidArgumentException('O anexo informado não pertence a este processo.');
        }

        return DB::transaction(function () use ($doc, $user, $anexoAntigo, $novoArquivo, $tipoAnexo) {
            $anteriorStatusDoc = $doc->status;

            if (Storage::disk('public')->exists($anexoAntigo->caminho_arquivo)) {
                Storage::disk('public')->delete($anexoAntigo->caminho_arquivo);
            }

            $nomeAntigo = $anexoAntigo->nome_arquivo;
            $caminho = $novoArquivo->store('anexos', 'public');
            
            $anexoAntigo->update([
                'nome_arquivo'    => $novoArquivo->getClientOriginalName(),
                'caminho_arquivo' => $caminho,
                'tipo_mime'       => $novoArquivo->getMimeType(),
                'tamanho_bytes'   => $novoArquivo->getSize(),
                'tipo_anexo'      => $tipoAnexo,
                'status_validacao'=> 'pendente',  
                'observacao_validacao' => null,
                'validado_por'    => null,
                'validado_em'     => null,
            ]);

            $this->registrarHistorico($doc, $anteriorStatusDoc, [
                'tipo'        => 'substituicao_anexo',
                'usuario_id'  => $user->id,
                'observacoes' => "Arquivo '{$nomeAntigo}' substituído por '{$novoArquivo->getClientOriginalName()}'.",
            ]);

            LogAuditoria::registrar('SUBSTITUIR_ANEXO', 'arquivo_anexos', $anexoAntigo->id, [
                'modulo'             => 'processos',
                'uploads_realizados' => [$novoArquivo->getClientOriginalName()],
                'campos_alterados'   => [
                    'nome_arquivo' => ['de' => $nomeAntigo, 'para' => $novoArquivo->getClientOriginalName()],
                    'status_validacao' => ['de' => $anexoAntigo->status_validacao, 'para' => 'pendente'],
                ],
                'descricao_legivel' => "Anexo do processo {$doc->numero_protocolo} substituído por {$user->nome}.",
            ]);

            return $anexoAntigo->fresh();
    });
    }

    // ════════════════════════════════════════════════════════
    // 10. VALIDAR ANEXO — admin/N3
    // ════════════════════════════════════════════════════════

    public function validarAnexo(
        Documento    $doc,
        User         $user,
        ArquivoAnexo $anexo,
        string       $status, 
        ?string      $observacao = null
    ): ArquivoAnexo {
        // 🛡️ CORREÇÃO DE SEGURANÇA: Garante o vínculo íntegro do anexo com o processo injetado
        if ($anexo->documento_id !== $doc->id) {
            throw new \InvalidArgumentException('O anexo informado não pertence a este processo.');
        }

        if (!in_array($status, ['aprovado', 'rejeitado'])) {
            throw new StatusTransitionException("Status de validação inválido: '{$status}'.");
        }

        $anterior = $anexo->status_validacao;

        $anexo->update([
            'status_validacao'     => $status,
            'observacao_validacao' => $observacao,
            'validado_por'         => $user->id,
            'validado_em'          => now(),
        ]);

        LogAuditoria::registrar('VALIDAR_ANEXO', 'arquivo_anexos', $anexo->id, [
            'modulo'           => 'processos',
            'status_anterior'  => $anterior,
            'status_novo'      => $status,
            'campos_alterados' => ['status_validacao' => ['de' => $anterior, 'para' => $status]],
            'descricao_legivel'=> "Anexo '{$anexo->nome_arquivo}' {$status} por {$user->nome}.",
        ]);

        return $anexo->fresh();
    }

    // ════════════════════════════════════════════════════════
    // HELPERS PRIVADOS
    // ════════════════════════════════════════════════════════

    private function registrarHistorico(Documento $doc, string $statusAnterior, array $dados): HistoricoMovimentacao
    {
        return HistoricoMovimentacao::create(array_merge([
            'documento_id'       => $doc->id,
            'status_anterior'    => $statusAnterior,
            'status_novo'        => $doc->status,
            'usuario_destino_id' => null,
        ], $dados));
    }

    private function processarAnexos(Documento $doc, User $user, array $arquivos, array $tipos): array
    {
        $nomes = [];
        foreach ($arquivos as $i => $file) {
            if (!$file instanceof UploadedFile) continue;
            $caminho = $file->store('anexos', 'public');
            ArquivoAnexo::create([
                'documento_id'    => $doc->id,
                'usuario_id'      => $user->id,
                'tipo_anexo'      => $tipos[$i] ?? 'outros',
                'status_validacao'=> 'pendente',
                'nome_arquivo'    => $file->getClientOriginalName(),
                'caminho_arquivo' => $caminho,
                'tipo_mime'       => $file->getMimeType(),
                'tamanho_bytes'   => $file->getSize(),
            ]);
            $nomes[] = $file->getClientOriginalName();
        }
        return $nomes;
    }

    public function acoesDisponiveis(Documento $doc, User $user): array
    {
        $acoes = [];

        // Admin tem acesso total (alteração manual de status inclusa)
        if ($user->isAdmin()) {
            return ['assumir', 'devolver', 'retornar', 'finalizar',
                    'desativar', 'reabrir', 'editar', 'alteracao_manual',
                    'substituir_anexo', 'validar_anexo'];
        }

        // ── ASSUMIR ────────────────────────────────────────────────────────────
        // Condições: status novo/pendente, sem responsável, e usuário pertence
        // ao departamento de destino do processo (ou do serviço associado).
        // ALÉM DISSO: usuário deve ter permissão habilitada para assumir.
        if (in_array($doc->status, ['novo', 'pendente']) && !$doc->atribuido_a_id) {
            // Verifica permissão de assumir (flag pode_assumir ou perfil N3/Admin)
            if ($user->podeAssumirProcesso()) {
                // Determina o departamento de destino (do processo ou do serviço)
                $depDestino = $doc->departamento_destino_id
                            ?? optional($doc->tipoDocumento)->departamento_destino_id;

                if (!$depDestino || (int)$user->departamento_id === (int)$depDestino) {
                    // Sem destino definido OU usuário pertence ao setor correto
                    $acoes[] = 'assumir';
                }
            }
        }

        // ── DEVOLVER + FINALIZAR ───────────────────────────────────────────────
        // Apenas o analista que assumiu o processo
        if ($doc->status === 'em_analise' && $doc->atribuido_a_id === $user->id) {
            $acoes[] = 'devolver';
            $acoes[] = 'finalizar';
        }

        // ── REENVIAR (retornar) ────────────────────────────────────────────────
        // Apenas o solicitante quando o processo foi devolvido
        if ($doc->status === 'pendente' && $doc->usuario_registro_id === $user->id) {
            $acoes[] = 'retornar';
            $acoes[] = 'substituir_anexo';
            $acoes[] = 'editar';
        }

        // ── N3 (Supervisor) ───────────────────────────────────────────────────
        // Pode desativar, reabrir e validar anexos — mas NÃO alterar status manual
        // (status manual é exclusivo do ADMIN)
        if ($user->isN3()) {
            $acoes[] = 'desativar';
            $acoes[] = 'reabrir';
            $acoes[] = 'validar_anexo';
        }

        return array_unique($acoes);
    }
}