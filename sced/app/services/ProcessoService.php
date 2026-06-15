<?php
// app/Services/ProcessoService.php

namespace App\Services;

use App\Models\Documento;
use App\Models\User;
use App\Models\ArquivoAnexo;
use App\Models\HistoricoMovimentacao;
use App\Exceptions\StatusTransitionException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProcessoService
{
    /**
     * Retorna as ações disponíveis para o usuário no processo
     */
    /**
     * Retorna as ações disponíveis para o usuário no processo
     */
    public function acoesDisponiveis(Documento $doc, User $user): array
    {
        $acoes = [];

        // Admin tem acesso total
        if ($user->isAdmin()) {
            return [
                'assumir',
                'devolver',
                'retornar',
                'finalizar',
                'desativar',
                'reabrir',
                'editar',
                'alteracao_manual',
                'substituir_anexo',
                'validar_anexo',
                'atribuir'
            ];
        }

        $temResponsavel = !is_null($doc->atribuido_a_id);
        $eOResponsavel = ($doc->atribuido_a_id === $user->id);
        $eOCriador = ($doc->usuario_registro_id === $user->id);

        // ⭐ ASSUMIR (apenas quando NÃO tem responsável)
        if (!$temResponsavel && in_array($doc->status, ['novo', 'pendente'])) {
            if ($user->podeAssumirProcesso()) {
                $depDestino = $doc->departamento_destino_id ?? optional($doc->tipoDocumento)->departamento_destino_id;
                if (!$depDestino || (int)$user->departamento_id === (int)$depDestino) {
                    $acoes[] = 'assumir';
                }
            }
        }

        // ⭐ ATRIBUIR (apenas quando NÃO tem responsável)
        if (!$temResponsavel && in_array($doc->status, ['novo', 'em_analise'])) {
            if ($doc->departamento_destino_id == $user->departamento_id) {
                if ($user->cargo == 'N3' || $user->cargo == 'N2') {
                    $acoes[] = 'atribuir';
                }
            }
        }

        // ⭐ DEVOLVER (apenas responsável, processo em análise)
        if ($eOResponsavel && $doc->status === 'em_analise') {
            $acoes[] = 'devolver';
        }

        // ⭐ FINALIZAR (apenas responsável, processo em análise)
        if ($eOResponsavel && $doc->status === 'em_analise') {
            $acoes[] = 'finalizar';
        }

        // ⭐ REENVIAR (apenas criador, processo pendente)
        if ($eOCriador && $doc->status === 'pendente') {
            $acoes[] = 'retornar';
            $acoes[] = 'substituir_anexo';
            $acoes[] = 'editar';
        }

        // ⭐ VALIDAR ANEXO (responsável ou N3)
        if ($doc->status === 'em_analise' && ($eOResponsavel || $user->isN3())) {
            $acoes[] = 'validar_anexo';
        }

        // ⭐ Ações administrativas (N3)
        if ($user->isN3()) {
            $acoes[] = 'desativar';
            $acoes[] = 'reabrir';
        }

        return array_unique($acoes);
    }

    /**
     * Assumir processo (apenas se não tiver responsável)
     */
    public function assumir(Documento $doc, User $user, ?string $observacoes): void
    {
        // ⭐ Só pode assumir se NÃO tiver responsável
        if ($doc->atribuido_a_id) {
            throw new StatusTransitionException('Este processo já possui um responsável.');
        }

        if ($doc->status !== 'novo' && $doc->status !== 'pendente') {
            throw new StatusTransitionException('Este processo não pode ser assumido no status atual.');
        }

        DB::transaction(function () use ($doc, $user, $observacoes) {
            $statusAnterior = $doc->status;

            $doc->update([
                'atribuido_a_id' => $user->id,
                'atribuido_em' => now(),
                'status' => 'em_analise',
            ]);

            HistoricoMovimentacao::create([
                'documento_id' => $doc->id,
                'usuario_id' => $user->id,
                'tipo' => 'assumir',
                'status_anterior' => $statusAnterior,
                'status_novo' => 'em_analise',
                'observacoes' => $observacoes ?? 'Processo assumido pelo responsável.',
            ]);
        });
    }

    /**
     * Devolver o processo ao solicitante
     * ⭐ CORREÇÃO: NÃO remover o responsável
     */
    public function devolver(Documento $doc, User $user, string $motivo): void
    {
        if ($doc->status !== 'em_analise') {
            throw new StatusTransitionException('Este processo não pode ser devolvido no status atual.');
        }

        if ($doc->atribuido_a_id !== $user->id && !$user->isAdmin()) {
            throw new StatusTransitionException('Apenas o responsável atual pode devolver o processo.');
        }

        DB::transaction(function () use ($doc, $user, $motivo) {
            $statusAnterior = $doc->status;

            $doc->update([
                'status' => 'pendente',
                // ⭐ NÃO REMOVER O RESPONSÁVEL
                // 'atribuido_a_id' => null,  ← REMOVER ESTA LINHA
                // 'atribuido_em' => null,    ← REMOVER ESTA LINHA
            ]);

            HistoricoMovimentacao::create([
                'documento_id' => $doc->id,
                'usuario_id' => $user->id,
                'tipo' => 'devolver',
                'status_anterior' => $statusAnterior,
                'status_novo' => 'pendente',
                'observacoes' => $motivo,
            ]);
        });
    }

    /**
     * Reenviar o processo (solicitante)
     * ⭐ CORREÇÃO: Mantém o mesmo responsável
     */
    public function retornar(Documento $doc, User $user, ?string $observacoes, array $novosArquivos, array $tiposAnexo): void
    {
        if ($doc->status !== 'pendente') {
            throw new StatusTransitionException('Este processo não pode ser reenviado no status atual.');
        }

        if ($doc->usuario_registro_id !== $user->id && !$user->isAdmin()) {
            throw new StatusTransitionException('Apenas o solicitante original pode reenviar o processo.');
        }

        DB::transaction(function () use ($doc, $user, $observacoes, $novosArquivos, $tiposAnexo) {
            $statusAnterior = $doc->status;

            $doc->update([
                'status' => 'em_analise',
                // ⭐ O RESPONSÁVEL CONTINUA O MESMO (não precisa alterar)
            ]);

            // Processar novos anexos se houver
            foreach ($novosArquivos as $index => $file) {
                $caminho = $file->store('anexos', 'public');
                $tipoAnexo = $tiposAnexo[$index] ?? 'outros';

                ArquivoAnexo::create([
                    'documento_id' => $doc->id,
                    'usuario_id' => $user->id,
                    'tipo_anexo' => $tipoAnexo,
                    'status_validacao' => 'pendente',
                    'nome_arquivo' => $file->getClientOriginalName(),
                    'caminho_arquivo' => $caminho,
                    'tipo_mime' => $file->getMimeType(),
                    'tamanho_bytes' => $file->getSize(),
                ]);
            }

            HistoricoMovimentacao::create([
                'documento_id' => $doc->id,
                'usuario_id' => $user->id,
                'tipo' => 'retornar',
                'status_anterior' => $statusAnterior,
                'status_novo' => 'em_analise',
                'observacoes' => $observacoes ?? 'Processo reenviado com ajustes.',
            ]);
        });
    }

    public function finalizar(Documento $doc, User $user, ?string $observacoes): void
    {
        if ($doc->status !== 'em_analise') {
            throw new StatusTransitionException('Este processo não pode ser finalizado no status atual.');
        }

        if ($doc->atribuido_a_id !== $user->id && !$user->isAdmin() && !$user->isN3()) {
            throw new StatusTransitionException('Apenas o responsável ou supervisor pode finalizar o processo.');
        }

        $documentosPendentes = $doc->anexos()->where('status_validacao', 'pendente')->exists();
        if ($documentosPendentes) {
            throw new StatusTransitionException('Existem documentos pendentes de validação. Finalize-os antes de concluir o processo.');
        }

        DB::transaction(function () use ($doc, $user, $observacoes) {
            $statusAnterior = $doc->status;

            $doc->update([
                'status' => 'finalizado',
            ]);

            HistoricoMovimentacao::create([
                'documento_id' => $doc->id,
                'usuario_id' => $user->id,
                'tipo' => 'finalizar',
                'status_anterior' => $statusAnterior,
                'status_novo' => 'finalizado',
                'observacoes' => $observacoes ?? 'Processo finalizado com sucesso.',
            ]);
        });
    }

    public function desativar(Documento $doc, User $user, string $motivo): void
    {
        if (!in_array($doc->status, ['novo', 'em_analise', 'pendente'])) {
            throw new StatusTransitionException('Apenas processos ativos podem ser desativados.');
        }

        DB::transaction(function () use ($doc, $user, $motivo) {
            $statusAnterior = $doc->status;

            $doc->update([
                'status' => 'desativado',
            ]);

            HistoricoMovimentacao::create([
                'documento_id' => $doc->id,
                'usuario_id' => $user->id,
                'tipo' => 'desativar',
                'status_anterior' => $statusAnterior,
                'status_novo' => 'desativado',
                'observacoes' => $motivo,
            ]);
        });
    }

    public function reabrir(Documento $doc, User $user, ?string $observacoes): void
    {
        if ($doc->status !== 'desativado') {
            throw new StatusTransitionException('Apenas processos desativados podem ser reabertos.');
        }

        DB::transaction(function () use ($doc, $user, $observacoes) {
            $statusAnterior = $doc->status;

            $doc->update([
                'status' => 'novo',
                'atribuido_a_id' => null,
                'atribuido_em' => null,
            ]);

            HistoricoMovimentacao::create([
                'documento_id' => $doc->id,
                'usuario_id' => $user->id,
                'tipo' => 'reabrir',
                'status_anterior' => $statusAnterior,
                'status_novo' => 'novo',
                'observacoes' => $observacoes ?? 'Processo reaberto.',
            ]);
        });
    }

    public function editarDados(Documento $doc, User $user, array $dados): void
    {
        if ($doc->status !== 'pendente') {
            throw new StatusTransitionException('Apenas processos pendentes podem ser editados.');
        }

        if ($doc->usuario_registro_id !== $user->id && !$user->isAdmin()) {
            throw new StatusTransitionException('Apenas o solicitante pode editar os dados do processo.');
        }

        $doc->update($dados);
    }

    public function alterarStatusManual(Documento $doc, User $user, string $novoStatus, ?string $observacoes): void
    {
        if (!$user->isAdmin()) {
            throw new StatusTransitionException('Apenas administradores podem alterar o status manualmente.');
        }

        DB::transaction(function () use ($doc, $user, $novoStatus, $observacoes) {
            $statusAnterior = $doc->status;

            $doc->update([
                'status' => $novoStatus,
            ]);

            HistoricoMovimentacao::create([
                'documento_id' => $doc->id,
                'usuario_id' => $user->id,
                'tipo' => 'alteracao_manual',
                'status_anterior' => $statusAnterior,
                'status_novo' => $novoStatus,
                'observacoes' => $observacoes ?? 'Status alterado manualmente pelo administrador.',
            ]);
        });
    }

    public function validarAnexo(Documento $doc, User $user, ArquivoAnexo $anexo, string $status, ?string $observacao): void
    {
        if (!in_array($doc->status, ['em_analise'])) {
            throw new StatusTransitionException('Apenas processos em análise podem ter anexos validados.');
        }

        // Verificar se o usuário tem permissão
        $podeValidar = false;

        // Responsável atual pode validar
        if ($doc->atribuido_a_id === $user->id) {
            $podeValidar = true;
        }

        // N3 pode validar
        if ($user->isN3()) {
            $podeValidar = true;
        }

        // Admin pode validar
        if ($user->isAdmin()) {
            $podeValidar = true;
        }

        if (!$podeValidar) {
            throw new StatusTransitionException('Apenas o responsável ou supervisor pode validar anexos.');
        }

        if ($anexo->status_validacao !== 'pendente') {
            throw new StatusTransitionException('Este anexo já foi validado anteriormente.');
        }

        DB::transaction(function () use ($anexo, $user, $status, $observacao, $doc) {
            $anexo->update([
                'status_validacao' => $status,
                'observacao_validacao' => $observacao,
                'validado_por' => $user->id,
                'validado_em' => now(),
            ]);

            // Registrar no histórico de movimentações
            HistoricoMovimentacao::create([
                'documento_id' => $doc->id,
                'usuario_id' => $user->id,
                'tipo' => 'validar_anexo',
                'status_anterior' => 'pendente',
                'status_novo' => $status,
                'observacoes' => $observacao ?? ($status === 'aprovado' ? 'Documento aprovado' : 'Documento recusado'),
            ]);
        });
    }

    public function substituirAnexo(Documento $doc, User $user, ArquivoAnexo $anexo, $novoArquivo, string $tipoAnexo): void
    {
        if ($doc->status !== 'pendente') {
            throw new StatusTransitionException('Apenas processos pendentes podem ter anexos substituídos.');
        }

        if ($doc->usuario_registro_id !== $user->id && !$user->isAdmin()) {
            throw new StatusTransitionException('Apenas o solicitante pode substituir anexos.');
        }

        DB::transaction(function () use ($anexo, $novoArquivo, $tipoAnexo, $user) {
            Storage::disk('public')->delete($anexo->caminho_arquivo);

            $caminho = $novoArquivo->store('anexos', 'public');

            $anexo->update([
                'tipo_anexo' => $tipoAnexo,
                'nome_arquivo' => $novoArquivo->getClientOriginalName(),
                'caminho_arquivo' => $caminho,
                'tipo_mime' => $novoArquivo->getMimeType(),
                'tamanho_bytes' => $novoArquivo->getSize(),
                'status_validacao' => 'pendente',
                'observacao_validacao' => null,
                'validado_por_id' => null,
                'validado_em' => null,
            ]);
        });
    }
}
