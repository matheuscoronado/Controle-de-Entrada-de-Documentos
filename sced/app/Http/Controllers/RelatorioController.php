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
        $tipos = TipoDocumento::all();
        return view('relatorios.index', compact('tipos'));
    }

    public function gerar(Request $request)
    {
        $query = Documento::with(['tipoDocumento', 'usuarioRegistro']);

        if ($request->status) {
            $query->where('status', $request->status);
        }
        if ($request->tipo_documento_id) {
            $query->where('tipo_documento_id', $request->tipo_documento_id);
        }
        if ($request->data_inicio && $request->data_fim) {
            $query->whereBetween('data_recebimento', [$request->data_inicio, $request->data_fim]);
        }

        $documentos = $query->orderBy('data_recebimento', 'desc')->get();

        $pdf = Pdf::loadView('relatorios.pdf', compact('documentos', 'request'));

        return $pdf->download('relatorio-sced-' . date('Y-m-d') . '.pdf');
    }
}