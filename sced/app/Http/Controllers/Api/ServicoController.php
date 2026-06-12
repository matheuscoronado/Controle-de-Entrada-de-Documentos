<?php
// app/Http/Controllers/Api/ServicoController.php
// Endpoints JSON para o autocomplete e requisitos do formulário.
// Rotas (em routes/api.php, dentro de middleware 'auth'):
//   GET /api/servicos/buscar?q=termo
//   GET /api/servicos/{id}/requisitos

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TipoDocumento;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ServicoController extends Controller
{
    /**
     * Autocomplete: retorna serviços ativos que contenham o termo.
     * Retorna no máximo 10 resultados.
     */
    public function buscar(Request $request): JsonResponse
    {
        $q = trim($request->input('q', ''));

        $servicos = TipoDocumento::where('status', 'ativo')
            ->when(strlen($q) >= 1, fn($query) =>
                $query->where('nome', 'like', "%{$q}%")
            )
            ->with('departamentoDestino:id,nome')
            ->select(['id', 'nome', 'descricao', 'obrigatoriedade',
                      'departamento_destino_id', 'cargo_responsavel'])
            ->orderBy('nome')
            ->limit(10)
            ->get();

        return response()->json(
            $servicos->map(fn($s) => [
                'id'               => $s->id,
                'nome'             => $s->nome,
                'descricao'        => $s->descricao,
                'obrigatorio'      => $s->obrigatoriedade === 'obrigatorio',
                'setor_nome'       => $s->departamentoDestino?->nome ?? '',
                'setor_id'         => $s->departamento_destino_id,
                'cargo_responsavel'=> $s->cargo_responsavel,
            ])
        );
    }

    /**
     * Requisitos: retorna o serviço selecionado + documentos obrigatórios
     * do mesmo setor que o usuário precisa anexar.
     */
    public function requisitos(int $id): JsonResponse
    {
        $servico = TipoDocumento::with('departamentoDestino:id,nome')->findOrFail($id);

        // Outros tipos obrigatórios do mesmo departamento de destino
        $obrigatorios = TipoDocumento::where('status', 'ativo')
            ->where('obrigatoriedade', 'obrigatorio')
            ->where('id', '!=', $id)
            ->when($servico->departamento_destino_id, fn($q) =>
                $q->where('departamento_destino_id', $servico->departamento_destino_id)
            )
            ->pluck('nome')
            ->values();

        return response()->json([
            'servico' => [
                'id'          => $servico->id,
                'nome'        => $servico->nome,
                'descricao'   => $servico->descricao,
                'setor'       => $servico->departamentoDestino?->nome,
                'setor_id'    => $servico->departamento_destino_id,
                'responsavel' => $servico->cargo_responsavel,
                'obrigatorio' => $servico->obrigatoriedade === 'obrigatorio',
            ],
            'documentos_obrigatorios' => $obrigatorios,
        ]);
    }
}