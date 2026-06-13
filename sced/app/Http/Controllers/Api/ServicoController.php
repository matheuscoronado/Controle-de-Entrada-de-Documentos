<?php
// app/Http/Controllers/Api/ServicoController.php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TipoDocumento;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class ServicoController extends Controller
{
    /**
     * Autocomplete: retorna serviços ativos que contenham o termo.
     */
    public function buscar(Request $request): JsonResponse
    {
        $q = trim($request->input('q', ''));
        
        // Se não tiver termo de busca, retorna vazio
        if (strlen($q) < 1) {
            return response()->json([]);
        }

        try {
            $servicos = TipoDocumento::where('status', 'ativo')
                ->where('nome', 'like', "%{$q}%")
                ->with(['departamentoDestino:id,nome', 'documentosTipo'])
                ->select(['id', 'nome', 'descricao', 'departamento_destino_id', 'cargos_responsaveis'])
                ->orderBy('nome')
                ->limit(10)
                ->get();

            Log::info('Busca de serviços', ['termo' => $q, 'encontrados' => $servicos->count()]);

            return response()->json(
                $servicos->map(fn($s) => [
                    'id'                    => $s->id,
                    'nome'                  => $s->nome,
                    'descricao'             => $s->descricao,
                    'setor_nome'            => $s->departamentoDestino?->nome ?? '',
                    'setor_id'              => $s->departamento_destino_id,
                    'cargos_responsaveis'   => $s->cargos_responsaveis ?? [],
                    'documentos_necessarios'=> $s->documentosTipo->map(fn($doc) => [
                        'id'   => $doc->id,
                        'nome' => $doc->nome,
                        'tipo' => $doc->tipo,
                    ]),
                ])
            );
        } catch (\Exception $e) {
            Log::error('Erro na busca de serviços', ['error' => $e->getMessage()]);
            return response()->json([]);
        }
    }

    /**
     * Requisitos: retorna o serviço selecionado + documentos vinculados.
     */
    public function requisitos(int $id): JsonResponse
    {
        try {
            $servico = TipoDocumento::with([
                'departamentoDestino:id,nome',
                'documentosTipo'
            ])->findOrFail($id);

            $documentosVinculados = $servico->documentosTipo->map(fn($doc) => [
                'id'          => $doc->id,
                'nome'        => $doc->nome,
                'descricao'   => $doc->descricao,
                'tipo'        => $doc->tipo,
                'obrigatorio' => $doc->tipo === 'obrigatorio',
            ])->values();

            return response()->json([
                'servico' => [
                    'id'                    => $servico->id,
                    'nome'                  => $servico->nome,
                    'descricao'             => $servico->descricao,
                    'setor'                 => $servico->departamentoDestino?->nome,
                    'setor_id'              => $servico->departamento_destino_id,
                    'cargos_responsaveis'   => $servico->cargos_responsaveis ?? [],
                ],
                'documentos_obrigatorios' => $documentosVinculados->filter(fn($doc) => $doc['obrigatorio']),
                'documentos_opcionais'    => $documentosVinculados->filter(fn($doc) => !$doc['obrigatorio']),
                'todos_documentos'        => $documentosVinculados,
            ]);
        } catch (\Exception $e) {
            Log::error('Erro ao buscar requisitos', ['id' => $id, 'error' => $e->getMessage()]);
            return response()->json(['error' => 'Serviço não encontrado'], 404);
        }
    }
}