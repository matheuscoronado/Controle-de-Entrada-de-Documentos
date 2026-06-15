{{-- ============================================================
     resources/views/processos/index.blade.php
     LISTAGEM DE PROCESSOS - CONTADORES CORRIGIDOS
     ============================================================ --}}
@extends('layouts.app')
@section('title', 'Processos')
@section('subtitle', 'Gerencie todos os processos do sistema')

@section('topbar-actions')
    <a href="{{ route('documentos.create') }}" class="btn-primary-sced">
        ➕ Novo Processo
    </a>
@endsection

@section('content')

<style>
    .tabs-container {
        background: var(--branco);
        border-radius: 16px;
        border: 1px solid var(--cinza-200);
        overflow: hidden;
        margin-bottom: 24px;
    }
    .tabs-nav {
        display: flex;
        border-bottom: 1px solid var(--cinza-200);
        background: var(--cinza-100);
    }
    .tab-btn {
        padding: 12px 20px;
        background: transparent;
        border: none;
        font-size: 13px;
        font-weight: 600;
        color: var(--cinza-600);
        cursor: pointer;
        transition: all 0.2s;
        position: relative;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .tab-btn:hover {
        background: rgba(37,99,235,0.05);
        color: var(--azul-claro);
    }
    .tab-btn.active {
        color: var(--azul-claro);
        background: var(--branco);
    }
    .tab-btn.active::after {
        content: '';
        position: absolute;
        bottom: -1px;
        left: 0;
        right: 0;
        height: 2px;
        background: var(--azul-claro);
    }
    .tab-badge {
        background: var(--cinza-200);
        color: var(--cinza-600);
        font-size: 10px;
        font-weight: 700;
        padding: 2px 8px;
        border-radius: 20px;
    }
    .tab-btn.active .tab-badge {
        background: var(--azul-claro);
        color: white;
    }
    .tab-badge-warning {
        background: var(--amarelo);
        color: white;
    }
    .tab-content {
        padding: 20px;
        display: none;
    }
    .tab-content.active {
        display: block;
    }
    
    .sub-tabs {
        display: flex;
        gap: 8px;
        margin-bottom: 20px;
        border-bottom: 1px solid var(--cinza-200);
        padding-bottom: 10px;
    }
    .sub-tab-btn {
        padding: 6px 14px;
        background: transparent;
        border: none;
        font-size: 12px;
        font-weight: 500;
        color: var(--cinza-600);
        cursor: pointer;
        border-radius: 20px;
        transition: all 0.2s;
    }
    .sub-tab-btn:hover {
        background: var(--cinza-100);
    }
    .sub-tab-btn.active {
        background: var(--azul-claro);
        color: white;
    }
    
    .processo-item {
        background: var(--branco);
        border-radius: 12px;
        border: 1px solid var(--cinza-200);
        padding: 16px;
        margin-bottom: 12px;
        transition: all 0.2s;
    }
    .processo-item:hover {
        box-shadow: var(--sombra-hover);
        transform: translateY(-1px);
    }
    .processo-item.aguardando-acao {
        background: #fffbeb;
        border-left: 3px solid var(--amarelo);
    }
    .processo-header-card {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        flex-wrap: wrap;
        gap: 10px;
        margin-bottom: 12px;
    }
    .processo-protocolo {
        font-family: monospace;
        font-size: 13px;
        font-weight: 700;
        color: var(--azul-claro);
        background: rgba(37,99,235,0.1);
        padding: 4px 10px;
        border-radius: 20px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .processo-status {
        font-size: 11px;
        font-weight: 600;
        padding: 4px 12px;
        border-radius: 20px;
    }
    .processo-body-card {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        margin-bottom: 12px;
    }
    .processo-info {
        flex: 1;
        min-width: 150px;
    }
    .processo-info-label {
        font-size: 10px;
        color: var(--cinza-400);
        text-transform: uppercase;
    }
    .processo-info-value {
        font-size: 13px;
        font-weight: 500;
        color: var(--cinza-800);
    }
    .processo-footer-card {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        padding-top: 12px;
        border-top: 1px solid var(--cinza-200);
    }
    .acao-pendente-badge {
        background: var(--amarelo);
        color: white;
        font-size: 10px;
        font-weight: 700;
        padding: 2px 8px;
        border-radius: 20px;
        margin-left: 8px;
    }
    .pagination-container {
        margin-top: 20px;
    }
</style>

<div class="tabs-container">
    <div class="tabs-nav">
        <button class="tab-btn {{ isset($tab) && $tab == 'meus' ? 'active' : '' }}" onclick="switchTab('meus')">
            📋 Meus Processos
            <span class="tab-badge {{ isset($acoesPendentesCount) && $acoesPendentesCount > 0 ? 'tab-badge-warning' : '' }}">
                {{ $acoesPendentesCount ?? 0 }}
            </span>
        </button>
        <button class="tab-btn {{ isset($tab) && $tab == 'setor' ? 'active' : '' }}" onclick="switchTab('setor')">
            🏢 Processos do Meu Setor
            <span class="tab-badge {{ isset($setorProcessosNovos) && $setorProcessosNovos > 0 ? 'tab-badge-warning' : '' }}">
                {{ $setorProcessosNovos ?? 0 }}
            </span>
        </button>
    </div>

    {{-- TAB: MEUS PROCESSOS --}}
    <div id="tab-meus" class="tab-content {{ (isset($tab) && $tab == 'meus') || !isset($tab) ? 'active' : '' }}">
        <div class="sub-tabs">
            <button class="sub-tab-btn {{ (isset($subtab) && $subtab == 'abri') || !isset($subtab) ? 'active' : '' }}" onclick="switchSubTab('abri')">
                📤 Processos que abri
                <span class="tab-badge {{ isset($processosAguardandoAcaoCount) && $processosAguardandoAcaoCount > 0 ? 'tab-badge-warning' : '' }}">
                    {{ $processosAguardandoAcaoCount ?? 0 }}
                </span>
            </button>
            <button class="sub-tab-btn {{ isset($subtab) && $subtab == 'atribuidos' ? 'active' : '' }}" onclick="switchSubTab('atribuidos')">
                👥 Atribuídos a mim
                <span class="tab-badge {{ isset($meusProcessosAtribuidosPendentes) && $meusProcessosAtribuidosPendentes > 0 ? 'tab-badge-warning' : '' }}">
                    {{ $meusProcessosAtribuidosPendentes ?? 0 }}
                </span>
            </button>
        </div>

        <div id="subtab-abri" style="display: {{ (isset($subtab) && $subtab == 'abri') || !isset($subtab) ? 'block' : 'none' }};">
            @forelse($processos ?? [] as $proc)
                @php
                    $aguardaAcao = ($proc->status == 'pendente' && $proc->usuario_registro_id == auth()->id());
                @endphp
                <div class="processo-item {{ $aguardaAcao ? 'aguardando-acao' : '' }}">
                    <div class="processo-header-card">
                        <div class="processo-protocolo">
                            📄 {{ $proc->numero_protocolo }}
                            @if($aguardaAcao)
                                <span class="acao-pendente-badge">⚠️ Aguarda sua ação</span>
                            @endif
                        </div>
                        <div class="processo-status badge-status badge-{{ $proc->status }}">
                            {{ $proc->label_status }}
                        </div>
                    </div>
                    <div class="processo-body-card">
                        <div class="processo-info">
                            <div class="processo-info-label">Serviço</div>
                            <div class="processo-info-value">{{ $proc->tipoDocumento->nome ?? 'N/A' }}</div>
                        </div>
                        <div class="processo-info">
                            <div class="processo-info-label">Setor Destino</div>
                            <div class="processo-info-value">{{ $proc->setor_destino }}</div>
                        </div>
                        <div class="processo-info">
                            <div class="processo-info-label">Abertura</div>
                            <div class="processo-info-value">{{ $proc->created_at->format('d/m/Y H:i') }}</div>
                        </div>
                    </div>
                    <div class="processo-footer-card">
                        <a href="{{ route('documentos.show', $proc) }}" class="btn-outline-sced">🔍 Visualizar</a>
                    </div>
                </div>
            @empty
                <div class="text-center py-5 text-muted">
                    📭 Nenhum processo encontrado.
                </div>
            @endforelse
            @if(isset($processos) && method_exists($processos, 'links'))
                <div class="pagination-container">
                    {{ $processos->links() }}
                </div>
            @endif
        </div>

        <div id="subtab-atribuidos" style="display: {{ isset($subtab) && $subtab == 'atribuidos' ? 'block' : 'none' }};">
            @forelse($processos ?? [] as $proc)
                @php
                    $aguardaAcao = ($proc->atribuido_a_id == auth()->id() && in_array($proc->status, ['novo', 'em_analise']));
                @endphp
                <div class="processo-item {{ $aguardaAcao ? 'aguardando-acao' : '' }}">
                    <div class="processo-header-card">
                        <div class="processo-protocolo">
                            📄 {{ $proc->numero_protocolo }}
                            @if($aguardaAcao)
                                <span class="acao-pendente-badge">⚠️ Aguarda sua ação</span>
                            @endif
                        </div>
                        <div class="processo-status badge-status badge-{{ $proc->status }}">
                            {{ $proc->label_status }}
                        </div>
                    </div>
                    <div class="processo-body-card">
                        <div class="processo-info">
                            <div class="processo-info-label">Serviço</div>
                            <div class="processo-info-value">{{ $proc->tipoDocumento->nome ?? 'N/A' }}</div>
                        </div>
                        <div class="processo-info">
                            <div class="processo-info-label">Solicitante</div>
                            <div class="processo-info-value">{{ $proc->remetente }}</div>
                        </div>
                        <div class="processo-info">
                            <div class="processo-info-label">Abertura</div>
                            <div class="processo-info-value">{{ $proc->created_at->format('d/m/Y H:i') }}</div>
                        </div>
                    </div>
                    <div class="processo-footer-card">
                        <a href="{{ route('documentos.show', $proc) }}" class="btn-outline-sced">🔍 Visualizar</a>
                    </div>
                </div>
            @empty
                <div class="text-center py-5 text-muted">
                    📭 Nenhum processo atribuído a você.
                </div>
            @endforelse
            @if(isset($processos) && method_exists($processos, 'links'))
                <div class="pagination-container">
                    {{ $processos->links() }}
                </div>
            @endif
        </div>
    </div>

    {{-- TAB: PROCESSOS DO MEU SETOR --}}
    <div id="tab-setor" class="tab-content {{ isset($tab) && $tab == 'setor' ? 'active' : '' }}">
        @forelse($setorProcessos ?? [] as $proc)
            @php
                $aguardaAtribuicao = ($proc->status == 'novo' && is_null($proc->atribuido_a_id));
            @endphp
            <div class="processo-item {{ $aguardaAtribuicao ? 'aguardando-acao' : '' }}">
                <div class="processo-header-card">
                    <div class="processo-protocolo">
                        📄 {{ $proc->numero_protocolo }}
                        @if($aguardaAtribuicao)
                            <span class="acao-pendente-badge">⚠️ Aguarda atribuição</span>
                        @endif
                    </div>
                    <div class="processo-status badge-status badge-{{ $proc->status }}">
                        {{ $proc->label_status }}
                    </div>
                </div>
                <div class="processo-body-card">
                    <div class="processo-info">
                        <div class="processo-info-label">Serviço</div>
                        <div class="processo-info-value">{{ $proc->tipoDocumento->nome ?? 'N/A' }}</div>
                    </div>
                    <div class="processo-info">
                        <div class="processo-info-label">Solicitante</div>
                        <div class="processo-info-value">{{ $proc->remetente }}</div>
                    </div>
                    <div class="processo-info">
                        <div class="processo-info-label">Abertura</div>
                        <div class="processo-info-value">{{ $proc->created_at->format('d/m/Y H:i') }}</div>
                    </div>
                </div>
                <div class="processo-footer-card">
                    <a href="{{ route('documentos.show', $proc) }}" class="btn-outline-sced">🔍 Visualizar</a>
                </div>
            </div>
        @empty
            <div class="text-center py-5 text-muted">
                📭 Nenhum processo para o seu setor.
            </div>
        @endforelse
        @if(isset($setorProcessos) && method_exists($setorProcessos, 'links'))
            <div class="pagination-container">
                {{ $setorProcessos->links() }}
            </div>
        @endif
    </div>
</div>

<script>
function switchTab(tab) {
    // Esconder todos os tabs
    document.querySelectorAll('.tab-content').forEach(el => el.classList.remove('active'));
    const targetTab = document.getElementById('tab-' + tab);
    if (targetTab) targetTab.classList.add('active');
    
    // Atualizar botões ativos
    document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
    if (event && event.target) event.target.classList.add('active');
    
    // Atualizar URL com o tab selecionado
    const url = new URL(window.location.href);
    url.searchParams.set('tab', tab);
    window.location.href = url;
}

function switchSubTab(subtab) {
    // Esconder todas as sub-tabs
    const subtabAbri = document.getElementById('subtab-abri');
    const subtabAtribuidos = document.getElementById('subtab-atribuidos');
    
    if (subtabAbri) subtabAbri.style.display = 'none';
    if (subtabAtribuidos) subtabAtribuidos.style.display = 'none';
    
    const targetSubtab = document.getElementById('subtab-' + subtab);
    if (targetSubtab) targetSubtab.style.display = 'block';
    
    // Atualizar botões ativos
    document.querySelectorAll('.sub-tab-btn').forEach(btn => btn.classList.remove('active'));
    if (event && event.target) event.target.classList.add('active');
    
    // Atualizar URL com o subtab selecionado
    const url = new URL(window.location.href);
    url.searchParams.set('subtab', subtab);
    window.location.href = url;
}
</script>
@endsection