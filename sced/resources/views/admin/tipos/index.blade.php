{{-- ============================================================
     resources/views/admin/tipos/index.blade.php
     LISTAGEM DE SERVIÇOS - CORRIGIDA
     ============================================================ --}}
@extends('layouts.app')
@section('title', 'Cadastro de Serviço')
@section('subtitle', 'Gerencie os serviços do sistema')

@section('topbar-actions')
    <a href="{{ route('tipos.create') }}" class="btn-primary-sced">
        ➕ Novo Serviço
    </a>
@endsection

@section('content')

<style>
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 20px;
        margin-bottom: 32px;
    }
    .stat-card-servico {
        background: var(--branco);
        border-radius: 16px;
        padding: 20px;
        border: 1px solid var(--cinza-200);
        transition: all 0.3s ease;
    }
    .stat-card-servico:hover {
        transform: translateY(-2px);
        box-shadow: var(--sombra-hover);
    }
    .stat-card-servico .stat-value {
        font-size: 28px;
        font-weight: 700;
        color: var(--azul-claro);
        line-height: 1.2;
    }
    .stat-card-servico .stat-label {
        font-size: 12px;
        color: var(--cinza-400);
        margin-top: 6px;
    }
    
    .servicos-table {
        width: 100%;
        border-collapse: collapse;
    }
    .servicos-table thead th {
        background: var(--cinza-100);
        padding: 14px 16px;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--cinza-500);
        border-bottom: 1px solid var(--cinza-200);
    }
    .servicos-table tbody td {
        padding: 16px;
        border-bottom: 1px solid var(--cinza-100);
        font-size: 13px;
        vertical-align: middle;
    }
    .servicos-table tbody tr:hover {
        background: var(--cinza-100);
    }
    
    .cargo-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
        margin: 2px;
    }
    .cargo-badge.N1 { background: #e0e7ff; color: #3730a3; }
    .cargo-badge.N2 { background: #fef3c7; color: #92400e; }
    .cargo-badge.N3 { background: #d1fae5; color: #065f46; }
    
    .doc-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 500;
        background: var(--cinza-100);
        color: var(--cinza-600);
        margin: 2px;
    }
    .doc-badge.obrigatorio {
        background: #fef2f2;
        color: #dc2626;
    }
    .doc-badge.opcional {
        background: #f0f9ff;
        color: #0369a1;
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
    .status-badge.ativo {
        background: #f0fdf4;
        color: #059669;
    }
    .status-badge.inativo {
        background: #fef2f2;
        color: #dc2626;
    }
    
    .btn-edit {
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
    .btn-edit:hover {
        background: var(--azul-claro);
        color: white;
        text-decoration: none;
    }
    
    .servico-card-mobile {
        background: var(--branco);
        border-radius: 12px;
        border: 1px solid var(--cinza-200);
        padding: 16px;
        margin-bottom: 12px;
    }
    .servico-card-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 12px;
        flex-wrap: wrap;
        gap: 8px;
    }
    .servico-card-nome {
        font-weight: 700;
        font-size: 15px;
        color: var(--azul-escuro);
    }
    .servico-card-descricao {
        font-size: 12px;
        color: var(--cinza-500);
        margin-bottom: 10px;
    }
    .servico-card-footer {
        display: flex;
        justify-content: flex-end;
        padding-top: 10px;
        border-top: 1px solid var(--cinza-200);
    }
</style>

{{-- Cards de estatísticas --}}
<div class="stats-grid">
    <div class="stat-card-servico">
        <div class="stat-value">{{ $tipos->count() }}</div>
        <div class="stat-label">Total de Serviços</div>
    </div>
    <div class="stat-card-servico">
        <div class="stat-value">{{ $tipos->where('status', 'ativo')->count() }}</div>
        <div class="stat-label">Ativos</div>
    </div>
    <div class="stat-card-servico">
        <div class="stat-value">{{ $tipos->filter(fn($s) => count($s->cargos_responsaveis ?? []) > 0)->count() }}</div>
        <div class="stat-label">Com Cargos Definidos</div>
    </div>
    <div class="stat-card-servico">
        <div class="stat-value">{{ $tipos->filter(fn($s) => $s->documentosTipo->count() > 0)->count() }}</div>
        <div class="stat-label">Com Documentos Vinculados</div>
    </div>
</div>

{{-- TABELA DESKTOP --}}
<div class="card-sced d-none d-md-block">
    <div class="table-responsive">
        <table class="servicos-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Serviço</th>
                    <th>Documentos Vinculados</th>
                    <th>Setor Destino</th>
                    <th>Cargos Responsáveis</th>
                    <th>Status</th>
                    <th class="text-center">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tipos as $servico)
                <tr>
                    <td style="color: var(--cinza-400); font-size: 12px;">{{ $servico->id }}</td>
                    <td>
                        <div style="font-weight: 600;">{{ $servico->nome }}</div>
                        @if($servico->descricao)
                            <div style="font-size: 11px; color: var(--cinza-400); margin-top: 2px;">
                                {{ Str::limit($servico->descricao, 60) }}
                            </div>
                        @endif
                    </td>
                    <td>
                        @if($servico->documentosTipo->count() > 0)
                            <div style="display: flex; flex-wrap: wrap; gap: 4px;">
                                @foreach($servico->documentosTipo as $doc)
                                    <span class="doc-badge {{ $doc->tipo }}">
                                        📄 {{ $doc->nome }}
                                    </span>
                                @endforeach
                            </div>
                        @else
                            <span style="color: var(--cinza-400); font-size: 12px;">—</span>
                        @endif
                    </td>
                    <td>
                        @if($servico->departamentoDestino)
                            <span style="display: flex; align-items: center; gap: 4px;">
                                🏢 {{ $servico->departamentoDestino->nome }}
                            </span>
                        @else
                            <span style="color: var(--cinza-400);">—</span>
                        @endif
                    </td>
                    <td>
                        @php $cargos = $servico->cargos_responsaveis ?? []; @endphp
                        @if(count($cargos) > 0)
                            <div style="display: flex; flex-wrap: wrap; gap: 4px;">
                                @foreach($cargos as $cargo)
                                    <span class="cargo-badge {{ $cargo }}">{{ $cargo }}</span>
                                @endforeach
                            </div>
                        @else
                            <span style="color: var(--cinza-400);">—</span>
                        @endif
                    </td>
                    <td>
                        <span class="status-badge {{ $servico->status }}">
                            ● {{ $servico->status == 'ativo' ? 'Ativo' : 'Inativo' }}
                        </span>
                    </td>
                    <td class="text-center">
                        <a href="{{ route('tipos.edit', $servico) }}" class="btn-edit">
                            ✏️ Editar
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-5">
                        <div class="text-muted">
                            <div class="fs-1 mb-2">🏷️</div>
                            <p>Nenhum serviço cadastrado ainda.</p>
                            <a href="{{ route('tipos.create') }}" class="btn-primary-sced" style="display: inline-flex;">
                                Criar o primeiro serviço →
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
    @forelse($tipos as $servico)
    <div class="servico-card-mobile">
        <div class="servico-card-header">
            <div class="servico-card-nome">🏷️ {{ $servico->nome }}</div>
            <span class="status-badge {{ $servico->status }}">
                ● {{ $servico->status == 'ativo' ? 'Ativo' : 'Inativo' }}
            </span>
        </div>
        @if($servico->descricao)
            <div class="servico-card-descricao">{{ Str::limit($servico->descricao, 80) }}</div>
        @endif
        <div class="servico-card-body">
            <div style="margin-bottom: 8px;">
                <span style="font-size: 11px; color: var(--cinza-400);">Setor:</span>
                <span style="font-size: 13px; margin-left: 5px;">
                    {{ $servico->departamentoDestino->nome ?? '—' }}
                </span>
            </div>
            <div style="margin-bottom: 8px;">
                <span style="font-size: 11px; color: var(--cinza-400);">Cargos:</span>
                <div style="display: flex; flex-wrap: wrap; gap: 4px; margin-top: 4px;">
                    @foreach($servico->cargos_responsaveis ?? [] as $cargo)
                        <span class="cargo-badge {{ $cargo }}">{{ $cargo }}</span>
                    @endforeach
                </div>
            </div>
            <div>
                <span style="font-size: 11px; color: var(--cinza-400);">Documentos:</span>
                <div style="display: flex; flex-wrap: wrap; gap: 4px; margin-top: 4px;">
                    @foreach($servico->documentosTipo as $doc)
                        <span class="doc-badge {{ $doc->tipo }}">📄 {{ $doc->nome }}</span>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="servico-card-footer">
            <a href="{{ route('tipos.edit', $servico) }}" class="btn-edit">
                ✏️ Editar
            </a>
        </div>
    </div>
    @empty
    <div class="card-sced text-center py-5">
        <div class="text-muted">
            <div class="fs-1 mb-2">🏷️</div>
            <p>Nenhum serviço cadastrado ainda.</p>
            <a href="{{ route('tipos.create') }}" class="btn-primary-sced">
                Criar o primeiro serviço →
            </a>
        </div>
    </div>
    @endforelse
</div>

@endsection