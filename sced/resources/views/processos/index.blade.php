{{-- ============================================================
     resources/views/processos/index.blade.php
     Listagem de Processos — Reformulação Visual Fase 1
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

{{-- ── FILTROS ───────────────────────────────────────────── --}}
<div class="idx-filtros">
    <form method="GET" action="{{ route('documentos.index') }}" id="formFiltros">
        <div class="idx-filtros-header">
            <span class="idx-filtros-titulo">Filtrar processos</span>
            @if(request()->anyFilled(['protocolo','remetente','tipo_documento_id','status','data_inicio','data_fim']))
                <a href="{{ route('documentos.index') }}" class="idx-limpar-btn">✕ Limpar filtros</a>
            @endif
        </div>
        <div class="row g-2 align-items-end">
            <div class="col-6 col-md-2">
                <label class="idx-label">Protocolo</label>
                <input type="text" name="protocolo" class="form-input-sced"
                       placeholder="2026-000001" value="{{ request('protocolo') }}">
            </div>
            <div class="col-6 col-md-3">
                <label class="idx-label">Solicitante</label>
                <input type="text" name="remetente" class="form-input-sced"
                       placeholder="Nome do solicitante" value="{{ request('remetente') }}">
            </div>
            <div class="col-6 col-md-2">
                <label class="idx-label">Serviço</label>
                <select name="tipo_documento_id" class="form-input-sced">
                    <option value="">Todos os serviços</option>
                    @foreach($tipos as $tipo)
                        <option value="{{ $tipo->id }}" {{ request('tipo_documento_id') == $tipo->id ? 'selected' : '' }}>
                            {{ $tipo->nome }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-6 col-md-2">
                <label class="idx-label">Status</label>
                <select name="status" class="form-input-sced">
                    <option value="">Todos</option>
                    <option value="novo"       {{ request('status') == 'novo'       ? 'selected':'' }}>Novo</option>
                    <option value="em_analise" {{ request('status') == 'em_analise' ? 'selected':'' }}>Em Análise</option>
                    <option value="pendente"   {{ request('status') == 'pendente'   ? 'selected':'' }}>Pendente</option>
                    <option value="finalizado" {{ request('status') == 'finalizado' ? 'selected':'' }}>Finalizado</option>
                    <option value="desativado" {{ request('status') == 'desativado' ? 'selected':'' }}>Desativado</option>
                </select>
            </div>
            <div class="col-6 col-md-1">
                <label class="idx-label">De</label>
                <input type="date" name="data_inicio" class="form-input-sced" value="{{ request('data_inicio') }}">
            </div>
            <div class="col-6 col-md-1">
                <label class="idx-label">Até</label>
                <input type="date" name="data_fim" class="form-input-sced" value="{{ request('data_fim') }}">
            </div>
            <div class="col-12 col-md-1">
                <button type="submit" class="btn-primary-sced" style="width:100%;justify-content:center;">
                    Buscar
                </button>
            </div>
        </div>
    </form>
</div>

{{-- ── TABELA DESKTOP ────────────────────────────────────── --}}
<div class="card-sced idx-table-card">

    {{-- Cabeçalho da tabela com total --}}
    <div class="idx-table-header">
        <span class="idx-table-count">
            {{ $documentos->total() }} processo(s) encontrado(s)
        </span>
        @if(request()->anyFilled(['protocolo','remetente','tipo_documento_id','status','data_inicio','data_fim']))
            <span class="idx-table-filtered">Filtros ativos</span>
        @endif
    </div>

    {{-- Tabela (visível em telas md+) --}}
    <div class="idx-tabela-wrap">
        <table class="tabela-sced idx-tabela">
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
                @forelse($documentos as $doc)
                @php
                    $statusLabel = [
                        'novo'       => 'Novo',
                        'em_analise' => 'Em Análise',
                        'pendente'   => 'Pendente',
                        'finalizado' => 'Finalizado',
                        'desativado' => 'Desativado',
                    ];
                    $nAnexos = $doc->anexos_count ?? 0;
                @endphp
                <tr class="idx-tr">
                    <td>
                        <span class="protocolo-codigo">{{ $doc->numero_protocolo }}</span>
                    </td>
                    <td class="idx-td-servico">{{ $doc->tipoDocumento->nome }}</td>
                    <td class="idx-td-text">{{ $doc->remetente }}</td>
                    <td class="idx-td-muted d-none d-lg-table-cell">{{ $doc->setor_destino }}</td>
                    <td class="idx-td-muted d-none d-md-table-cell" style="white-space:nowrap;">
                        {{ \Carbon\Carbon::parse($doc->data_recebimento)->format('d/m/Y') }}
                    </td>
                    <td class="text-center">
                        @if($nAnexos > 0)
                            <span class="idx-anexo-badge">📎 {{ $nAnexos }}</span>
                        @else
                            <span class="idx-td-muted">—</span>
                        @endif
                    </td>
                    <td>
                        <span class="badge-status badge-{{ $doc->status }}">
                            {{ $statusLabel[$doc->status] ?? ucfirst($doc->status) }}
                        </span>
                    </td>
                    <td class="text-center">
                        <a href="{{ route('documentos.show', $doc) }}" class="idx-btn-ver">
                            Ver →
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="idx-empty-cell">
                        <div class="idx-empty">
                            <div class="idx-empty-icon">📂</div>
                            <div class="idx-empty-text">Nenhum processo encontrado</div>
                            @if(request()->anyFilled(['protocolo','remetente','tipo_documento_id','status','data_inicio','data_fim']))
                                <a href="{{ route('documentos.index') }}" class="idx-empty-link">Limpar filtros</a>
                            @else
                                <a href="{{ route('documentos.create') }}" class="idx-empty-link">
                                    Abrir o primeiro processo →
                                </a>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Cards mobile (visível apenas em telas xs/sm) --}}
    <div class="idx-cards-mobile">
        @forelse($documentos as $doc)
        @php
            $statusLabel = [
                'novo'       => 'Novo',
                'em_analise' => 'Em Análise',
                'pendente'   => 'Pendente',
                'finalizado' => 'Finalizado',
                'desativado' => 'Desativado',
            ];
            $nAnexos = $doc->anexos_count ?? 0;
        @endphp
        <div class="idx-card-mobile">
            <div class="idx-card-mobile-top">
                <span class="protocolo-codigo">{{ $doc->numero_protocolo }}</span>
                <span class="badge-status badge-{{ $doc->status }}">
                    {{ $statusLabel[$doc->status] ?? ucfirst($doc->status) }}
                </span>
            </div>
            <div class="idx-card-mobile-servico">{{ $doc->tipoDocumento->nome }}</div>
            <div class="idx-card-mobile-meta">
                <span>{{ $doc->remetente }}</span>
                <span>{{ \Carbon\Carbon::parse($doc->data_recebimento)->format('d/m/Y') }}</span>
                @if($nAnexos > 0)<span>📎 {{ $nAnexos }}</span>@endif
            </div>
            <a href="{{ route('documentos.show', $doc) }}" class="idx-card-mobile-btn">
                Ver detalhes →
            </a>
        </div>
        @empty
        <div class="idx-empty" style="padding:40px 16px;">
            <div class="idx-empty-icon">📂</div>
            <div class="idx-empty-text">Nenhum processo encontrado</div>
            <a href="{{ route('documentos.create') }}" class="idx-empty-link">
                Abrir o primeiro processo →
            </a>
        </div>
        @endforelse
    </div>

    {{-- Paginação --}}
    @if($documentos->hasPages())
    <div class="idx-pagination">
        {{ $documentos->withQueryString()->links() }}
    </div>
    @endif
</div>
@endsection