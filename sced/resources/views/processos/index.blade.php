{{-- ============================================================
     resources/views/processos/index.blade.php
     TELA DE PROCESSOS - COM DESTAQUE PARA AÇÕES PENDENTES
     ============================================================ --}}
@extends('layouts.app')
@section('title', 'Processos')
@section('subtitle', 'Consulta e acompanhamento de processos')

@section('topbar-actions')
    <a href="{{ route('documentos.create') }}" class="btn-primary-sced">
        ➕ Novo Processo
    </a>
@endsection

@section('content')

<style>
    /* Abas principais */
    .nav-tabs-custom {
        border-bottom: 2px solid var(--cinza-200);
        margin-bottom: 24px;
    }
    .nav-tabs-custom .nav-link {
        border: none;
        font-size: 14px;
        font-weight: 600;
        padding: 10px 24px;
        color: var(--cinza-600);
        background: transparent;
        transition: all 0.2s ease;
    }
    .nav-tabs-custom .nav-link:hover {
        color: var(--azul-claro);
        border-bottom: 2px solid var(--azul-claro);
        margin-bottom: -2px;
    }
    .nav-tabs-custom .nav-link.active {
        color: var(--azul-claro);
        background: transparent;
        border-bottom: 2px solid var(--azul-claro);
        margin-bottom: -2px;
    }
    
    /* Sub-abas */
    .sub-tabs {
        margin-left: 20px;
        border-bottom: none !important;
        margin-bottom: 20px !important;
    }
    .sub-tabs .nav-link {
        font-size: 13px;
        padding: 6px 16px;
        color: var(--cinza-500);
    }
    .sub-tabs .nav-link.active {
        color: var(--azul-claro);
        border-bottom: 2px solid var(--azul-claro);
    }
    
    /* Badges de contagem */
    .badge-count {
        background: var(--cinza-200);
        color: var(--cinza-600);
        border-radius: 20px;
        padding: 2px 8px;
        font-size: 11px;
        font-weight: 600;
        margin-left: 8px;
    }
    .badge-count.urgent {
        background: var(--vermelho);
        color: white;
    }
    .badge-count.warning {
        background: var(--amarelo);
        color: white;
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
    .tabela-processos {
        width: 100%;
        border-collapse: collapse;
    }
    .tabela-processos thead th {
        background: var(--cinza-100);
        padding: 12px 16px;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--cinza-500);
        border-bottom: 1px solid var(--cinza-200);
    }
    .tabela-processos tbody td {
        padding: 14px 16px;
        border-bottom: 1px solid var(--cinza-100);
        font-size: 13px;
        vertical-align: middle;
    }
    .tabela-processos tbody tr {
        transition: background 0.2s;
    }
    .tabela-processos tbody tr:hover {
        background: var(--cinza-100);
    }
    
    /* Linha com ação pendente (destaque) */
    .tabela-processos tbody tr.pendente-acao {
        background: #fffbeb;
        border-left: 3px solid var(--amarelo);
    }
    .tabela-processos tbody tr.pendente-acao:hover {
        background: #fef3c7;
    }
    
    /* Badge de ação pendente */
    .acao-pendente-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 10px;
        font-weight: 600;
        background: var(--amarelo);
        color: white;
        margin-left: 8px;
    }
    .acao-pendente-badge.devolvido {
        background: #f59e0b;
    }
    .acao-pendente-badge.pendente {
        background: #ef4444;
    }
    
    /* Protocolo */
    .protocolo-codigo {
        font-family: monospace;
        background: var(--cinza-100);
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
    }
    
    /* Badge de status */
    .badge-status {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
    }
    .badge-status::before {
        content: "●";
        font-size: 7px;
    }
    .badge-novo { background: #eff6ff; color: #2563eb; }
    .badge-em_analise { background: #fffbeb; color: #d97706; }
    .badge-pendente { background: #fef3c7; color: #92400e; }
    .badge-finalizado { background: #f0fdf4; color: #059669; }
    .badge-desativado { background: #f1f5f9; color: #64748b; }
    
    /* Botão ver */
    .btn-ver {
        padding: 5px 14px;
        background: transparent;
        border: 1.5px solid var(--azul-claro);
        color: var(--azul-claro);
        border-radius: 8px;
        font-size: 12px;
        transition: all 0.2s;
        display: inline-block;
        text-decoration: none;
    }
    .btn-ver:hover {
        background: var(--azul-claro);
        color: white;
        text-decoration: none;
    }
    
    /* Card mobile */
    .processo-card-mobile {
        background: var(--branco);
        border-radius: 12px;
        border: 1px solid var(--cinza-200);
        padding: 16px;
        margin-bottom: 12px;
    }
    .processo-card-mobile.pendente-acao {
        background: #fffbeb;
        border-left: 3px solid var(--amarelo);
    }
    .processo-card-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 12px;
        flex-wrap: wrap;
        gap: 8px;
    }
    .processo-card-body {
        margin-bottom: 12px;
    }
    .processo-card-servico {
        font-weight: 600;
        font-size: 14px;
        margin-bottom: 4px;
    }
    .processo-card-solicitante {
        font-size: 12px;
        color: var(--cinza-500);
        margin-bottom: 8px;
    }
    .processo-card-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 10px;
        padding-top: 10px;
        border-top: 1px solid var(--cinza-200);
    }
    
    /* Responsividade */
    @media (max-width: 768px) {
        .nav-tabs-custom .nav-link {
            padding: 8px 16px;
            font-size: 12px;
        }
        .sub-tabs .nav-link {
            padding: 4px 12px;
            font-size: 11px;
        }
    }
</style>

{{-- FILTROS --}}
<div class="filtros-card">
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <h5 class="mb-0" style="font-size: 14px; font-weight: 600; color: var(--azul-escuro);">
            🔍 Filtros de busca
        </h5>
        @if(request()->anyFilled(['protocolo','remetente','tipo_documento_id','status','data_inicio','data_fim']))
            <a href="{{ route('documentos.index') }}?tab={{ request('tab', 'meus') }}&subtab={{ request('subtab', 'abri') }}" 
               class="btn-outline-sced" style="padding: 5px 14px; font-size: 12px;">
                ✕ Limpar filtros
            </a>
        @endif
    </div>

    <form method="GET" action="{{ route('documentos.index') }}" id="formFiltros">
        <input type="hidden" name="tab" id="tabInput" value="{{ request('tab', 'meus') }}">
        <input type="hidden" name="subtab" id="subtabInput" value="{{ request('subtab', 'abri') }}">
        
        <div class="row g-2">
            <div class="col-12 col-sm-6 col-md-3 col-lg-2">
                <label class="form-label-sced">Protocolo</label>
                <input type="text" name="protocolo" class="form-input-sced"
                       placeholder="Ex: 2026-000001" value="{{ request('protocolo') }}">
            </div>

            <div class="col-12 col-sm-6 col-md-3 col-lg-3">
                <label class="form-label-sced">Solicitante</label>
                <input type="text" name="remetente" class="form-input-sced"
                       placeholder="Nome do solicitante" value="{{ request('remetente') }}">
            </div>

            <div class="col-12 col-sm-6 col-md-3 col-lg-2">
                <label class="form-label-sced">Serviço</label>
                <select name="tipo_documento_id" class="form-input-sced">
                    <option value="">Todos</option>
                    @foreach($tipos as $tipo)
                        <option value="{{ $tipo->id }}" {{ request('tipo_documento_id') == $tipo->id ? 'selected' : '' }}>
                            {{ $tipo->nome }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-12 col-sm-6 col-md-3 col-lg-2">
                <label class="form-label-sced">Status</label>
                <select name="status" class="form-input-sced">
                    <option value="">Todos</option>
                    <option value="novo" {{ request('status') == 'novo' ? 'selected' : '' }}>🆕 Novo</option>
                    <option value="em_analise" {{ request('status') == 'em_analise' ? 'selected' : '' }}>🔍 Em Análise</option>
                    <option value="pendente" {{ request('status') == 'pendente' ? 'selected' : '' }}>⏳ Pendente</option>
                    <option value="finalizado" {{ request('status') == 'finalizado' ? 'selected' : '' }}>✅ Finalizado</option>
                    <option value="desativado" {{ request('status') == 'desativado' ? 'selected' : '' }}>🚫 Desativado</option>
                </select>
            </div>

            <div class="col-6 col-sm-3 col-md-2 col-lg-1">
                <label class="form-label-sced">Data Início</label>
                <input type="date" name="data_inicio" class="form-input-sced" value="{{ request('data_inicio') }}">
            </div>

            <div class="col-6 col-sm-3 col-md-2 col-lg-1">
                <label class="form-label-sced">Data Fim</label>
                <input type="date" name="data_fim" class="form-input-sced" value="{{ request('data_fim') }}">
            </div>

            <div class="col-12 col-sm-6 col-md-3 col-lg-1">
                <label class="form-label-sced d-none d-lg-block">&nbsp;</label>
                <button type="submit" class="btn-primary-sced w-100" style="justify-content: center; padding: 10px;">
                    Buscar
                </button>
            </div>
        </div>
    </form>
</div>

{{-- ABAS PRINCIPAIS --}}
<ul class="nav nav-tabs-custom" id="processTabs" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link {{ request('tab', 'meus') == 'meus' ? 'active' : '' }}" 
                onclick="mudarAba('meus')" type="button">
            📋 Meus Processos
            <span class="badge-count {{ ($acoesPendentesCount ?? 0) > 0 ? 'warning' : '' }}">{{ $meusProcessosTotal ?? 0 }}</span>
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link {{ request('tab') == 'setor' ? 'active' : '' }}" 
                onclick="mudarAba('setor')" type="button">
            🏢 Processos do Meu Setor
            <span class="badge-count {{ ($setorProcessosNovos ?? 0) > 0 ? 'urgent' : '' }}">{{ $setorProcessosTotal ?? 0 }}</span>
        </button>
    </li>
</ul>

{{-- SUB-ABAS (apenas para Meus Processos) --}}
@if(request('tab', 'meus') == 'meus')
<ul class="nav sub-tabs mb-3" id="subTabs">
    <li class="nav-item">
        <button class="nav-link {{ request('subtab', 'abri') == 'abri' ? 'active' : '' }}" 
                onclick="mudarSubAba('abri')" type="button">
            📝 Processos que abri
            <span class="badge-count {{ ($processosAguardandoAcaoCount ?? 0) > 0 ? 'warning' : '' }}">{{ $meusProcessosAbertosCount ?? 0 }}</span>
        </button>
    </li>
    <li class="nav-item">
        <button class="nav-link {{ request('subtab') == 'atribuidos' ? 'active' : '' }}" 
                onclick="mudarSubAba('atribuidos')" type="button">
            👤 Processos atribuídos a mim
            <span class="badge-count {{ ($meusProcessosAtribuidosPendentes ?? 0) > 0 ? 'warning' : '' }}">{{ $meusProcessosAtribuidosCount ?? 0 }}</span>
        </button>
    </li>
</ul>
@endif

{{-- TABELA DESKTOP --}}
<div class="card-sced d-none d-md-block">
    <div class="table-responsive">
        <table class="tabela-processos">
            <thead>
                <tr>
                    <th>Protocolo</th>
                    <th>Serviço</th>
                    <th>Solicitante</th>
                    <th class="d-none d-lg-table-cell">Setor Destino</th>
                    <th class="d-none d-md-table-cell">Abertura</th>
                    <th class="text-center">Anexos</th>
                    <th>Status</th>
                    <th class="text-center">Ação</th>
                </tr>
            </thead>
            <tbody>
                @forelse($processosLista as $doc)
                @php
                    $precisaAcao = false;
                    $motivoAcao = '';
                    
                    // Verifica se o processo precisa de ação do usuário
                    if ($doc->status == 'pendente' && $doc->usuario_registro_id == auth()->id()) {
                        $precisaAcao = true;
                        $motivoAcao = 'Aguardando sua correção';
                    } elseif ($doc->status == 'pendente' && $doc->atribuido_a_id == auth()->id()) {
                        $precisaAcao = true;
                        $motivoAcao = 'Aguardando sua análise';
                    } elseif ($doc->status == 'novo' && $doc->atribuido_a_id == null && $doc->departamento_destino_id == auth()->user()->departamento_id) {
                        $precisaAcao = true;
                        $motivoAcao = 'Novo processo no seu setor';
                    } elseif ($doc->status == 'em_analise' && $doc->atribuido_a_id == auth()->id()) {
                        $precisaAcao = true;
                        $motivoAcao = 'Em análise - ação necessária';
                    }
                @endphp
                <tr class="{{ $precisaAcao ? 'pendente-acao' : '' }}">
                    <td>
                        <span class="protocolo-codigo">{{ $doc->numero_protocolo }}</span>
                        @if($precisaAcao)
                            <span class="acao-pendente-badge {{ $doc->status == 'pendente' ? 'devolvido' : 'pendente' }}" title="{{ $motivoAcao }}">
                                ⚠️ {{ $motivoAcao }}
                            </span>
                        @endif
                    </td>
                    <td class="fw-semibold">{{ $doc->tipoDocumento->nome ?? '-' }}</td>
                    <td>{{ $doc->remetente }}</td>
                    <td class="d-none d-lg-table-cell text-muted">{{ $doc->setor_destino }}</td>
                    <td class="d-none d-md-table-cell text-muted" style="white-space: nowrap;">
                        {{ $doc->created_at->format('d/m/Y H:i') }}
                    </td>
                    <td class="text-center">
                        @php $nAnexos = $doc->anexos_count ?? 0; @endphp
                        @if($nAnexos > 0)
                            <span class="badge bg-light text-dark">📎 {{ $nAnexos }}</span>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                    <td>
                        <span class="badge-status badge-{{ $doc->status }}">
                            {{ $doc->label_status }}
                        </span>
                    </td>
                    <td class="text-center">
                        <a href="{{ route('documentos.show', $doc) }}" class="btn-ver">
                            Ver →
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-5">
                        <div class="text-muted">
                            <div class="fs-1 mb-2">📭</div>
                            <p>Nenhum processo encontrado</p>
                            <a href="{{ route('documentos.create') }}" class="btn-primary-sced" style="display: inline-flex;">
                                Abrir o primeiro processo →
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($processosLista->hasPages())
    <div class="card-footer-sced py-3">
        {{ $processosLista->withQueryString()->links() }}
    </div>
    @endif
</div>

{{-- CARDS MOBILE --}}
<div class="d-md-none">
    @forelse($processosLista as $doc)
    @php
        $precisaAcao = false;
        $motivoAcao = '';
        
        if ($doc->status == 'pendente' && $doc->usuario_registro_id == auth()->id()) {
            $precisaAcao = true;
            $motivoAcao = 'Aguardando sua correção';
        } elseif ($doc->status == 'pendente' && $doc->atribuido_a_id == auth()->id()) {
            $precisaAcao = true;
            $motivoAcao = 'Aguardando sua análise';
        } elseif ($doc->status == 'novo' && $doc->atribuido_a_id == null && $doc->departamento_destino_id == auth()->user()->departamento_id) {
            $precisaAcao = true;
            $motivoAcao = 'Novo processo no seu setor';
        } elseif ($doc->status == 'em_analise' && $doc->atribuido_a_id == auth()->id()) {
            $precisaAcao = true;
            $motivoAcao = 'Em análise - ação necessária';
        }
    @endphp
    <div class="processo-card-mobile {{ $precisaAcao ? 'pendente-acao' : '' }}">
        <div class="processo-card-header">
            <span class="protocolo-codigo">{{ $doc->numero_protocolo }}</span>
            <span class="badge-status badge-{{ $doc->status }}">
                {{ $doc->label_status }}
            </span>
        </div>
        @if($precisaAcao)
            <div style="margin-bottom: 8px;">
                <span class="acao-pendente-badge {{ $doc->status == 'pendente' ? 'devolvido' : 'pendente' }}" style="display: inline-block;">
                    ⚠️ {{ $motivoAcao }}
                </span>
            </div>
        @endif
        <div class="processo-card-body">
            <div class="processo-card-servico">{{ $doc->tipoDocumento->nome ?? '-' }}</div>
            <div class="processo-card-solicitante">👤 {{ $doc->remetente }}</div>
            <div class="small text-muted">🏢 {{ $doc->setor_destino }}</div>
        </div>
        <div class="processo-card-footer">
            <div class="small text-muted">
                📅 {{ $doc->created_at->format('d/m/Y H:i') }}
                @if(($doc->anexos_count ?? 0) > 0)
                    <span class="ms-2">📎 {{ $doc->anexos_count }}</span>
                @endif
            </div>
            <a href="{{ route('documentos.show', $doc) }}" class="btn-outline-sced" style="padding: 5px 14px; font-size: 12px;">
                Ver →
            </a>
        </div>
    </div>
    @empty
    <div class="card-sced text-center py-5">
        <div class="text-muted">
            <div class="fs-1 mb-2">📭</div>
            <p>Nenhum processo encontrado</p>
            <a href="{{ route('documentos.create') }}" class="btn-primary-sced">
                Abrir o primeiro processo →
            </a>
        </div>
    </div>
    @endforelse

    @if($processosLista->hasPages())
    <div class="mt-3">
        {{ $processosLista->withQueryString()->links() }}
    </div>
    @endif
</div>

@endsection

@push('scripts')
<script>
function mudarAba(tab) {
    document.getElementById('tabInput').value = tab;
    document.getElementById('subtabInput').value = 'abri';
    document.getElementById('formFiltros').submit();
}

function mudarSubAba(subtab) {
    document.getElementById('subtabInput').value = subtab;
    document.getElementById('formFiltros').submit();
}

// Submete o formulário ao mudar filtros
document.querySelectorAll('#formFiltros input, #formFiltros select').forEach(el => {
    el.addEventListener('change', () => {
        document.getElementById('formFiltros').submit();
    });
});
</script>
@endpush