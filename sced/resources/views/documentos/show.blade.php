{{-- ============================================================
     Arquivo: resources/views/documentos/show.blade.php
     Detalhes do documento + histórico + alterar status
     ============================================================ --}}
@extends('layouts.app')
@section('title', 'Documento ' . $documento->numero_protocolo)
@section('subtitle', $documento->assunto)

@section('topbar-actions')
    <a href="{{ route('documentos.index') }}" class="btn-secondary-sced">
        ← Voltar à lista
    </a>
@endsection

@section('content')

<div class="row g-3">

    {{-- COLUNA PRINCIPAL --}}
    <div class="col-12 col-lg-8">

        {{-- Cabeçalho do documento --}}
        <div class="card-sced card-body-sced" style="margin-bottom:16px;">
            <div style="display:flex; align-items:flex-start; justify-content:space-between; flex-wrap:wrap; gap:12px;">
                <div>
                    <div style="font-size:11px; color:var(--cinza-400); text-transform:uppercase; letter-spacing:1px; margin-bottom:4px;">
                        Número de Protocolo
                    </div>
                    <div class="protocolo-codigo" style="font-size:18px; padding:8px 14px;">
                        {{ $documento->numero_protocolo }}
                    </div>
                </div>
                <span class="badge-status badge-{{ $documento->status }}" style="font-size:14px; padding:8px 16px;">
                    {{ ['recebido'=>'Recebido','em_analise'=>'Em Análise','encaminhado'=>'Encaminhado','finalizado'=>'Finalizado'][$documento->status] }}
                </span>
            </div>
        </div>

        {{-- Dados do documento --}}
        <div class="card-sced card-body-sced" style="margin-bottom:16px;">
            <strong style="font-size:15px; color:var(--azul-escuro); display:block; margin-bottom:20px;">
                📋 Informações do Documento
            </strong>
            <div class="row g-3">
                <div class="col-6">
                    <div style="font-size:11px; color:var(--cinza-400); text-transform:uppercase; letter-spacing:0.6px; margin-bottom:4px;">
                        Tipo
                    </div>
                    <div style="font-size:14px; font-weight:500;">{{ $documento->tipoDocumento->nome }}</div>
                </div>
                <div class="col-6">
                    <div style="font-size:11px; color:var(--cinza-400); text-transform:uppercase; letter-spacing:0.6px; margin-bottom:4px;">
                        Data de Recebimento
                    </div>
                    <div style="font-size:14px; font-weight:500;">
                        {{ \Carbon\Carbon::parse($documento->data_recebimento)->format('d/m/Y') }}
                    </div>
                </div>
                <div class="col-6">
                    <div style="font-size:11px; color:var(--cinza-400); text-transform:uppercase; letter-spacing:0.6px; margin-bottom:4px;">
                        Remetente
                    </div>
                    <div style="font-size:14px; font-weight:500;">{{ $documento->remetente }}</div>
                </div>
                <div class="col-6">
                    <div style="font-size:11px; color:var(--cinza-400); text-transform:uppercase; letter-spacing:0.6px; margin-bottom:4px;">
                        Setor de Destino
                    </div>
                    <div style="font-size:14px; font-weight:500;">{{ $documento->setor_destino }}</div>
                </div>
                <div class="col-12">
                    <div style="font-size:11px; color:var(--cinza-400); text-transform:uppercase; letter-spacing:0.6px; margin-bottom:4px;">
                        Assunto
                    </div>
                    <div style="font-size:14px; font-weight:500;">{{ $documento->assunto }}</div>
                </div>
                @if($documento->descricao)
                <div class="col-12">
                    <div style="font-size:11px; color:var(--cinza-400); text-transform:uppercase; letter-spacing:0.6px; margin-bottom:4px;">
                        Descrição
                    </div>
                    <div style="font-size:14px; color:var(--cinza-600); line-height:1.6;">
                        {{ $documento->descricao }}
                    </div>
                </div>
                @endif
                <div class="col-12" style="padding-top:8px; border-top:1px solid var(--cinza-200);">
                    <div style="font-size:11px; color:var(--cinza-400);">
                        Registrado por <strong>{{ $documento->usuarioRegistro->nome }}</strong>
                        em {{ $documento->created_at->format('d/m/Y \à\s H:i') }}
                    </div>
                </div>
            </div>
        </div>

        {{-- Anexos --}}
        @if($documento->anexos->count() > 0)
        <div class="card-sced card-body-sced" style="margin-bottom:16px;">
            <strong style="font-size:15px; color:var(--azul-escuro); display:block; margin-bottom:16px;">
                📎 Arquivos Anexos
            </strong>
            @foreach($documento->anexos as $anexo)
            <div style="display:flex; align-items:center; justify-content:space-between; padding:12px 16px; background:var(--cinza-100); border-radius:var(--radius-sm); margin-bottom:8px;">
                <div style="display:flex; align-items:center; gap:10px;">
                    <span style="font-size:20px;">📄</span>
                    <div>
                        <div style="font-size:13px; font-weight:500;">{{ $anexo->nome_arquivo }}</div>
                        <div style="font-size:11px; color:var(--cinza-400);">
                            {{ number_format($anexo->tamanho_bytes / 1024, 1) }} KB
                        </div>
                    </div>
                </div>
                <a href="{{ Storage::url($anexo->caminho_arquivo) }}"
                   target="_blank" class="btn-outline-sced" style="font-size:12px;">
                    ⬇ Baixar
                </a>
            </div>
            @endforeach
        </div>
        @endif

        {{-- Histórico de Movimentação --}}
        <div class="card-sced card-body-sced">
            <strong style="font-size:15px; color:var(--azul-escuro); display:block; margin-bottom:20px;">
                🕐 Histórico de Movimentações
            </strong>
            <ul class="timeline">
                @foreach($documento->historicos->sortByDesc('data_hora') as $hist)
                <li class="timeline-item">
                    <div class="timeline-dot">
                        {{ ['recebido'=>'📥','em_analise'=>'🔍','encaminhado'=>'↗️','finalizado'=>'✅'][$hist->status_novo] ?? '📋' }}
                    </div>
                    <div class="timeline-content">
                        <div style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:8px;">
                            <div>
                                @if($hist->status_anterior)
                                    <span class="badge-status badge-{{ $hist->status_anterior }}" style="font-size:11px;">
                                        {{ ['recebido'=>'Recebido','em_analise'=>'Em Análise','encaminhado'=>'Encaminhado','finalizado'=>'Finalizado'][$hist->status_anterior] }}
                                    </span>
                                    <span style="font-size:12px; color:var(--cinza-400); margin:0 4px;">→</span>
                                @endif
                                <span class="badge-status badge-{{ $hist->status_novo }}" style="font-size:11px;">
                                    {{ ['recebido'=>'Recebido','em_analise'=>'Em Análise','encaminhado'=>'Encaminhado','finalizado'=>'Finalizado'][$hist->status_novo] }}
                                </span>
                            </div>
                            <div class="timeline-date">
                                {{ \Carbon\Carbon::parse($hist->data_hora)->format('d/m/Y H:i') }}
                            </div>
                        </div>
                        <div class="timeline-text" style="margin-top:6px;">
                            Por <strong>{{ $hist->usuario->nome }}</strong>
                            @if($hist->observacoes)
                                — {{ $hist->observacoes }}
                            @endif
                        </div>
                    </div>
                </li>
                @endforeach
            </ul>
        </div>

    </div>

    {{-- COLUNA LATERAL — Alterar Status --}}
    <div class="col-12 col-lg-4">
        <div class="card-sced card-body-sced" style="position:sticky; top:80px;">
            <strong style="font-size:15px; color:var(--azul-escuro); display:block; margin-bottom:16px;">
                🔄 Atualizar Status
            </strong>

            @if($documento->status === 'finalizado' && !auth()->user()->isAdmin())
                <div class="alert-sced alert-warning">
                    🔒 Apenas administradores podem alterar documentos finalizados.
                </div>
            @else
                <form method="POST" action="{{ route('documentos.status', $documento) }}">
                    @csrf
                    @method('PATCH')

                    <div class="form-group">
                        <label class="form-label-sced">Novo Status</label>
                        <select name="status" class="form-input-sced" required>
                            <option value="recebido"    {{ $documento->status=='recebido'    ? 'selected' : '' }}>📥 Recebido</option>
                            <option value="em_analise"  {{ $documento->status=='em_analise'  ? 'selected' : '' }}>🔍 Em Análise</option>
                            <option value="encaminhado" {{ $documento->status=='encaminhado' ? 'selected' : '' }}>↗️ Encaminhado</option>
                            <option value="finalizado"  {{ $documento->status=='finalizado'  ? 'selected' : '' }}>✅ Finalizado</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label-sced">Observações</label>
                        <textarea name="observacoes" class="form-input-sced" rows="3"
                                  placeholder="Motivo da alteração (opcional)..."></textarea>
                    </div>

                    <button type="submit" class="btn-primary-sced" style="width:100%;">
                        💾 Salvar Alteração
                    </button>
                </form>
            @endif

            {{-- Informações rápidas --}}
            <div style="margin-top:24px; padding-top:20px; border-top:1px solid var(--cinza-200);">
                <div style="font-size:12px; color:var(--cinza-400); margin-bottom:12px; font-weight:600; text-transform:uppercase; letter-spacing:0.6px;">
                    Resumo
                </div>
                <div style="display:flex; flex-direction:column; gap:10px;">
                    <div style="display:flex; justify-content:space-between; font-size:13px;">
                        <span style="color:var(--cinza-600);">Movimentações</span>
                        <span style="font-weight:600;">{{ $documento->historicos->count() }}</span>
                    </div>
                    <div style="display:flex; justify-content:space-between; font-size:13px;">
                        <span style="color:var(--cinza-600);">Anexos</span>
                        <span style="font-weight:600;">{{ $documento->anexos->count() }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection
