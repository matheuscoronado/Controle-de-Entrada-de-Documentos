{{-- ============================================================
     resources/views/admin/logs/show.blade.php
     Detalhes de um Registro de Auditoria
     ============================================================ --}}
@extends('layouts.app')
@section('title', 'Detalhe do Log #' . $log->id)
@section('subtitle', 'Rastreamento completo desta ação')

@section('topbar-actions')
    <a href="{{ route('logs.index') }}" class="btn-outline-sced">← Voltar aos Logs</a>
@endsection

@section('content')
<div class="row">

    {{-- Coluna principal --}}
    <div class="col-lg-8">

        {{-- Cabeçalho --}}
        <div class="card-sced mb-4">
            <div style="display:flex;align-items:flex-start;justify-content:space-between;flex-wrap:wrap;gap:12px;">
                <div>
                    <div style="font-size:20px;font-weight:700;margin-bottom:4px;">
                        {{ $log->acao }}
                    </div>
                    <div style="font-size:13px;color:var(--cinza-400);">
                        Registro #{{ $log->id }} — {{ $log->data_hora->format('d/m/Y \à\s H:i:s') }}
                    </div>
                </div>
                @if($log->modulo)
                    <span style="background:var(--azul-claro);color:#fff;padding:6px 16px;border-radius:20px;font-size:13px;font-weight:600;">
                        {{ ucfirst($log->modulo) }}
                    </span>
                @endif
            </div>

            @if($log->descricao_legivel)
            <div style="margin-top:16px;padding:14px;background:var(--cinza-100);border-radius:var(--radius-sm);font-size:14px;color:var(--cinza-600);border-left:3px solid var(--azul-claro);">
                {{ $log->descricao_legivel }}
            </div>
            @endif
        </div>

        {{-- Alteração de Status --}}
        @if($log->status_anterior || $log->status_novo)
        <div class="card-sced mb-4">
            <div style="font-size:13px;font-weight:700;text-transform:uppercase;letter-spacing:1.2px;color:var(--cinza-400);margin-bottom:16px;">
                🔄 Alteração de Status
            </div>
            <div style="display:flex;align-items:center;gap:16px;flex-wrap:wrap;">
                <div style="text-align:center;">
                    <div style="font-size:11px;color:var(--cinza-400);margin-bottom:6px;font-weight:600;">ANTERIOR</div>
                    <span style="background:#fef3c7;color:#92400e;padding:8px 18px;border-radius:8px;font-size:14px;font-weight:700;display:inline-block;">
                        {{ $log->status_anterior ?? '—' }}
                    </span>
                </div>
                <div style="font-size:24px;color:var(--cinza-400);">→</div>
                <div style="text-align:center;">
                    <div style="font-size:11px;color:var(--cinza-400);margin-bottom:6px;font-weight:600;">NOVO</div>
                    <span style="background:#d1fae5;color:#065f46;padding:8px 18px;border-radius:8px;font-size:14px;font-weight:700;display:inline-block;">
                        {{ $log->status_novo ?? '—' }}
                    </span>
                </div>
            </div>
        </div>
        @endif

        {{-- Campos Alterados --}}
        @if($log->campos_alterados && count($log->campos_alterados) > 0)
        <div class="card-sced mb-4">
            <div style="font-size:13px;font-weight:700;text-transform:uppercase;letter-spacing:1.2px;color:var(--cinza-400);margin-bottom:16px;">
                📝 Campos Alterados
            </div>
            <table class="tabela-sced">
                <thead>
                    <tr>
                        <th>Campo</th>
                        <th>Valor Anterior</th>
                        <th>Novo Valor</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($log->campos_alterados as $campo => $vals)
                    <tr>
                        <td style="font-weight:600;font-family:'JetBrains Mono',monospace;font-size:12px;">{{ $campo }}</td>
                        <td style="color:var(--vermelho);font-size:13px;">{{ $vals['de'] ?? '—' }}</td>
                        <td style="color:var(--verde);font-size:13px;font-weight:600;">{{ $vals['para'] ?? '—' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

        {{-- Uploads --}}
        @if($log->uploads_realizados && count($log->uploads_realizados) > 0)
        <div class="card-sced mb-4">
            <div style="font-size:13px;font-weight:700;text-transform:uppercase;letter-spacing:1.2px;color:var(--cinza-400);margin-bottom:16px;">
                📎 Uploads Realizados
            </div>
            @foreach($log->uploads_realizados as $upload)
            <div style="display:flex;align-items:center;gap:10px;padding:10px;background:var(--cinza-100);border-radius:var(--radius-sm);margin-bottom:8px;">
                <span style="font-size:20px;">📄</span>
                <span style="font-size:13px;font-weight:500;">{{ $upload }}</span>
            </div>
            @endforeach
        </div>
        @endif

    </div>

    {{-- Coluna lateral — contexto --}}
    <div class="col-lg-4">
        <div class="card-sced mb-4">
            <div style="font-size:13px;font-weight:700;text-transform:uppercase;letter-spacing:1.2px;color:var(--cinza-400);margin-bottom:16px;">
                👤 Usuário Responsável
            </div>

            <div style="display:flex;align-items:center;gap:12px;margin-bottom:16px;">
                <div style="width:44px;height:44px;border-radius:50%;background:var(--azul-claro);display:flex;align-items:center;justify-content:center;font-size:18px;font-weight:700;color:#fff;flex-shrink:0;">
                    {{ strtoupper(substr($log->usuario->nome ?? '?', 0, 1)) }}
                </div>
                <div>
                    <div style="font-weight:600;font-size:15px;">{{ $log->usuario->nome ?? '—' }}</div>
                    <div style="font-size:12px;color:var(--cinza-400);">{{ $log->usuario->label_perfil ?? '' }}</div>
                </div>
            </div>

            <div style="display:flex;flex-direction:column;gap:10px;">
                <div>
                    <div style="font-size:11px;font-weight:600;color:var(--cinza-400);margin-bottom:2px;">TABELA AFETADA</div>
                    <div style="font-family:'JetBrains Mono',monospace;font-size:13px;">{{ $log->tabela_afetada ?? '—' }}</div>
                </div>
                <div>
                    <div style="font-size:11px;font-weight:600;color:var(--cinza-400);margin-bottom:2px;">ID DO REGISTRO</div>
                    <div style="font-family:'JetBrains Mono',monospace;font-size:13px;">{{ $log->registro_id ?? '—' }}</div>
                </div>
                <div>
                    <div style="font-size:11px;font-weight:600;color:var(--cinza-400);margin-bottom:2px;">IP DE ORIGEM</div>
                    <div style="font-family:'JetBrains Mono',monospace;font-size:13px;">{{ $log->ip_origem ?? '—' }}</div>
                </div>
                <div>
                    <div style="font-size:11px;font-weight:600;color:var(--cinza-400);margin-bottom:2px;">DATA / HORA</div>
                    <div style="font-size:13px;">{{ $log->data_hora->format('d/m/Y H:i:s') }}</div>
                </div>
                @if($log->user_agent)
                <div>
                    <div style="font-size:11px;font-weight:600;color:var(--cinza-400);margin-bottom:2px;">NAVEGADOR</div>
                    <div style="font-size:11px;color:var(--cinza-400);word-break:break-all;">{{ Str::limit($log->user_agent, 80) }}</div>
                </div>
                @endif
            </div>
        </div>
    </div>

</div>
@endsection
