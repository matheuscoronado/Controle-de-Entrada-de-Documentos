{{-- ============================================================
     resources/views/admin/logs/show.blade.php
     DETALHES DO LOG DE AUDITORIA
     ============================================================ --}}
@extends('layouts.app')
@section('title', 'Detalhe do Log #' . $log->id)
@section('subtitle', 'Rastreamento completo desta ação')

@section('topbar-actions')
    <a href="{{ route('logs.index') }}" class="btn-outline-sced">← Voltar aos Logs</a>
@endsection

@section('content')

<style>
    .detail-card {
        background: var(--branco);
        border-radius: 16px;
        border: 1px solid var(--cinza-200);
        overflow: hidden;
        margin-bottom: 24px;
    }
    .detail-header {
        padding: 20px 24px;
        background: linear-gradient(135deg, var(--azul-escuro) 0%, var(--azul-medio) 100%);
        color: white;
    }
    .detail-header h2 {
        font-size: 20px;
        font-weight: 700;
        margin-bottom: 4px;
    }
    .detail-header p {
        font-size: 12px;
        opacity: 0.8;
        margin: 0;
    }
    .detail-body {
        padding: 24px;
    }
    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 24px;
    }
    .info-item {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }
    .info-label {
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--cinza-400);
    }
    .info-value {
        font-size: 14px;
        font-weight: 600;
        color: var(--cinza-800);
        font-family: monospace;
    }
    .status-change {
        background: var(--cinza-100);
        border-radius: 12px;
        padding: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 30px;
        flex-wrap: wrap;
        margin-bottom: 24px;
    }
    .status-box {
        text-align: center;
        padding: 15px 25px;
        background: white;
        border-radius: 12px;
        min-width: 150px;
    }
    .status-box .label {
        font-size: 11px;
        color: var(--cinza-400);
        margin-bottom: 8px;
    }
    .status-box .value {
        font-size: 16px;
        font-weight: 700;
    }
    .status-box.anterior .value { color: #92400e; }
    .status-box.novo .value { color: #059669; }
    
    .campos-table {
        width: 100%;
        border-collapse: collapse;
    }
    .campos-table th {
        background: var(--cinza-100);
        padding: 12px;
        font-size: 12px;
        text-align: left;
    }
    .campos-table td {
        padding: 12px;
        border-bottom: 1px solid var(--cinza-200);
        font-size: 13px;
    }
    .valor-antigo {
        color: #dc2626;
        font-family: monospace;
    }
    .valor-novo {
        color: #059669;
        font-family: monospace;
        font-weight: 600;
    }
    
    .user-card {
        background: var(--cinza-100);
        border-radius: 12px;
        padding: 20px;
        display: flex;
        align-items: center;
        gap: 15px;
    }
    .user-avatar {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: var(--azul-claro);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        font-weight: 700;
    }
    
    .upload-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px;
        background: var(--cinza-100);
        border-radius: 8px;
        margin-bottom: 8px;
    }
</style>

<div class="row">
    <div class="col-lg-8">

        {{-- Cabeçalho --}}
        <div class="detail-card">
            <div class="detail-header">
                <h2>{{ $log->acao }}</h2>
                <p>Registro #{{ $log->id }} — {{ $log->data_hora->format('d/m/Y \à\s H:i:s') }}</p>
            </div>
            <div class="detail-body">

                {{-- Descrição legível --}}
                @if($log->descricao_legivel)
                <div style="background: var(--cinza-100); border-radius: 12px; padding: 16px; margin-bottom: 24px; border-left: 3px solid var(--azul-claro);">
                    <div style="font-size: 14px; color: var(--cinza-600);">
                        {{ $log->descricao_legivel }}
                    </div>
                </div>
                @endif

                {{-- Informações básicas --}}
                <div class="info-grid">
                    <div class="info-item">
                        <span class="info-label">Módulo</span>
                        <span class="info-value">{{ ucfirst($log->modulo ?? '—') }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Tabela Afetada</span>
                        <span class="info-value">{{ $log->tabela_afetada ?? '—' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">ID do Registro</span>
                        <span class="info-value">{{ $log->registro_id ?? '—' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">IP de Origem</span>
                        <span class="info-value">{{ $log->ip_origem ?? '—' }}</span>
                    </div>
                </div>

                {{-- Alteração de Status --}}
                @if($log->status_anterior || $log->status_novo)
                <div class="status-change">
                    <div class="status-box anterior">
                        <div class="label">STATUS ANTERIOR</div>
                        <div class="value">{{ $log->status_anterior ?? '—' }}</div>
                    </div>
                    <div style="font-size: 28px; color: var(--cinza-400);">→</div>
                    <div class="status-box novo">
                        <div class="label">NOVO STATUS</div>
                        <div class="value">{{ $log->status_novo ?? '—' }}</div>
                    </div>
                </div>
                @endif

                {{-- Campos Alterados --}}
                @if($log->campos_alterados && count($log->campos_alterados) > 0)
                <div style="margin-top: 24px;">
                    <h4 style="font-size: 14px; font-weight: 700; margin-bottom: 16px;">📝 Campos Alterados</h4>
                    <div class="table-responsive">
                        <table class="campos-table">
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
                                    <td style="font-weight: 600; font-family: monospace;">{{ $campo }}</td>
                                    <td class="valor-antigo">{{ $vals['de'] ?? '—' }}</td>
                                    <td class="valor-novo">{{ $vals['para'] ?? '—' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif

                {{-- Uploads --}}
                @if($log->uploads_realizados && count($log->uploads_realizados) > 0)
                <div style="margin-top: 24px;">
                    <h4 style="font-size: 14px; font-weight: 700; margin-bottom: 16px;">📎 Uploads Realizados</h4>
                    @foreach($log->uploads_realizados as $upload)
                    <div class="upload-item">
                        <span style="font-size: 20px;">📄</span>
                        <span>{{ $upload }}</span>
                    </div>
                    @endforeach
                </div>
                @endif

            </div>
        </div>

    </div>

    <div class="col-lg-4">

        {{-- Card do Usuário --}}
        <div class="detail-card">
            <div class="detail-header" style="background: var(--cinza-800);">
                <h2 style="font-size: 16px;">👤 Usuário Responsável</h2>
            </div>
            <div class="detail-body">
                <div class="user-card">
                    <div class="user-avatar">
                        {{ strtoupper(substr($log->usuario->nome ?? '?', 0, 1)) }}
                    </div>
                    <div>
                        <div style="font-weight: 700; font-size: 16px;">{{ $log->usuario->nome ?? '—' }}</div>
                        <div style="font-size: 12px; color: var(--cinza-400);">{{ $log->usuario->label_perfil ?? '' }}</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Informações técnicas --}}
        <div class="detail-card">
            <div class="detail-header" style="background: var(--cinza-800);">
                <h2 style="font-size: 16px;">ℹ️ Informações Técnicas</h2>
            </div>
            <div class="detail-body">
                <div style="margin-bottom: 15px;">
                    <div class="info-label">DATA / HORA</div>
                    <div class="info-value">{{ $log->data_hora->format('d/m/Y H:i:s') }}</div>
                </div>
                <div style="margin-bottom: 15px;">
                    <div class="info-label">IP DE ORIGEM</div>
                    <div class="info-value">{{ $log->ip_origem ?? '—' }}</div>
                </div>
                @if($log->user_agent)
                <div>
                    <div class="info-label">NAVEGADOR</div>
                    <div class="info-value" style="font-size: 11px; word-break: break-all;">
                        {{ Str::limit($log->user_agent, 100) }}
                    </div>
                </div>
                @endif
            </div>
        </div>

    </div>
</div>

@endsection