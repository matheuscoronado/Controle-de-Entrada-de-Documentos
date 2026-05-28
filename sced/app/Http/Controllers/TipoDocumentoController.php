<?php

namespace App\Http\Controllers;

use App\Models\TipoDocumento;
use App\Models\Departamento;
use App\Models\LogAuditoria;
use Illuminate\Http\Request;

class TipoDocumentoController extends Controller
{
    public function index()
    {
        $tipos = TipoDocumento::with('departamentoDestino')
            ->withCount('documentos')
            ->orderBy('nome')
            ->get();

        return view('admin.tipos.index', compact('tipos'));
    }

    public function create()
    {
        $departamentos = Departamento::orderBy('nome')->get();
        return view('admin.tipos.create', compact('departamentos'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nome'                    => 'required|string|max:100|unique:tipo_documentos,nome',
            'descricao'               => 'nullable|string|max:500',
            'obrigatoriedade'         => 'required|in:obrigatorio,opcional',
            'departamento_destino_id' => 'nullable|exists:departamentos,id',
            'cargo_responsavel'       => 'nullable|in:N1,N2,N3',
            'sla_horas'               => 'nullable|integer|min:1|max:8760',
        ]);

        $tipo = TipoDocumento::create($data);

        LogAuditoria::registrar('CADASTRO_TIPO_DOCUMENTO', 'tipo_documentos', $tipo->id, [
            'modulo'            => 'tipos',
            'descricao_legivel' => "Tipo de documento '{$tipo->nome}' cadastrado.",
        ]);

        return redirect()->route('tipos.index')
            ->with('success', "Tipo \"{$tipo->nome}\" cadastrado com sucesso!");
    }

    public function edit(TipoDocumento $tipo)
    {
        $departamentos = Departamento::orderBy('nome')->get();
        return view('admin.tipos.edit', compact('tipo', 'departamentos'));
    }

    public function update(Request $request, TipoDocumento $tipo)
    {
        $data = $request->validate([
            'nome'                    => 'required|string|max:100|unique:tipo_documentos,nome,' . $tipo->id,
            'descricao'               => 'nullable|string|max:500',
            'obrigatoriedade'         => 'required|in:obrigatorio,opcional',
            'departamento_destino_id' => 'nullable|exists:departamentos,id',
            'cargo_responsavel'       => 'nullable|in:N1,N2,N3',
            'sla_horas'               => 'nullable|integer|min:1|max:8760',
            'status'                  => 'required|in:ativo,inativo',
        ]);

        if ($data['status'] === 'inativo' && $tipo->documentos()->exists()) {
            return back()->with('error', 'Não é possível desativar um tipo com documentos vinculados.');
        }

        // Captura campos alterados para o log
        $alterados = [];
        foreach ($data as $campo => $novoValor) {
            $valorAtual = $tipo->$campo;
            if ((string) $valorAtual !== (string) $novoValor) {
                $alterados[$campo] = ['de' => $valorAtual, 'para' => $novoValor];
            }
        }

        $tipo->update($data);

        LogAuditoria::registrar('ATUALIZAR_TIPO_DOCUMENTO', 'tipo_documentos', $tipo->id, [
            'modulo'            => 'tipos',
            'campos_alterados'  => $alterados,
            'descricao_legivel' => "Tipo '{$tipo->nome}' atualizado. Campos: " . implode(', ', array_keys($alterados)),
        ]);

        return redirect()->route('tipos.index')
            ->with('success', "Tipo \"{$tipo->nome}\" atualizado!");
    }
}
