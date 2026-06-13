{{-- ============================================================
     resources/views/usuarios/index.blade.php
     LISTAGEM DE USUÁRIOS - COM ESTILO MODERNO
     ============================================================ --}}
@extends('layouts.app')
@section('title', 'Usuários')
@section('subtitle', 'Gerencie os usuários do sistema')

@section('topbar-actions')
    <a href="{{ route('usuarios.create') }}" class="btn-primary-sced">
        ➕ Novo Usuário
    </a>
@endsection

@section('content')

<style>
    /* Cards de estatísticas */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
        gap: 20px;
        margin-bottom: 32px;
    }
    .stat-card-user {
        background: var(--branco);
        border-radius: 16px;
        padding: 20px;
        border: 1px solid var(--cinza-200);
        transition: all 0.3s ease;
    }
    .stat-card-user:hover {
        transform: translateY(-2px);
        box-shadow: var(--sombra-hover);
    }
    .stat-card-user .stat-value {
        font-size: 28px;
        font-weight: 700;
        color: var(--azul-claro);
        line-height: 1.2;
    }
    .stat-card-user .stat-label {
        font-size: 12px;
        color: var(--cinza-400);
        margin-top: 6px;
    }
    
    /* Tabela */
    .usuarios-table {
        width: 100%;
        border-collapse: collapse;
    }
    .usuarios-table thead th {
        background: var(--cinza-100);
        padding: 14px 16px;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--cinza-500);
        border-bottom: 1px solid var(--cinza-200);
    }
    .usuarios-table tbody td {
        padding: 16px;
        border-bottom: 1px solid var(--cinza-100);
        font-size: 13px;
        vertical-align: middle;
    }
    .usuarios-table tbody tr:hover {
        background: var(--cinza-100);
    }
    
    /* Avatar */
    .user-avatar-table {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: var(--azul-claro);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 14px;
        flex-shrink: 0;
    }
    
    /* Badges */
    .perfil-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
    }
    .perfil-admin {
        background: #e0e7ff;
        color: #3730a3;
    }
    .perfil-n3 {
        background: #d1fae5;
        color: #065f46;
    }
    .perfil-operador {
        background: #f1f5f9;
        color: #475569;
    }
    
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
    }
    .status-ativo {
        background: #f0fdf4;
        color: #059669;
    }
    .status-inativo {
        background: #fef2f2;
        color: #dc2626;
    }
    
    .cargo-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
    }
    .cargo-N1 { background: #e0e7ff; color: #3730a3; }
    .cargo-N2 { background: #fef3c7; color: #92400e; }
    .cargo-N3 { background: #d1fae5; color: #065f46; }
    
    .btn-edit-user {
        padding: 6px 14px;
        background: transparent;
        border: 1.5px solid var(--azul-claro);
        color: var(--azul-claro);
        border-radius: 8px;
        font-size: 12px;
        font-weight: 500;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }
    .btn-edit-user:hover {
        background: var(--azul-claro);
        color: white;
        text-decoration: none;
    }
    
    /* Card mobile */
    .user-card-mobile {
        background: var(--branco);
        border-radius: 12px;
        border: 1px solid var(--cinza-200);
        padding: 16px;
        margin-bottom: 12px;
    }
    .user-card-header {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 12px;
    }
    .user-card-info {
        flex: 1;
    }
    .user-card-nome {
        font-weight: 700;
        font-size: 15px;
        color: var(--azul-escuro);
    }
    .user-card-email {
        font-size: 11px;
        color: var(--cinza-400);
    }
    .user-card-body {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 12px;
        padding: 10px 0;
        border-top: 1px solid var(--cinza-100);
        border-bottom: 1px solid var(--cinza-100);
    }
    .user-card-footer {
        display: flex;
        justify-content: flex-end;
    }
</style>

{{-- Cards de estatísticas --}}
<div class="stats-grid">
    <div class="stat-card-user">
        <div class="stat-value">{{ $usuarios->count() }}</div>
        <div class="stat-label">Total de Usuários</div>
    </div>
    <div class="stat-card-user">
        <div class="stat-value">{{ $usuarios->where('status', 'ativo')->count() }}</div>
        <div class="stat-label">Ativos</div>
    </div>
    <div class="stat-card-user">
        <div class="stat-value">{{ $usuarios->where('perfil', 'administrador')->count() }}</div>
        <div class="stat-label">Administradores</div>
    </div>
    <div class="stat-card-user">
        <div class="stat-value">{{ $usuarios->where('perfil', 'operador')->count() }}</div>
        <div class="stat-label">Operadores</div>
    </div>
</div>

{{-- TABELA DESKTOP --}}
<div class="card-sced d-none d-md-block">
    <div class="table-responsive">
        <table class="usuarios-table">
            <thead>
                <tr>
                    <th>Usuário</th>
                    <th>Departamento</th>
                    <th>Cargo</th>
                    <th>Perfil</th>
                    <th>Status</th>
                    <th>Cadastro</th>
                    <th class="text-center">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse($usuarios as $usuario)
                <tr>
                    <td>
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <div class="user-avatar-table">
                                {{ strtoupper(substr($usuario->nome, 0, 1)) }}
                            </div>
                            <div>
                                <div style="font-weight: 600;">{{ $usuario->nome }}</div>
                                <div style="font-size: 11px; color: var(--cinza-400);">{{ $usuario->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span style="font-size: 13px; color: var(--azul-escuro); font-weight: 500;">
                            {{ $usuario->departamentoRelacionado->nome ?? '—' }}
                        </span>
                    </td>
                    <td>
                        <span class="cargo-badge cargo-{{ $usuario->cargo }}">
                            {{ $usuario->cargo ?? '—' }}
                        </span>
                    </td>
                    <td>
                        @if($usuario->perfil === 'administrador')
                            <span class="perfil-badge perfil-admin">👑 Administrador</span>
                        @elseif($usuario->perfil === 'n3')
                            <span class="perfil-badge perfil-n3">⭐ Supervisor N3</span>
                        @else
                            <span class="perfil-badge perfil-operador">👤 Operador</span>
                        @endif
                    </td>
                    <td>
                        @if($usuario->status === 'ativo')
                            <span class="status-badge status-ativo">● Ativo</span>
                        @else
                            <span class="status-badge status-inativo">● Inativo</span>
                        @endif
                    </td>
                    <td style="font-size: 12px; color: var(--cinza-400);">
                        {{ $usuario->created_at->format('d/m/Y') }}
                    </td>
                    <td class="text-center">
                        @if($usuario->id !== auth()->id())
                            <a href="{{ route('usuarios.edit', $usuario) }}" class="btn-edit-user">
                                ✏️ Editar
                            </a>
                        @else
                            <span style="font-size: 12px; color: var(--cinza-400);">— você —</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-5">
                        <div class="text-muted">
                            <div class="fs-1 mb-2">👥</div>
                            <p>Nenhum usuário cadastrado ainda.</p>
                            <a href="{{ route('usuarios.create') }}" class="btn-primary-sced" style="display: inline-flex;">
                                Criar o primeiro usuário →
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- CARDS MOBILE --}}
<div class="d-md-none">
    @forelse($usuarios as $usuario)
    <div class="user-card-mobile">
        <div class="user-card-header">
            <div class="user-avatar-table">
                {{ strtoupper(substr($usuario->nome, 0, 1)) }}
            </div>
            <div class="user-card-info">
                <div class="user-card-nome">{{ $usuario->nome }}</div>
                <div class="user-card-email">{{ $usuario->email }}</div>
            </div>
        </div>
        <div class="user-card-body">
            <div>
                <span style="font-size: 10px; color: var(--cinza-400);">Departamento</span>
                <div style="font-size: 13px; font-weight: 500;">{{ $usuario->departamentoRelacionado->nome ?? '—' }}</div>
            </div>
            <div>
                <span style="font-size: 10px; color: var(--cinza-400);">Cargo</span>
                <div><span class="cargo-badge cargo-{{ $usuario->cargo }}">{{ $usuario->cargo ?? '—' }}</span></div>
            </div>
            <div>
                <span style="font-size: 10px; color: var(--cinza-400);">Perfil</span>
                <div>
                    @if($usuario->perfil === 'administrador')
                        <span class="perfil-badge perfil-admin">👑 Admin</span>
                    @elseif($usuario->perfil === 'n3')
                        <span class="perfil-badge perfil-n3">⭐ N3</span>
                    @else
                        <span class="perfil-badge perfil-operador">👤 Operador</span>
                    @endif
                </div>
            </div>
            <div>
                <span style="font-size: 10px; color: var(--cinza-400);">Status</span>
                <div>
                    @if($usuario->status === 'ativo')
                        <span class="status-badge status-ativo">Ativo</span>
                    @else
                        <span class="status-badge status-inativo">Inativo</span>
                    @endif
                </div>
            </div>
        </div>
        <div class="user-card-footer">
            @if($usuario->id !== auth()->id())
                <a href="{{ route('usuarios.edit', $usuario) }}" class="btn-edit-user">
                    ✏️ Editar
                </a>
            @else
                <span style="font-size: 12px; color: var(--cinza-400);">— você —</span>
            @endif
        </div>
    </div>
    @empty
    <div class="card-sced text-center py-5">
        <div class="text-muted">
            <div class="fs-1 mb-2">👥</div>
            <p>Nenhum usuário cadastrado ainda.</p>
            <a href="{{ route('usuarios.create') }}" class="btn-primary-sced">
                Criar o primeiro usuário →
            </a>
        </div>
    </div>
    @endforelse
</div>

@endsection