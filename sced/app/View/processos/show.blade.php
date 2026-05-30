{{-- ============================================================
     resources/views/processos/show.blade.php
     Detalhe do Processo — Parte 2
     • Seção de Anexos com status de validação por arquivo
     • Novo label "Processo" em vez de "Documento"
     ============================================================ --}}
@extends('layouts.app')
@section('title', 'Processo ' . $documento->numero_protocolo)
@section('subtitle', $documento->tipoDocumento->nome)

@section('topbar-actions')
    <a href="{{ route('documentos.index') }}" class="btn-secondary-sced">← Voltar</a>
@endsection

@section('content')
<div class="row g-3">

    {{-- ── COLUNA PRINCIPAL ─────────────────────────────────── --}}
    <div class="col-12 col-lg-8">

        {{-- Cabeçalho com protocolo e status --}}
        <div class="card-sced card-body-sced mb-3">
            <div style="display:flex;align-items:flex-start;justify-content:space-between;flex-wrap:wrap;gap:12px;">
                <div>
                    <div style="font-size:11px;color:var(--cinza-400);text-transform:uppercase;letter-spacing:1px;margin-bottom:4px;">
                        Número de Protocolo
                    </div>
                    <div class="protocolo-codigo" style="font-size:18px;padding:8px 14px;">
                        {{ $documento->numero_protocolo }}
                    </div>
                </div>
                <span class="badge-status badge-{{ $documento->status }}" style="font-size:14px;padding:8px 16px;">
                    {{ ['recebido'=>'Recebido','em_analise'=>'Em Análise','encaminhado'=>'Encaminhado','finalizado'=>'Finalizado'][$documento->status] }}
                </span>
            </div>
        </div>

        {{-- Dados do processo --}}
        <div class="card-sced card-body-sced mb-3">
            <strong style="font-size:15px;color:var(--azul-escuro);display:block;margin-bottom:20px;">
                📋 Informações do Processo
            </strong>
            <div class="row g-3">
                <div class="col-6">
                    <div class="dado-label">Serviço</div>
                    <div class="dado-valor">{{ $documento->tipoDocumento->nome }}</div>
                </div>
                <div class="col-6">
                    <div class="dado-label">Data de Abertura</div>
                    <div class="dado-valor">{{ \Carbon\Carbon::parse($documento->data_recebimento)->format('d/m/Y') }}</div>
                </div>
                <div class="col-6">
                    <div class="dado-label">Solicitante</div>
                    <div class="dado-valor">{{ $documento->remetente }}</div>
                </div>
                <div class="col-6">
                    <div class="dado-label">Setor de Destino</div>
                    <div class="dado-valor">{{ $documento->setor_destino }}</div>
                </div>
                @if($documento->descricao)
                <div class="col-12">
                    <div class="dado-label">Descrição</div>
                    <div class="dado-valor" style="color:var(--cinza-600);line-height:1.6;">{{ $documento->descricao }}</div>
                </div>
                @endif
                <div class="col-12" style="padding-top:8px;border-top:1px solid var(--cinza-200);">
                    <div style="font-size:11px;color:var(--cinza-400);">
                        Aberto por <strong>{{ $documento->usuarioRegistro->nome }}</strong>
                        em {{ $documento->created_at->format('d/m/Y \à\s H:i') }}
                    </div>
                </div>
            </div>
        </div>

        {{-- Anexos com status de validação --}}
        @if($documento->anexos->count() > 0)
        <div class="card-sced card-body-sced mb-3">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;">
                <strong style="font-size:15px;color:var(--azul-escuro);">📎 Documentos Anexos</strong>
                <span style="font-size:12px;color:var(--cinza-400);">
                    {{ $documento->anexos->count() }} arquivo(s)
                    · {{ $documento->anexos->where('status_validacao','aprovado')->count() }} aprovado(s)
                    · {{ $documento->anexos->where('status_validacao','pendente')->count() }} pendente(s)
                </span>
            </div>

            @foreach($documento->anexos as $anexo)
            <div class="anexo-item">
                <div style="display:flex;align-items:center;gap:10px;flex:1;min-width:0;">
                    <span style="font-size:22px;flex-shrink:0;">
                        {{ str_contains($anexo->tipo_mime,'image') ? '🖼️' : (str_ends_with($anexo->nome_arquivo,'.pdf') ? '📕' : '📄') }}
                    </span>
                    <div style="min-width:0;">
                        <div style="font-size:13px;font-weight:600;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                            {{ $anexo->nome_arquivo }}
                        </div>
                        <div style="font-size:11px;color:var(--cinza-400);margin-top:2px;">
                            {{ $anexo->label_tipo_anexo }}
                            · {{ number_format($anexo->tamanho_bytes / 1024, 1) }} KB
                        </div>
                        @if($anexo->observacao_validacao)
                        <div style="font-size:11px;color:var(--cinza-600);margin-top:3px;font-style:italic;">
                            "{{ $anexo->observacao_validacao }}"
                        </div>
                        @endif
                    </div>
                </div>
                <div style="display:flex;align-items:center;gap:10px;flex-shrink:0;">
                    @php
                        $validBg    = ['pendente'=>'#fef3c7','aprovado'=>'#d1fae5','rejeitado'=>'#fef2f2'];
                        $validColor = ['pendente'=>'#92400e','aprovado'=>'#065f46','rejeitado'=>'#991b1b'];
                        $validLabel = ['pendente'=>'⏳ Pendente','aprovado'=>'✅ Aprovado','rejeitado'=>'❌ Rejeitado'];
                    @endphp
                    <span style="font-size:11px;font-weight:700;padding:3px 10px;border-radius:10px;
                                 background:{{ $validBg[$anexo->status_validacao] }};
                                 color:{{ $validColor[$anexo->status_validacao] }};">
                        {{ $validLabel[$anexo->status_validacao] }}
                    </span>
                    <a href="{{ Storage::url($anexo->caminho_arquivo) }}"
                       target="_blank" class="btn-outline-sced" style="font-size:12px;padding:5px 10px;">
                        ⬇ Baixar
                    </a>
                </div>
            </div>
            @endforeach
        </div>
        @endif

        {{-- Histórico de movimentações --}}
        <div class="card-sced card-body-sced mb-3">
            <strong style="font-size:15px;color:var(--azul-escuro);display:block;margin-bottom:20px;">
                🕐 Histórico de Movimentações
            </strong>
            <ul class="timeline">
                @foreach($documento->historicos->sortByDesc('created_at') as $hist)
                <li class="timeline-item">
                    <div class="timeline-dot">
                        {{ ['recebido'=>'📥','em_analise'=>'🔍','encaminhado'=>'📤','finalizado'=>'✅'][$hist->status_novo] ?? '•' }}
                    </div>
                    <div class="timeline-content">
                        <div class="timeline-date">
                            {{ $hist->usuario->nome ?? '—' }}
                            · {{ \Carbon\Carbon::parse($hist->created_at)->format('d/m/Y H:i') }}
                        </div>
                        <div class="timeline-text">
                            @if($hist->status_anterior)
                                <span class="badge-status badge-{{ $hist->status_anterior }}" style="font-size:11px;">
                                    {{ ['recebido'=>'Recebido','em_analise'=>'Em Análise','encaminhado'=>'Encaminhado','finalizado'=>'Finalizado'][$hist->status_anterior] }}
                                </span>
                                →
                            @endif
                            <span class="badge-status badge-{{ $hist->status_novo }}" style="font-size:11px;">
                                {{ ['recebido'=>'Recebido','em_analise'=>'Em Análise','encaminhado'=>'Encaminhado','finalizado'=>'Finalizado'][$hist->status_novo] }}
                            </span>
                        </div>
                        @if($hist->observacoes)
                        <div style="font-size:12px;color:var(--cinza-600);margin-top:5px;">
                            {{ $hist->observacoes }}
                        </div>
                        @endif
                    </div>
                </li>
                @endforeach
            </ul>
        </div>

    </div>

    {{-- ── COLUNA LATERAL ────────────────────────────────────── --}}
    <div class="col-12 col-lg-4">

        {{-- Alterar status --}}
        @if($documento->status !== 'finalizado' || auth()->user()->isAdmin())
        <div class="card-sced card-body-sced mb-3">
            <strong style="font-size:14px;color:var(--azul-escuro);display:block;margin-bottom:16px;">
                🔄 Atualizar Status
            </strong>
            <form method="POST" action="{{ route('documentos.status', $documento) }}">
                @csrf @method('PATCH')
                <div style="margin-bottom:12px;">
                    <label style="font-size:12px;font-weight:600;color:var(--cinza-600);display:block;margin-bottom:6px;">
                        Novo Status
                    </label>
                    <select name="status" class="form-input-sced">
                        <option value="recebido"    {{ $documento->status==='recebido'    ? 'selected':'' }}>📥 Recebido</option>
                        <option value="em_analise"  {{ $documento->status==='em_analise'  ? 'selected':'' }}>🔍 Em Análise</option>
                        <option value="encaminhado" {{ $documento->status==='encaminhado' ? 'selected':'' }}>📤 Encaminhado</option>
                        <option value="finalizado"  {{ $documento->status==='finalizado'  ? 'selected':'' }}>✅ Finalizado</option>
                    </select>
                </div>
                <div style="margin-bottom:12px;">
                    <label style="font-size:12px;font-weight:600;color:var(--cinza-600);display:block;margin-bottom:6px;">
                        Observações
                    </label>
                    <textarea name="observacoes" class="form-input-sced" rows="3"
                              placeholder="Motivo ou detalhes da alteração..."></textarea>
                </div>
                <button type="submit" class="btn-primary-sced" style="width:100%;justify-content:center;">
                    Salvar Status
                </button>
            </form>
        </div>
        @endif

        {{-- Resumo técnico --}}
        <div class="card-sced card-body-sced">
            <strong style="font-size:14px;color:var(--azul-escuro);display:block;margin-bottom:14px;">
                ℹ️ Dados Técnicos
            </strong>
            <div style="display:flex;flex-direction:column;gap:10px;">
                @foreach([
                    ['Serviço',      $documento->tipoDocumento->nome],
                    ['Setor',        $documento->setor_destino],
                    ['Responsável',  $documento->tipoDocumento->cargo_responsavel ?? '—'],
                    ['SLA',          $documento->tipoDocumento->label_sla],
                    ['Protocolo',    $documento->numero_protocolo],
                ] as [$label,$valor])
                <div>
                    <div style="font-size:10px;font-weight:600;text-transform:uppercase;letter-spacing:.8px;color:var(--cinza-400);margin-bottom:2px;">{{ $label }}</div>
                    <div style="font-size:13px;font-weight:600;color:var(--cinza-800);">{{ $valor }}</div>
                </div>
                @endforeach
            </div>
        </div>

    </div>

</div>
@endsection

@push('styles')
<style>
.dado-label { font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:.6px;color:var(--cinza-400);margin-bottom:4px; }
.dado-valor { font-size:14px;font-weight:500; }
.anexo-item {
    display:flex;align-items:center;justify-content:space-between;
    gap:12px;padding:12px 14px;
    background:var(--cinza-100);border-radius:var(--radius-sm);
    margin-bottom:8px;flex-wrap:wrap;
}
</style>
@endpush
