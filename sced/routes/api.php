<?php
// routes/api.php

use App\Http\Controllers\Api\ServicoController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('dashboard/metricas', [DashboardController::class, 'metricas'])->name('dashboard.metricas');
    Route::get('dashboard/analistas', [DashboardController::class, 'analistas'])->name('dashboard.analistas');
    Route::post('processos/{documento}/atribuir', [DashboardController::class, 'atribuir'])->name('dashboard.atribuir');

    // Autocomplete de Serviços
    Route::get('servicos/buscar', [ServicoController::class, 'buscar']);
    Route::get('servicos/{id}/requisitos', [ServicoController::class, 'requisitos']);

    // ⭐ BUSCAR TODOS OS DOCUMENTOS CADASTRADOS (para o select no create)
    Route::get('documentos/todos', function () {
        try {
            $documentos = \App\Models\DocumentoTipo::where('status', 'ativo')
                ->orderBy('nome')
                ->get(['id', 'nome', 'tipo', 'descricao']);
            
            \Illuminate\Support\Facades\Log::info('Documentos carregados via API', ['quantidade' => $documentos->count()]);
            
            return response()->json($documentos);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Erro ao carregar documentos via API: ' . $e->getMessage());
            return response()->json(['error' => 'Erro ao carregar documentos'], 500);
        }
    })->name('api.documentos.todos');

    // ⭐ BUSCAR USUÁRIOS DISPONÍVEIS PARA ATRIBUIÇÃO
    Route::get('usuarios/para-atribuir/{processo}', function($processoId) {
        try {
            $processo = \App\Models\Documento::findOrFail($processoId);
            $user = auth()->user();
            
            \Illuminate\Support\Facades\Log::info('Buscando usuários para atribuição', [
                'processo_id' => $processoId,
                'usuario_logado' => $user->id,
                'cargo_logado' => $user->cargo,
                'setor_processo' => $processo->departamento_destino_id
            ]);
            
            // Verifica se o usuário pode atribuir
            if (!in_array($user->cargo, ['N3', 'N2', 'administrador', 'admin'])) {
                \Illuminate\Support\Facades\Log::warning('Usuário não tem permissão para atribuir', ['cargo' => $user->cargo]);
                return response()->json(['error' => 'Sem permissão'], 403);
            }
            
            $query = \App\Models\User::where('status', 'ativo')
                ->where('id', '!=', $user->id);
            
            // Filtra por setor do processo
            if ($processo->departamento_destino_id) {
                $query->where('departamento_id', $processo->departamento_destino_id);
                \Illuminate\Support\Facades\Log::info('Filtrando por setor', ['setor_id' => $processo->departamento_destino_id]);
            }
            
            // N3 pode atribuir para N2 ou N1
            if ($user->cargo == 'N3') {
                $query->whereIn('cargo', ['N2', 'N1']);
                \Illuminate\Support\Facades\Log::info('N3 buscando usuários N2 ou N1');
            } elseif ($user->cargo == 'N2') {
                $query->where('cargo', 'N1');
                \Illuminate\Support\Facades\Log::info('N2 buscando usuários N1');
            } elseif ($user->cargo == 'administrador' || $user->cargo == 'admin') {
                // Admin pode atribuir para qualquer cargo
                \Illuminate\Support\Facades\Log::info('Admin buscando todos os usuários');
            } else {
                return response()->json(['error' => 'Cargo não permitido'], 403);
            }
            
            $usuarios = $query->get(['id', 'nome', 'cargo', 'departamento_id', 'email']);
            
            // Adiciona nome do departamento
            foreach ($usuarios as $usuario) {
                $departamento = \App\Models\Departamento::find($usuario->departamento_id);
                $usuario->departamento_nome = $departamento ? $departamento->nome : 'Setor não definido';
            }
            
            \Illuminate\Support\Facades\Log::info('Usuários encontrados', ['quantidade' => $usuarios->count()]);
            
            return response()->json($usuarios);
            
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Erro ao buscar usuários para atribuição: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    })->middleware('auth')->name('api.usuarios.para-atribuir');
});