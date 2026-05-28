<?php

namespace App\Http\Controllers;

use App\Models\LogAuditoria;
use App\Models\User;
use Illuminate\Http\Request;

class LogAuditoriaController extends Controller
{
    /**
     * Tela principal de logs — somente Administrador.
     * Filtros: usuário, módulo, ação, período.
     */
    public function index(Request $request)
    {
        $query = LogAuditoria::with('usuario')
            ->orderByDesc('data_hora');

        // ── Filtros ──────────────────────────────────────────

        if ($request->filled('usuario_id')) {
            $query->doUsuario((int) $request->usuario_id);
        }

        if ($request->filled('modulo')) {
            $query->doModulo($request->modulo);
        }

        if ($request->filled('acao')) {
            $query->where('acao', 'like', '%' . strtoupper($request->acao) . '%');
        }

        if ($request->filled('data_inicio') && $request->filled('data_fim')) {
            $query->noPeriodo(
                $request->data_inicio . ' 00:00:00',
                $request->data_fim   . ' 23:59:59'
            );
        }

        $logs     = $query->paginate(50)->withQueryString();
        $usuarios = User::orderBy('nome')->get(['id', 'nome']);

        $modulos = LogAuditoria::select('modulo')
            ->whereNotNull('modulo')
            ->distinct()
            ->orderBy('modulo')
            ->pluck('modulo');

        return view('admin.logs.index', compact('logs', 'usuarios', 'modulos'));
    }

    /**
     * Exibe os detalhes completos de um log específico.
     */
    public function show(LogAuditoria $log)
    {
        $log->load('usuario');
        return view('admin.logs.show', compact('log'));
    }
}
