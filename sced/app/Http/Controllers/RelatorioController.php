<?php

namespace App\Http\Controllers;

use App\Models\Documento;
use App\Models\TipoDocumento;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class RelatorioController extends Controller
{
    public function index()
    {
        $tipos = TipoDocumento::where('status', 'ativo')->get();
        return view('relatorios.index', compact('tipos'));
    }

    public function gerar(Request $request)
    {
        $query = Documento::with(['tipoDocumento', 'usuarioRegistro'])
            ->withCount('anexos');

        if ($request->status) {
            $query->where('status', $request->status);
        }
        if ($request->tipo_documento_id) {
            $query->where('tipo_documento_id', $request->tipo_documento_id);
        }
        if ($request->data_inicio && $request->data_fim) {
            $query->whereBetween('created_at', [
                $request->data_inicio . ' 00:00:00',
                $request->data_fim . ' 23:59:59'
            ]);
        }

        $documentos = $query->orderBy('created_at', 'desc')->get();

        $pdf = Pdf::loadView('relatorios.pdf', compact('documentos', 'request'));

        return $pdf->download('relatorio-sced-' . date('Y-m-d-His') . '.pdf');
    }
}