{{-- ============================================================
     resources/views/processos/show.blade.php
     Detalhe do Processo — Reformulação Visual Fase 1
     ============================================================ --}}
@extends('layouts.app')
@section('title', 'Processo ' . $documento->numero_protocolo)
@section('subtitle', $documento->tipoDocumento->nome)

@section('topbar-actions')
    <a href="{{ route('documentos.index') }}" class="btn-secondary-sced">← Voltar</a>
    @if(in_array('editar', $acoes))
        <a href="{{ route('documentos.edit', $documento) }}" class="btn-outline-sced">✏️ Editar</a>
    @endif
@endsection

@section('content')

{{-- ── CABEÇALHO DO PROCESSO ────────────────────────────── --}}
<div class="show-header card-sced card-body-sced mb-3">
    <div class="show-header-left">
        <div class="show-header-label">Número de Protocolo</div>
        <div class="show-header-protocolo">
            <span class="protocolo-codigo show-protocolo-codigo">{{ $documento->numero_protocolo }}</span>
        </div>
        <div class="show-header-meta">
            Aberto em {{ $documento->created_at->format('d/m/Y \à\s H:i') }}
            · por <strong>{{ $documento->usuarioRegistro->nome }}</strong>
        </div>
    </div>
    <div class="show-header-right">
        <span class="badge-status badge-{{ $documento->status }} show-badge-status">
            @php
                $statusLabel = [
                    'novo'       => 'Novo',
                    'em_analise' => 'Em Análise',
                    'pendente'   => 'Pendente',
                    'finalizado' => 'Finalizado',
                    'desativado' => 'Desativado',
                ];
            @endphp
            {{ $statusLabel[$documento->status] ?? ucfirst($documento->status) }}
        </span>
        @if($documento->atribuidoA)
        <div class="show-header-responsavel">
            👤 {{ $documento->atribuidoA->nome }}
        </div>
        @endif
    </div>
</div>

