{{-- ============================================================
     Arquivo: resources/views/documentos/index.blade.php
     Listagem de documentos com filtros avançados
     ============================================================ --}}
@extends('layouts.app')
@section('title', 'Documentos')
@section('subtitle', 'Consulta e gerenciamento de documentos')

@section('topbar-actions')
    <a href="{{ route('documentos.create') }}" class="btn-primary-sced">
        ➕ Novo Documento
    </a>
@endsection

@section('content')

{{-- FILTROS --}}
<div class="filtros-card">
    <form method="GET" action="{{ route('documentos.index') }}">
        <div style="display:flex; align-items:center; gap:8px; margin-bottom:16px;">
            <span style="font-size:14px; font-weight:600; color:var(--azul-escuro);">🔍 Filtros de busca</span>
            @if(request()->anyFilled(['protocolo','remetente','tipo_documento_id','status','data_inicio','data_fim']))
                <a href="{{ route('documentos.index') }}" class="btn-secondary-sced" style="padding:4px 10px; font-size:12px;">
                    ✕ Limpar filtros
                </a>
            @endif
        </div>
        <div class="row g-2">
            <div class="col-12 col-md-2">
                <label>Protocolo</label>
                <input type="text" name="protocolo" class="form-input-sced"
                       placeholder="Ex: 2026-000001"
                       value="{{ request('protocolo') }}">
            </div>
            <div class="col-12 col-md-3">
                <label>Remetente</label>
                <input type="text" name="remetente" class="form-input-sced"
                       placeholder="Nome do remetente"
                       value="{{ request('remetente') }}">
            </div>
            <div class="col-12 col-md-2">
                <label>Tipo</label>
                <select name="tipo_documento_id" class="form-input-sced">
                    <option value="">Todos os tipos</option>
                    @foreach($tipos as $tipo)
                        <option value="{{ $tipo->id }}"
                            {{ request('tipo_documento_id') == $tipo->id ? 'selected' : '' }}>
                            {{ $tipo->nome }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 col-md-2">
                <label>Status</label>
                <select name="status" class="form-input-sced">
                    <option value="">Todos</option>
                    <option value="recebido"    {{ request('status') == 'recebido'    ? 'selected' : '' }}>Recebido</option>
                    <option value="em_analise"  {{ request('status') == 'em_analise'  ? 'selected' : '' }}>Em Análise</option>
                    <option value="encaminhado" {{ request('status') == 'encaminhado' ? 'selected' : '' }}>Encaminhado</option>
                    <option value="finalizado"  {{ request('status') == 'finalizado'  ? 'selected' : '' }}>Finalizado</option>
                </select>
            </div>
            <div class="col-6 col-md-1">
                <label>De</label>
                <input type="date" name="data_inicio" class="form-input-sced"
                       value="{{ request('data_inicio') }}">
            </div>
            <div class="col-6 col-md-1">
                <label>Até</label>
                <input type="date" name="data_fim" class="form-input-sced"
                       value="{{ request('data_fim') }}">
            </div>
            <div class="col-12 col-md-1" style="display:flex; align-items:flex-end;">
                <button type="submit" class="btn-primary-sced" style="width:100%;">
                    Filtrar
                </button>
            </div>
        </div>
    </form>
</div>

{{-- TABELA --}}
<div class="card-sced">
    <div style="display:flex; align-items:center; justify-content:space-between; padding:16px 24px; border-bottom:1px solid var(--cinza-200);">
        <span style="font-size:14px; color:var(--cinza-600);">
            {{ $documentos->total() }} documento(s) encontrado(s)
        </span>
    </div>
    <div style="overflow-x:auto;">
        <table class="tabela-sced">
            <thead>
                <tr>
                    <th>Protocolo</th>
                    <th>Tipo</th>
                    <th>Assunto</th>
                    <th>Remetente</th>
                    <th>Destino</th>
                    <th>Recebido em</th>
                    <th>Status</th>
                    <th style="text-align:center;">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse($documentos as $doc)
                <tr>
                    <td><span class="protocolo-codigo">{{ $doc->numero_protocolo }}</span></td>
                    <td>{{ $doc->tipoDocumento->nome }}</td>
                    <td style="max-width:220px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">
                        {{ $doc->assunto }}
                    </td>
                    <td>{{ $doc->remetente }}</td>
                    <td>{{ $doc->setor_destino }}</td>
                    <td style="white-space:nowrap;">
                        {{ \Carbon\Carbon::parse($doc->data_recebimento)->format('d/m/Y') }}
                    </td>
                    <td>
                        <span class="badge-status badge-{{ $doc->status }}">
                            {{ ['recebido'=>'Recebido','em_analise'=>'Em Análise','encaminhado'=>'Encaminhado','finalizado'=>'Finalizado'][$doc->status] }}
                        </span>
                    </td>
                    <td style="text-align:center;">
                        <a href="{{ route('documentos.show', $doc) }}" class="btn-outline-sced">
                            👁 Ver
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align:center; padding:48px; color:var(--cinza-400);">
                        <div style="font-size:32px; margin-bottom:8px;">📭</div>
                        Nenhum documento encontrado com os filtros informados.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Paginação --}}
    @if($documentos->hasPages())
    <div style="padding:16px 24px; border-top:1px solid var(--cinza-200);">
        {{ $documentos->withQueryString()->links() }}
    </div>
    @endif
</div>

@endsection
