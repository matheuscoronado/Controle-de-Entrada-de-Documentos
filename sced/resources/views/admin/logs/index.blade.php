{{-- ============================================================
     resources/views/admin/logs/index.blade.php
     LOGS DE AUDITORIA - COM FILTROS E ESTILO MODERNO
     ============================================================ --}}
@extends('layouts.app')
@section('title', 'Logs de Auditoria')
@section('subtitle', 'Rastreamento completo de ações no sistema')

@section('content')

<style>
    /* Cards de estatísticas */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 20px;
        margin-bottom: 32px;
    }
    .stat-card-log {
        background: var(--branco);
        border-radius: 16px;
        padding: 20px;
        border: 1px solid var(--cinza-200);
        transition: all 0.3s ease;
    }
    .stat-card-log:hover {
        transform: translateY(-2px);
        box-shadow: var(--sombra-hover);
    }
    .stat-card-log .stat-value {
        font-size: 28px;
        font-weight: 700;
        color: var(--azul-claro);
        line-height: 1.2;
    }
    .stat-card-log .stat-label {
        font-size: 12px;
        color: var(--cinza-400);
        margin-top: 6px;
    }
    
    /* Filtros */
    .filtros-card {
        background: var(--branco);
        border-radius: 16px;
        border: 1px solid var(--cinza-200);
        padding: 20px;
        margin-bottom: 24px;
    }
    
    /* Tabela */
    .logs-table {
        width: 100%;
        border-collapse: collapse;
    }
    .logs-table thead th {
        background: var(--cinza-100);
        padding: 14px 12px;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--cinza-500);
        border-bottom: 1px solid var(--cinza-200);
    }
    .logs-table tbody td {
        padding: 14px 12px;
        border-bottom: 1px solid var(--cinza-100);
        font-size: 13px;
        vertical-align: middle;
    }
    .logs-table tbody tr:hover {
        background: var(--cinza-100);
    }
    
    /* Badges */
    .modulo-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
        background: var(--cinza-200);
        color: var(--cinza-600);
    }
    .modulo-badge.processos { background: #e0e7ff; color: #3730a3; }
    .modulo-badge.usuarios { background: #d1fae5; color: #065f46; }
    .modulo-badge.documentos { background: #fef3c7; color: #92400e; }
    .modulo-badge.servicos { background: #fce7f3; color: #be185d; }
    
    .acao-badge {
        font-family: monospace;
        font-size: 11px;
        font-weight: 700;
        background: var(--cinza-100);
        padding: 3px 8px;
        border-radius: 6px;
        color: var(--azul-claro);
    }
    
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
    }
    .status-anterior {
        background: #fef3c7;
        color: #92400e;
    }
    .status-novo {
        background: #d1fae5;
        color: #065f46;
    }
    
    /* Card mobile */
    .log-card-mobile {
        background: var(--branco);
        border-radius: 12px;
        border: 1px solid var(--cinza-200);
        padding: 16px;
        margin-bottom: 12px;
    }
    .log-card-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 12px;
        flex-wrap: wrap;
        gap: 8px;
    }
    .log-card-data {
        font-family: monospace;
        font-size: 12px;
        color: var(--cinza-400);
    }
    .log-card-body {
        margin-bottom: 12px;
    }
    .log-card-usuario {
        font-weight: 600;
        margin-bottom: 6px;
    }
    .log-card-acao {
        font-family: monospace;
        font-size: 12px;
        background: var(--cinza-100);
        padding: 4px 8px;
        border-radius: 6px;
        display: inline-block;
        margin-bottom: 8px;
    }
    .log-card-footer {
        display: flex;
        justify-content: flex-end;
        padding-top: 10px;
        border-top: 1px solid var(--cinza-200);
    }
    .btn-detalhe {
        padding: 5px 14px;
        background: transparent;
        border: 1.5px solid var(--azul-claro);
        color: var(--azul-claro);
        border-radius: 8px;
        font-size: 12px;
        transition: all 0.2s;
    }
    .btn-detalhe:hover {
        background: var(--azul-claro);
        color: white;
        text-decoration: none;
    }
</style>

{{-- Cards de estatísticas --}}
<div class="stats-grid">
    <div class="stat-card-log">
        <div class="stat-value">{{ $logs->total() }}</div>
        <div class="stat-label">Total de Registros</div>
    </div>
    <div class="stat-card-log">
        <div class="stat-value">{{ $logs->where('acao', 'CRIAR')->count() }}</div>
        <div class="stat-label">Criações</div>
    </div>
    <div class="stat-card-log">
        <div class="stat-value">{{ $logs->where('acao', 'ATUALIZAR')->count() }}</div>
        <div class="stat-label">Alterações</div>
    </div>
    <div class="stat-card-log">
        <div class="stat-value">{{ $logs->where('acao', 'EXCLUIR')->count() }}</div>
        <div class="stat-label">Exclusões</div>
    </div>
</div>

{{-- FILTROS --}}
<div class="filtros-card">
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <h5 class="mb-0" style="font-size: 14px; font-weight: 600; color: var(--azul-escuro);">
            🔍 Filtros de busca
        </h5>
        @if(request()->hasAny(['usuario_id', 'modulo', 'acao', 'data_inicio', 'data_fim']))
            <a href="{{ route('logs.index') }}" class="btn-outline-sced" style="padding: 5px 14px; font-size: 12px;">
                ✕ Limpar filtros
            </a>
        @endif
    </div>

    <form method="GET" action="{{ route('logs.index') }}">
        <div class="row g-3 align-items-end">
            <div class="col-12 col-sm-6 col-md-3 col-lg-2">
                <label class="form-label-sced">Usuário</label>
                <select name="usuario_id" class="form-input-sced">
                    <option value="">Todos</option>
                    @foreach($usuarios as $u)
                        <option value="{{ $u->id }}" {{ request('usuario_id') == $u->id ? 'selected' : '' }}>
                            {{ $u->nome }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-12 col-sm-6 col-md-3 col-lg-2">
                <label class="form-label-sced">Módulo</label>
                <select name="modulo" class="form-input-sced">
                    <option value="">Todos</option>
                    @foreach($modulos as $mod)
                        <option value="{{ $mod }}" {{ request('modulo') === $mod ? 'selected' : '' }}>
                            {{ ucfirst($mod) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-12 col-sm-6 col-md-3 col-lg-2">
                <label class="form-label-sced">Ação</label>
                <input type="text" name="acao" class="form-input-sced"
                       placeholder="Ex: ABRIR_PROCESSO" value="{{ request('acao') }}">
            </div>

            <div class="col-6 col-sm-3 col-md-2 col-lg-2">
                <label class="form-label-sced">Data Início</label>
                <input type="date" name="data_inicio" class="form-input-sced" value="{{ request('data_inicio') }}">
            </div>

            <div class="col-6 col-sm-3 col-md-2 col-lg-2">
                <label class="form-label-sced">Data Fim</label>
                <input type="date" name="data_fim" class="form-input-sced" value="{{ request('data_fim') }}">
            </div>

            <div class="col-12 col-sm-6 col-md-3 col-lg-2">
                <label class="form-label-sced d-none d-lg-block">&nbsp;</label>
                <button type="submit" class="btn-primary-sced w-100" style="justify-content: center;">
                    Filtrar
                </button>
            </div>
        </div>
    </form>
</div>

{{-- TABELA DESKTOP --}}
<div class="card-sced d-none d-md-block">
    <div class="table-responsive">
        <table class="logs-table">
            <thead>
                <tr>
                    <th style="width: 140px;">Data / Hora</th>
                    <th>Usuário</th>
                    <th>Módulo</th>
                    <th>Ação</th>
                    <th>Status</th>
                    <th>Descrição</th>
                    <th>IP</th>
                    <th style="text-align: center;">Det.</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                <tr>
                    <td style="font-family: monospace; font-size: 12px; white-space: nowrap; color: var(--cinza-600);">
                        {{ $log->data_hora->format('d/m/Y') }}<br>
                        <span style="color: var(--cinza-400);">{{ $log->data_hora->format('H:i:s') }}</span>
                    </td>
                    <td>
                        <div style="font-weight: 600;">{{ $log->usuario->nome ?? '—' }}</div>
                        <div style="font-size: 11px; color: var(--cinza-400);">{{ $log->usuario->label_perfil ?? '' }}</div>
                    </td>
                    <td>
                        @if($log->modulo)
                            <span class="modulo-badge {{ $log->modulo }}">
                                📁 {{ ucfirst($log->modulo) }}
                            </span>
                        @else
                            <span style="color: var(--cinza-400);">—</span>
                        @endif
                    </td>
                    <td>
                        <span class="acao-badge">{{ $log->acao }}</span>
                    </td>
                    <td style="font-size: 12px;">
                        @if($log->status_anterior)
                            <span class="status-badge status-anterior">{{ $log->status_anterior }}</span>
                            <span style="margin: 0 4px;">→</span>
                        @endif
                        @if($log->status_novo)
                            <span class="status-badge status-novo">{{ $log->status_novo }}</span>
                        @else
                            <span style="color: var(--cinza-400);">—</span>
                        @endif
                    </td>
                    <td style="max-width: 250px;">
                        <span style="color: var(--cinza-600); font-size: 12px;">
                            {{ Str::limit($log->descricao_legivel ?? '—', 70) }}
                        </span>
                        @if($log->uploads_realizados && count($log->uploads_realizados) > 0)
                            <div class="mt-1">
                                <span style="background: #eff6ff; color: #1d4ed8; padding: 2px 8px; border-radius: 6px; font-size: 10px; font-weight: 600;">
                                    📎 {{ count($log->uploads_realizados) }} arquivo(s)
                                </span>
                            </div>
                        @endif
                    </td>
                    <td style="font-family: monospace; font-size: 11px; color: var(--cinza-400);">
                        {{ $log->ip_origem ?? '—' }}
                    </td>
                    <td style="text-align: center;">
                        <a href="{{ route('logs.show', $log) }}" class="btn-outline-sced" style="padding: 4px 10px; font-size: 11px;">
                            🔍
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-5">
                        <div class="text-muted">
                            <div class="fs-1 mb-2">📋</div>
                            <p>Nenhum registro encontrado para os filtros selecionados.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($logs->hasPages())
    <div class="card-footer-sced py-3">
        {{ $logs->withQueryString()->links() }}
    </div>
    @endif
</div>

{{-- CARDS MOBILE --}}
<div class="d-md-none">
    @forelse($logs as $log)
    <div class="log-card-mobile">
        <div class="log-card-header">
            <div class="log-card-data">
                📅 {{ $log->data_hora->format('d/m/Y H:i:s') }}
            </div>
            <span class="modulo-badge {{ $log->modulo }}">
                📁 {{ ucfirst($log->modulo ?? 'geral') }}
            </span>
        </div>
        <div class="log-card-body">
            <div class="log-card-usuario">
                👤 {{ $log->usuario->nome ?? '—' }}
                <span style="font-size: 11px; color: var(--cinza-400);">({{ $log->usuario->label_perfil ?? '' }})</span>
            </div>
            <div class="log-card-acao">
                {{ $log->acao }}
            </div>
            @if($log->status_anterior || $log->status_novo)
                <div style="margin: 8px 0;">
                    <span class="status-badge status-anterior">{{ $log->status_anterior ?? '—' }}</span>
                    <span style="margin: 0 4px;">→</span>
                    <span class="status-badge status-novo">{{ $log->status_novo ?? '—' }}</span>
                </div>
            @endif
            @if($log->descricao_legivel)
                <div style="font-size: 12px; color: var(--cinza-600); margin-top: 6px;">
                    {{ Str::limit($log->descricao_legivel, 100) }}
                </div>
            @endif
            @if($log->ip_origem)
                <div style="font-size: 11px; color: var(--cinza-400); margin-top: 6px;">
                    🌐 IP: {{ $log->ip_origem }}
                </div>
            @endif
        </div>
        <div class="log-card-footer">
            <a href="{{ route('logs.show', $log) }}" class="btn-detalhe">
                🔍 Ver detalhes
            </a>
        </div>
    </div>
    @empty
    <div class="card-sced text-center py-5">
        <div class="text-muted">
            <div class="fs-1 mb-2">📋</div>
            <p>Nenhum registro encontrado para os filtros selecionados.</p>
        </div>
    </div>
    @endforelse

    @if($logs->hasPages())
    <div class="mt-3">
        {{ $logs->withQueryString()->links() }}
    </div>
    @endif
</div>

@endsection