<div class="row g-3">

    {{-- ── COLUNA PRINCIPAL ──────────────────────────────── --}}
    <div class="col-12 col-lg-8">

        {{-- Dados do processo --}}
        <div class="card-sced card-body-sced mb-3">
            <div class="show-section-title">📋 Informações do Processo</div>
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
                @if($documento->tipoDocumento->cargo_responsavel)
                <div class="col-6">
                    <div class="dado-label">Cargo Responsável</div>
                    <div class="dado-valor">{{ $documento->tipoDocumento->cargo_responsavel }}</div>
                </div>
                @endif
                @if($documento->descricao)
                <div class="col-12">
                    <div class="dado-label">Descrição</div>
                    <div class="dado-valor" style="color:var(--cinza-600);line-height:1.6;">{{ $documento->descricao }}</div>
                </div>
                @endif
            </div>
        </div>

        {{-- Anexos com status de validação --}}
        @if($documento->anexos->count() > 0)
        <div class="card-sced card-body-sced mb-3">
            <div class="show-section-header">
                <div class="show-section-title mb-0">📎 Documentos Anexos</div>
                <span class="show-section-count">
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

                    @if(in_array('validar_anexo', $acoes))
                    <form method="POST" action="{{ route('documentos.anexo.validar', [$documento, $anexo]) }}"
                          style="display:inline;">
                        @csrf
                        <div style="display:flex;gap:4px;">
                            <button type="submit" name="status_validacao" value="aprovado"
                                    class="show-btn-validar show-btn-aprovar"
                                    title="Aprovar">✓</button>
                            <button type="submit" name="status_validacao" value="rejeitado"
                                    class="show-btn-validar show-btn-rejeitar"
                                    title="Rejeitar">✕</button>
                        </div>
                    </form>
                    @endif

                    @if(in_array('substituir_anexo', $acoes))
                    <form method="POST" action="{{ route('documentos.anexo.substituir', [$documento, $anexo]) }}"
                          enctype="multipart/form-data" style="display:inline;">
                        @csrf
                        <label class="show-btn-substituir" title="Substituir arquivo">
                            📎
                            <input type="file" name="arquivo" style="display:none"
                                   accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                                   onchange="this.form.submit()">
                        </label>
                    </form>
                    @endif

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
            <div class="show-section-title">🕐 Histórico de Movimentações</div>
            <ul class="timeline">
                @php
                    $timelineIcons = [
                        'novo'       => '🆕',
                        'em_analise' => '🔍',
                        'pendente'   => '⏳',
                        'finalizado' => '✅',
                        'desativado' => '🚫',
                    ];
                    $statusLabel = [
                        'novo'       => 'Novo',
                        'em_analise' => 'Em Análise',
                        'pendente'   => 'Pendente',
                        'finalizado' => 'Finalizado',
                        'desativado' => 'Desativado',
                    ];
                @endphp
                @foreach($documento->historicos->sortByDesc('created_at') as $hist)
                <li class="timeline-item">
                    <div class="timeline-dot">
                        {{ $timelineIcons[$hist->status_novo] ?? '●' }}
                    </div>
                    <div class="timeline-content">
                        <div class="timeline-date">
                            {{ $hist->usuario->nome ?? '—' }}
                            · {{ \Carbon\Carbon::parse($hist->created_at)->format('d/m/Y H:i') }}
                        </div>
                        <div class="timeline-text">
                            @if($hist->status_anterior)
                                <span class="badge-status badge-{{ $hist->status_anterior }}" style="font-size:11px;">
                                    {{ $statusLabel[$hist->status_anterior] ?? $hist->status_anterior }}
                                </span>
                                →
                            @endif
                            <span class="badge-status badge-{{ $hist->status_novo }}" style="font-size:11px;">
                                {{ $statusLabel[$hist->status_novo] ?? $hist->status_novo }}
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

    {{-- ── COLUNA LATERAL ──────────────────────────────────── --}}
    <div class="col-12 col-lg-4">

        {{-- ── PAINEL DE AÇÕES ──────────────────────────── --}}
        @php
            $temAcoes = array_intersect($acoes, ['assumir','devolver','retornar','finalizar','desativar','reabrir']);
        @endphp
        @if(count($temAcoes) > 0)
        <div class="card-sced card-body-sced mb-3 show-acoes-card">
            <div class="show-section-title mb-0" style="margin-bottom:16px;">⚡ Ações Disponíveis</div>

            {{-- ASSUMIR --}}
            @if(in_array('assumir', $acoes))
            <form method="POST" action="{{ route('documentos.assumir', $documento) }}" class="show-acao-form">
                @csrf
                <div class="mb-2">
                    <label class="show-acao-label">Observações (opcional)</label>
                    <textarea name="observacoes" class="form-input-sced" rows="2"
                              placeholder="Detalhes ao assumir..."></textarea>
                </div>
                <button type="submit" class="show-acao-btn show-acao-btn--assumir">
                    🎯 Assumir Processo
                </button>
            </form>
            @endif

            {{-- DEVOLVER --}}
            @if(in_array('devolver', $acoes))
            <form method="POST" action="{{ route('documentos.devolver', $documento) }}" class="show-acao-form">
                @csrf
                <div class="mb-2">
                    <label class="show-acao-label">Motivo da devolução <span style="color:var(--vermelho)">*</span></label>
                    <textarea name="motivo" class="form-input-sced" rows="2"
                              placeholder="Descreva o motivo da devolução..." required></textarea>
                </div>
                <button type="submit" class="show-acao-btn show-acao-btn--devolver">
                    ↩️ Devolver ao Solicitante
                </button>
            </form>
            @endif

            {{-- FINALIZAR --}}
            @if(in_array('finalizar', $acoes))
            <form method="POST" action="{{ route('documentos.finalizar', $documento) }}" class="show-acao-form">
                @csrf
                <div class="mb-2">
                    <label class="show-acao-label">Observações finais (opcional)</label>
                    <textarea name="observacoes" class="form-input-sced" rows="2"
                              placeholder="Considerações finais..."></textarea>
                </div>
                <button type="submit" class="show-acao-btn show-acao-btn--finalizar">
                    ✅ Finalizar Processo
                </button>
            </form>
            @endif

            {{-- RETORNAR (reenviar pelo solicitante) --}}
            @if(in_array('retornar', $acoes))
            <form method="POST" action="{{ route('documentos.retornar', $documento) }}"
                  enctype="multipart/form-data" class="show-acao-form">
                @csrf
                <div class="mb-2">
                    <label class="show-acao-label">Observações</label>
                    <textarea name="observacoes" class="form-input-sced" rows="2"
                              placeholder="Descreva os ajustes realizados..."></textarea>
                </div>
                <div class="mb-2">
                    <label class="show-acao-label">Novos anexos (opcional)</label>
                    <input type="file" name="anexos[]" multiple class="form-input-sced"
                           accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                </div>
                <button type="submit" class="show-acao-btn show-acao-btn--retornar">
                    📤 Reenviar ao Analista
                </button>
            </form>
            @endif

            {{-- DESATIVAR --}}
            @if(in_array('desativar', $acoes))
            <form method="POST" action="{{ route('documentos.desativar', $documento) }}" class="show-acao-form">
                @csrf
                <div class="mb-2">
                    <label class="show-acao-label">Motivo <span style="color:var(--vermelho)">*</span></label>
                    <textarea name="motivo" class="form-input-sced" rows="2"
                              placeholder="Motivo da desativação..." required></textarea>
                </div>
                <button type="submit" class="show-acao-btn show-acao-btn--desativar">
                    🚫 Desativar Processo
                </button>
            </form>
            @endif

            {{-- REABRIR --}}
            @if(in_array('reabrir', $acoes))
            <form method="POST" action="{{ route('documentos.reabrir', $documento) }}" class="show-acao-form">
                @csrf
                <div class="mb-2">
                    <label class="show-acao-label">Observações (opcional)</label>
                    <textarea name="observacoes" class="form-input-sced" rows="2"
                              placeholder="Justificativa para reabertura..."></textarea>
                </div>
                <button type="submit" class="show-acao-btn show-acao-btn--reabrir">
                    🔄 Reabrir Processo
                </button>
            </form>
            @endif
        </div>
        @endif

        {{-- Alterar status manual (apenas admin) --}}
        @can('alterarStatusManual', $documento)
        <div class="card-sced card-body-sced mb-3">
            <div class="show-section-title" style="font-size:13px;">🔄 Alterar Status Manualmente</div>
            <form method="POST" action="{{ route('documentos.status-manual', $documento) }}">
                @csrf @method('PATCH')
                <div style="margin-bottom:10px;">
                    <label class="show-acao-label">Novo Status</label>
                    <select name="status" class="form-input-sced">
                        <option value="novo"       {{ $documento->status==='novo'       ? 'selected':'' }}>🆕 Novo</option>
                        <option value="em_analise" {{ $documento->status==='em_analise' ? 'selected':'' }}>🔍 Em Análise</option>
                        <option value="pendente"   {{ $documento->status==='pendente'   ? 'selected':'' }}>⏳ Pendente</option>
                        <option value="finalizado" {{ $documento->status==='finalizado' ? 'selected':'' }}>✅ Finalizado</option>
                        <option value="desativado" {{ $documento->status==='desativado' ? 'selected':'' }}>🚫 Desativado</option>
                    </select>
                </div>
                <div style="margin-bottom:10px;">
                    <label class="show-acao-label">Observações</label>
                    <textarea name="observacoes" class="form-input-sced" rows="2"
                              placeholder="Motivo ou detalhes..."></textarea>
                </div>
                <button type="submit" class="btn-primary-sced" style="width:100%;justify-content:center;font-size:13px;">
                    Salvar Status
                </button>
            </form>
        </div>
        @endcan

        {{-- Resumo técnico --}}
        <div class="card-sced card-body-sced">
            <div class="show-section-title" style="font-size:13px;">ℹ️ Dados Técnicos</div>
            <div style="display:flex;flex-direction:column;gap:12px;">
                @foreach([
                    ['Serviço',     $documento->tipoDocumento->nome],
                    ['Setor',       $documento->setor_destino],
                    ['Responsável', $documento->tipoDocumento->cargo_responsavel ?? '—'],
                    ['Protocolo',   $documento->numero_protocolo],
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
.mb-2 { margin-bottom:8px; }
</style>
@endpush