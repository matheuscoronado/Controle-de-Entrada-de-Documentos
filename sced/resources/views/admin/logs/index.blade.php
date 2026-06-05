{{-- ============================================================
     resources/views/admin/logs/index.blade.php
     Tela de Logs e Auditoria — Acesso exclusivo do Administrador
     ============================================================ --}}
@extends('layouts.app')
@section('title', 'Logs de Auditoria')
@section('subtitle', 'Rastreamento completo de ações no sistema')

@section('content')

{{-- Painel de Filtros --}}
<div class="card-sced mb-4">
    <div style="font-size:13px;font-weight:700;text-transform:uppercase;letter-spacing:1.2px;color:var(--cinza-400);margin-bottom:16px;">
        🔍 Filtros
    </div>
    <form method="GET" action="{{ route('logs.index') }}">
        <div class="row g-3 align-items-end">

            <div class="col-sm-6 col-lg-3">
                <label class="form-label-sced">Usuário</label>
                <select name="usuario_id" class="form-control-sced">
                    <option value="">Todos</option>
                    @foreach($usuarios as $u)
                        <option value="{{ $u->id }}" {{ request('usuario_id') == $u->id ? 'selected' : '' }}>
                            {{ $u->nome }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-sm-6 col-lg-2">
                <label class="form-label-sced">Módulo</label>
                <select name="modulo" class="form-control-sced">
                    <option value="">Todos</option>
                    @foreach($modulos as $mod)
                        <option value="{{ $mod }}" {{ request('modulo') === $mod ? 'selected' : '' }}>
                            {{ ucfirst($mod) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-sm-6 col-lg-2">
                <label class="form-label-sced">Ação</label>
                <input type="text" name="acao" class="form-control-sced"
                       placeholder="Ex: ATUALIZAR..." value="{{ request('acao') }}">
            </div>

            <div class="col-sm-6 col-lg-2">
                <label class="form-label-sced">Data Início</label>
                <input type="date" name="data_inicio" class="form-control-sced"
                       value="{{ request('data_inicio') }}">
            </div>

            <div class="col-sm-6 col-lg-2">
                <label class="form-label-sced">Data Fim</label>
                <input type="date" name="data_fim" class="form-control-sced"
                       value="{{ request('data_fim') }}">
            </div>

            <div class="col-sm-6 col-lg-1">
                <button type="submit" class="btn-primary-sced" style="width:100%;">Filtrar</button>
            </div>

        </div>
    </form>
</div>

{{-- Tabela de Logs --}}
<div class="card-sced">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;">
        <div style="font-size:13px;color:var(--cinza-400);">
            Exibindo <strong style="color:var(--cinza-800);">{{ $logs->total() }}</strong> registros
        </div>
        @if(request()->hasAny(['usuario_id','modulo','acao','data_inicio','data_fim']))
            <a href="{{ route('logs.index') }}" style="font-size:13px;color:var(--vermelho);">✕ Limpar filtros</a>
        @endif
    </div>

    <div style="overflow-x:auto;">
        <table class="tabela-sced">
            <thead>
                <tr>
                    <th style="width:140px;">Data / Hora</th>
                    <th>Usuário</th>
                    <th>Módulo</th>
                    <th>Ação</th>
                    <th>Status Anterior</th>
                    <th>Novo Status</th>
                    <th>Descrição</th>
                    <th>IP</th>
                    <th style="text-align:center;">Det.</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                <tr>
                    <td style="font-size:12px;font-family:'JetBrains Mono',monospace;white-space:nowrap;color:var(--cinza-600);">
                        {{ $log->data_hora->format('d/m/Y') }}<br>
                        <span style="color:var(--cinza-400);">{{ $log->data_hora->format('H:i:s') }}</span>
                    </td>

                    <td>
                        <div style="font-weight:600;font-size:14px;">{{ $log->usuario->nome ?? '—' }}</div>
                        <div style="font-size:11px;color:var(--cinza-400);">{{ $log->usuario->label_perfil ?? '' }}</div>
                    </td>

                    <td>
                        @if($log->modulo)
                            <span style="background:var(--cinza-200);padding:2px 8px;border-radius:6px;font-size:12px;font-weight:600;">
                                {{ ucfirst($log->modulo) }}
                            </span>
                        @else
                            <span style="color:var(--cinza-400);">—</span>
                        @endif
                    </td>

                    <td>
                        <span style="font-family:'JetBrains Mono',monospace;font-size:12px;color:var(--azul-claro);font-weight:600;">
                            {{ $log->acao }}
                        </span>
                    </td>

                    <td style="font-size:13px;">
                        @if($log->status_anterior)
                            <span style="background:#fef3c7;color:#92400e;padding:2px 8px;border-radius:6px;font-size:12px;">
                                {{ $log->status_anterior }}
                            </span>
                        @else
                            <span style="color:var(--cinza-400);">—</span>
                        @endif
                    </td>

                    <td style="font-size:13px;">
                        @if($log->status_novo)
                            <span style="background:#d1fae5;color:#065f46;padding:2px 8px;border-radius:6px;font-size:12px;">
                                {{ $log->status_novo }}
                            </span>
                        @else
                            <span style="color:var(--cinza-400);">—</span>
                        @endif
                    </td>

                    <td style="font-size:13px;max-width:280px;">
                        <span style="color:var(--cinza-600);">
                            {{ Str::limit($log->descricao_legivel ?? '—', 80) }}
                        </span>
                        @if($log->uploads_realizados && count($log->uploads_realizados) > 0)
                            <div style="margin-top:3px;">
                                <span style="background:#eff6ff;color:#1d4ed8;padding:2px 7px;border-radius:6px;font-size:11px;font-weight:600;">
                                    📎 {{ count($log->uploads_realizados) }} arquivo(s)
                                </span>
                            </div>
                        @endif
                    </td>

                    <td style="font-size:12px;font-family:'JetBrains Mono',monospace;color:var(--cinza-400);">
                        {{ $log->ip_origem ?? '—' }}
                    </td>

                    <td style="text-align:center;">
                        <a href="{{ route('logs.show', $log) }}" class="btn-outline-sced" style="padding:4px 10px;font-size:12px;">
                            🔎
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" style="text-align:center;padding:48px;color:var(--cinza-400);">
                        <div style="font-size:32px;margin-bottom:8px;">📋</div>
                        Nenhum registro encontrado para os filtros selecionados.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Paginação --}}
    @if($logs->hasPages())
    <div style="margin-top:20px;display:flex;justify-content:center;">
        {{ $logs->links() }}
    </div>
    @endif
</div>

@endsection

@push('styles')
<style>
.form-label-sced { font-size:13px;font-weight:600;color:var(--cinza-600);margin-bottom:6px;display:block; }
.form-control-sced { width:100%;padding:9px 12px;border:1.5px solid var(--cinza-200);border-radius:var(--radius-sm);font-size:13px;font-family:'Sora',sans-serif;background:var(--branco);color:var(--cinza-800);transition:var(--transicao);outline:none; }
.form-control-sced:focus { border-color:var(--azul-claro);box-shadow:0 0 0 3px rgba(37,99,235,0.12); }
</style>
@endpush
