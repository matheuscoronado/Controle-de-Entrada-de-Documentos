{{-- ============================================================
     resources/views/processos/show.blade.php — PARTE 3
     Detalhe completo do processo com painéis de ação contextuais.
     Os painéis aparecem/somem de acordo com o status e perfil.
     ============================================================ --}}
@extends('layouts.app')
@section('title', 'Processo ' . $documento->numero_protocolo)
@section('subtitle', $documento->tipoDocumento->nome)

@section('topbar-actions')
    <a href="{{ route('documentos.index') }}" class="btn-secondary-sced">← Processos</a>
    @if(in_array('editar', $acoes))
        <a href="{{ route('documentos.edit', $documento) }}" class="btn-outline-sced">✏️ Editar</a>
    @endif
@endsection

@section('content')

{{-- ── Alerta contextual por status ─────────────────────── --}}
@if($documento->status === 'pendente' && $documento->usuario_registro_id === auth()->id())
<div class="alerta-status alerta-pendente">
    <div class="alerta-status-icone">⚠️</div>
    <div>
        <strong>Ação necessária: este processo está pendente.</strong><br>
        <span style="font-size:13px;">{{ $documento->motivo_pendencia }}</span>
    </div>
</div>
@endif

@if($documento->status === 'desativado')
<div class="alerta-status alerta-desativado">
    <div class="alerta-status-icone">🚫</div>
    <div>
        <strong>Processo desativado.</strong>
        @if($documento->motivo_desativacao)
            <br><span style="font-size:13px;">{{ $documento->motivo_desativacao }}</span>
        @endif
    </div>
</div>
@endif

<div class="row g-3">

{{-- ════════════════════════════════════════════════════
     COLUNA PRINCIPAL
════════════════════════════════════════════════════ --}}
<div class="col-12 col-lg-8">

    {{-- Cabeçalho --}}
    <div class="card-sced card-body-sced mb-3">
        <div style="display:flex;align-items:flex-start;justify-content:space-between;flex-wrap:wrap;gap:12px;">
            <div>
                <div style="font-size:11px;color:var(--cinza-400);text-transform:uppercase;letter-spacing:1px;margin-bottom:4px;">Protocolo</div>
                <div class="protocolo-codigo" style="font-size:18px;padding:8px 14px;">{{ $documento->numero_protocolo }}</div>
            </div>
            @php
                $cores = \App\Models\Documento::STATUS_CORES[$documento->status] ?? [];
            @endphp
            <span class="status-badge" data-bg="{{ $cores['bg'] ?? '#f1f5f9' }}" data-color="{{ $cores['color'] ?? '#64748b' }}" style="padding:8px 18px;border-radius:20px;font-size:14px;font-weight:700;">
                ● {{ $documento->label_status }}
            </span>
        </div>
    </div>

    {{-- Dados principais --}}
    <div class="card-sced card-body-sced mb-3">
        <strong style="font-size:15px;color:var(--azul-escuro);display:block;margin-bottom:20px;">📋 Informações do Processo</strong>
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
                    @if($documento->atribuidoA)
                    · Responsável atual: <strong>{{ $documento->atribuidoA->nome }}</strong>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- ── PAINÉIS DE AÇÃO CONTEXTUAIS ────────────────────── --}}

    {{-- ASSUMIR — aparece quando status = novo ou pendente (sem dono) --}}
    @if(in_array('assumir', $acoes))
    <div class="card-sced card-body-sced mb-3 painel-acao painel-azul">
        <div class="painel-acao-header">
            <span class="painel-acao-icone">👤</span>
            <div>
                <div class="painel-acao-titulo">Assumir este processo</div>
                <div class="painel-acao-sub">O status mudará automaticamente para <strong>Em Análise</strong></div>
            </div>
        </div>
        <form method="POST" action="{{ route('documentos.assumir', $documento) }}">
            @csrf
            <div class="mb-3">
                <label class="dado-label">Observações (opcional)</label>
                <textarea name="observacoes" class="form-input-sced" rows="2"
                          placeholder="Informe detalhes caso necessário..."></textarea>
            </div>
            <button type="submit" class="btn-primary-sced">👤 Assumir Processo</button>
        </form>
    </div>
    @endif

    {{-- DEVOLVER — aparece quando em_analise e é o responsável --}}
    @if(in_array('devolver', $acoes))
    <div class="card-sced card-body-sced mb-3 painel-acao painel-amarelo">
        <div class="painel-acao-header">
            <span class="painel-acao-icone">↩️</span>
            <div>
                <div class="painel-acao-titulo">Devolver ao Solicitante</div>
                <div class="painel-acao-sub">O status mudará para <strong>Pendente</strong> e o solicitante será notificado</div>
            </div>
        </div>
        <form method="POST" action="{{ route('documentos.devolver', $documento) }}">
            @csrf
            <div class="mb-3">
                <label class="dado-label">Motivo da devolução <span style="color:var(--vermelho)">*</span></label>
                <textarea name="motivo" class="form-input-sced" rows="3" required
                          placeholder="Descreva o que está faltando ou o que precisa ser corrigido..."></textarea>
            </div>
            <button type="submit" class="btn-amarelo">↩️ Devolver ao Solicitante</button>
        </form>
    </div>
    @endif

    {{-- RETORNAR — aparece quando pendente e é o solicitante --}}
    @if(in_array('retornar', $acoes))
    <div class="card-sced card-body-sced mb-3 painel-acao painel-verde">
        <div class="painel-acao-header">
            <span class="painel-acao-icone">🔄</span>
            <div>
                <div class="painel-acao-titulo">Reenviar Processo</div>
                <div class="painel-acao-sub">Faça os ajustes e reenvie. O status voltará para <strong>Em Análise</strong></div>
            </div>
        </div>
        <form method="POST" action="{{ route('documentos.retornar', $documento) }}" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label class="dado-label">Observações sobre os ajustes</label>
                <textarea name="observacoes" class="form-input-sced" rows="2"
                          placeholder="Descreva o que foi corrigido..."></textarea>
            </div>
            {{-- Upload de novos anexos --}}
            <div class="mb-3">
                <label class="dado-label">Adicionar documentos corrigidos (opcional)</label>
                <div class="upload-mini" onclick="document.getElementById('inputRetorno').click()">
                    <span>📎</span> Clique para selecionar arquivos
                </div>
                <input type="file" id="inputRetorno" name="anexos[]"
                       multiple style="display:none" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                       onchange="mostrarArquivosRetorno(this)">
                <div id="listaRetorno" style="margin-top:8px;"></div>
            </div>
            <button type="submit" class="btn-verde">🔄 Reenviar para Análise</button>
        </form>
    </div>
    @endif

    {{-- FINALIZAR — aparece quando em_analise e é o responsável --}}
    @if(in_array('finalizar', $acoes))
    <div class="card-sced card-body-sced mb-3 painel-acao painel-verde">
        <div class="painel-acao-header">
            <span class="painel-acao-icone">✅</span>
            <div>
                <div class="painel-acao-titulo">Finalizar Processo</div>
                <div class="painel-acao-sub">Use após validar que tudo está correto. Esta ação não é reversível por operadores.</div>
            </div>
        </div>
        <form method="POST" action="{{ route('documentos.finalizar', $documento) }}">
            @csrf
            <div class="mb-3">
                <label class="dado-label">Observações finais (opcional)</label>
                <textarea name="observacoes" class="form-input-sced" rows="2"
                          placeholder="Conclusão ou resultado do processo..."></textarea>
            </div>
            <button type="submit" class="btn-verde"
                    onclick="return confirm('Confirmar finalização do processo?')">
                ✅ Finalizar Processo
            </button>
        </form>
    </div>
    @endif

    {{-- ── ANEXOS com substituição e validação ──────────── --}}
    @if($documento->anexos->count() > 0)
    <div class="card-sced card-body-sced mb-3">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;">
            <strong style="font-size:15px;color:var(--azul-escuro);">📎 Documentos Anexos</strong>
            <span style="font-size:12px;color:var(--cinza-400);">
                {{ $documento->anexos->where('status_validacao','aprovado')->count() }} aprovado(s) ·
                {{ $documento->anexos->where('status_validacao','pendente')->count() }} pendente(s) ·
                {{ $documento->anexos->where('status_validacao','rejeitado')->count() }} rejeitado(s)
            </span>
        </div>

        @foreach($documento->anexos as $anexo)
        <div class="anexo-item mb-3" id="anexo-{{ $anexo->id }}">
            <div class="anexo-item-info">
                <div style="display:flex;align-items:center;gap:10px;">
                    <span style="font-size:22px;">{{ str_contains($anexo->tipo_mime,'image') ? '🖼️' : (str_ends_with($anexo->nome_arquivo,'.pdf') ? '📕' : '📄') }}</span>
                    <div style="min-width:0;">
                        <div style="font-size:13px;font-weight:600;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $anexo->nome_arquivo }}</div>
                        <div style="font-size:11px;color:var(--cinza-400);">
                            {{ $anexo->label_tipo_anexo }} · {{ number_format($anexo->tamanho_bytes/1024,1) }} KB
                        </div>
                    </div>
                </div>
                <div style="display:flex;align-items:center;gap:8px;flex-shrink:0;flex-wrap:wrap;">
                    @php
                        $vBg    = ['pendente'=>'#fef3c7','aprovado'=>'#d1fae5','rejeitado'=>'#fef2f2'];
                        $vColor = ['pendente'=>'#92400e','aprovado'=>'#065f46','rejeitado'=>'#991b1b'];
                        $vLabel = ['pendente'=>'⏳ Pendente','aprovado'=>'✅ Aprovado','rejeitado'=>'❌ Rejeitado'];
                    @endphp
                    <span class="validation-badge" data-bg="{{ $vBg[$anexo->status_validacao] }}" data-color="{{ $vColor[$anexo->status_validacao] }}" style="font-size:11px;font-weight:700;padding:3px 10px;border-radius:10px;">
                        {{ $vLabel[$anexo->status_validacao] }}
                    </span>
                    <a href="{{ Storage::url($anexo->caminho_arquivo) }}" target="_blank"
                       class="btn-outline-sced" style="font-size:12px;padding:4px 10px;">⬇</a>
                </div>
            </div>

            {{-- Substituir anexo (solicitante, processo pendente) --}}
            @if(in_array('substituir_anexo', $acoes))
            <div class="substituir-wrap" id="sw-{{ $anexo->id }}">
                <button type="button" class="btn-link-sced" data-anexo-id="{{ $anexo->id }}" onclick="toggleSubstituir(this.dataset.anexoId)">
                    🔄 Substituir arquivo
                </button>
                <div class="substituir-form" id="sf-{{ $anexo->id }}" style="display:none;">
                    <form method="POST"
                          action="{{ route('documentos.anexo.substituir', [$documento, $anexo]) }}"
                          enctype="multipart/form-data"
                          style="display:flex;gap:8px;align-items:flex-end;flex-wrap:wrap;margin-top:8px;">
                        @csrf
                        <div style="flex:1;">
                            <input type="file" name="arquivo" class="form-input-sced" required
                                   accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" style="font-size:13px;">
                        </div>
                        <div>
                            <select name="tipo_anexo" class="form-input-sced" style="font-size:13px;">
                                @foreach(\App\Models\ArquivoAnexo::$tiposAnexo as $v => $l)
                                    <option value="{{ $v }}" {{ $v === $anexo->tipo_anexo ? 'selected':'' }}>{{ $l }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn-primary-sced" style="font-size:13px;padding:9px 14px;">Enviar</button>
                    </form>
                </div>
            </div>
            @endif

            {{-- Validar anexo (admin/N3) --}}
            @if(in_array('validar_anexo', $acoes))
            <div style="margin-top:10px;padding-top:10px;border-top:1px dashed var(--cinza-200);">
                <form method="POST"
                      action="{{ route('documentos.anexo.validar', [$documento, $anexo]) }}"
                      style="display:flex;gap:8px;align-items:flex-end;flex-wrap:wrap;">
                    @csrf
                    <div style="flex:1;">
                        <input type="text" name="observacao" class="form-input-sced"
                               placeholder="Observação (opcional)" style="font-size:13px;"
                               value="{{ $anexo->observacao_validacao }}">
                    </div>
                    <button name="status_validacao" value="aprovado" type="submit"
                            class="btn-verde" style="font-size:13px;padding:9px 14px;">✅ Aprovar</button>
                    <button name="status_validacao" value="rejeitado" type="submit"
                            class="btn-vermelho" style="font-size:13px;padding:9px 14px;">❌ Rejeitar</button>
                </form>
            </div>
            @endif
        </div>
        @endforeach
    </div>
    @endif

    {{-- Histórico --}}
    <div class="card-sced card-body-sced mb-3">
        <strong style="font-size:15px;color:var(--azul-escuro);display:block;margin-bottom:20px;">🕐 Histórico</strong>
        <ul class="timeline">
            @foreach($documento->historicos as $hist)
            <li class="timeline-item">
                <div class="timeline-dot">{{ $hist->icone_tipo }}</div>
                <div class="timeline-content">
                    <div class="timeline-date">
                        {{ $hist->label_tipo }}
                        · <strong>{{ $hist->usuario->nome ?? '—' }}</strong>
                        @if($hist->usuarioDestino)
                            → <strong>{{ $hist->usuarioDestino->nome }}</strong>
                        @endif
                        · {{ \Carbon\Carbon::parse($hist->data_hora)->format('d/m/Y H:i') }}
                    </div>
                    @if($hist->status_anterior || $hist->status_novo)
                    <div class="timeline-text">
                        @if($hist->status_anterior)
                            @php $ca = \App\Models\Documento::STATUS_CORES[$hist->status_anterior] ?? []; @endphp
                            <span class="history-badge" data-bg="{{ $ca['bg'] ?? '#f1f5f9' }}" data-color="{{ $ca['color'] ?? '#64748b' }}" style="font-size:11px;font-weight:700;padding:2px 8px;border-radius:10px;">
                                {{ \App\Models\Documento::STATUS[$hist->status_anterior] ?? $hist->status_anterior }}
                            </span>
                            →
                        @endif
                        @php $cn = \App\Models\Documento::STATUS_CORES[$hist->status_novo] ?? []; @endphp
                        <span class="history-badge" data-bg="{{ $cn['bg'] ?? '#f1f5f9' }}" data-color="{{ $cn['color'] ?? '#64748b' }}" style="font-size:11px;font-weight:700;padding:2px 8px;border-radius:10px;">
                            {{ \App\Models\Documento::STATUS[$hist->status_novo] ?? $hist->status_novo }}
                        </span>
                    </div>
                    @endif
                    @if($hist->observacoes)
                    <div style="font-size:12px;color:var(--cinza-600);margin-top:4px;font-style:italic;">
                        "{{ $hist->observacoes }}"
                    </div>
                    @endif
                </div>
            </li>
            @endforeach
        </ul>
    </div>

</div>{{-- /col-lg-8 --}}

{{-- ════════════════════════════════════════════════════
     COLUNA LATERAL
════════════════════════════════════════════════════ --}}
<div class="col-12 col-lg-4">

    {{-- Info técnica --}}
    <div class="card-sced card-body-sced mb-3">
        <strong style="font-size:14px;color:var(--azul-escuro);display:block;margin-bottom:16px;">ℹ️ Dados Técnicos</strong>
        <div style="display:flex;flex-direction:column;gap:12px;">
            @foreach([
                ['Serviço',      $documento->tipoDocumento->nome],
                ['Setor',        $documento->setor_destino],
                ['Responsável',  $documento->tipoDocumento->cargo_responsavel ?? '—'],
                ['SLA',          $documento->tipoDocumento->label_sla],
                ['Protocolo',    $documento->numero_protocolo],
                ['Aberto por',   $documento->usuarioRegistro->nome],
                ['Analista',     $documento->atribuidoA?->nome ?? 'Sem responsável'],
            ] as [$label,$valor])
            <div>
                <div style="font-size:10px;font-weight:600;text-transform:uppercase;letter-spacing:.8px;color:var(--cinza-400);margin-bottom:2px;">{{ $label }}</div>
                <div style="font-size:13px;font-weight:600;color:var(--cinza-800);">{{ $valor }}</div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Painel Admin/N3: Desativar, Reabrir, Status manual --}}
    @if(in_array('desativar', $acoes) || in_array('reabrir', $acoes) || in_array('alteracao_manual', $acoes))
    <div class="card-sced card-body-sced mb-3" style="border:1.5px solid #fde68a;">
        <strong style="font-size:13px;color:var(--cinza-600);display:block;margin-bottom:14px;text-transform:uppercase;letter-spacing:.8px;">
            ⚙️ Controles Admin / N3
        </strong>

        @if(in_array('alteracao_manual', $acoes))
        <div style="margin-bottom:16px;">
            <label class="dado-label" style="margin-bottom:6px;">Alterar status manualmente</label>
            <form method="POST" action="{{ route('documentos.status-manual', $documento) }}">
                @csrf @method('PATCH')
                <select name="status" class="form-input-sced" style="margin-bottom:8px;font-size:13px;">
                    @foreach(\App\Models\Documento::STATUS as $val => $label)
                        @if($val !== $documento->status)
                        <option value="{{ $val }}">{{ $label }}</option>
                        @endif
                    @endforeach
                </select>
                <input type="text" name="observacoes" class="form-input-sced"
                       placeholder="Motivo (opcional)" style="margin-bottom:8px;font-size:13px;">
                <button type="submit" class="btn-outline-sced" style="width:100%;justify-content:center;font-size:13px;">
                    ⚙️ Aplicar Alteração
                </button>
            </form>
        </div>
        @endif

        @if(in_array('reabrir', $acoes))
        <div style="margin-bottom:12px;">
            <form method="POST" action="{{ route('documentos.reabrir', $documento) }}">
                @csrf
                <input type="text" name="observacoes" class="form-input-sced"
                       placeholder="Motivo da reabertura (opcional)" style="margin-bottom:8px;font-size:13px;">
                <button type="submit" class="btn-outline-sced" style="width:100%;justify-content:center;font-size:13px;"
                        onclick="return confirm('Confirmar reabertura?')">
                    🔓 Reabrir Processo
                </button>
            </form>
        </div>
        @endif

        @if(in_array('desativar', $acoes))
        <div>
            <form method="POST" action="{{ route('documentos.desativar', $documento) }}">
                @csrf
                <textarea name="motivo" class="form-input-sced" rows="2" required
                          placeholder="Motivo da desativação *" style="margin-bottom:8px;font-size:13px;"></textarea>
                <button type="submit" class="btn-vermelho" style="width:100%;justify-content:center;font-size:13px;"
                        onclick="return confirm('Desativar este processo?')">
                    🚫 Desativar Processo
                </button>
            </form>
        </div>
        @endif
    </div>
    @endif

</div>

</div>{{-- /row --}}
@endsection

@push('styles')
<style>
.dado-label { font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:.6px;color:var(--cinza-400);margin-bottom:4px; }
.dado-valor { font-size:14px;font-weight:500; }

/* ── Alertas de contexto ─────────────────── */
.alerta-status { display:flex;gap:12px;align-items:flex-start;padding:14px 18px;border-radius:var(--radius-sm);margin-bottom:18px;font-size:14px; }
.alerta-status-icone { font-size:20px;flex-shrink:0; }
.alerta-pendente { background:#fef3c7;border:1.5px solid #fde68a;color:#92400e; }
.alerta-desativado { background:#f1f5f9;border:1.5px solid var(--cinza-200);color:var(--cinza-600); }

/* ── Painéis de ação ─────────────────────── */
.painel-acao { border-left:4px solid transparent; }
.painel-azul  { border-left-color:var(--azul-claro); }
.painel-amarelo { border-left-color:var(--amarelo); }
.painel-verde { border-left-color:var(--verde); }
.painel-acao-header { display:flex;align-items:flex-start;gap:12px;margin-bottom:18px;padding-bottom:14px;border-bottom:1px solid var(--cinza-200); }
.painel-acao-icone { font-size:22px;flex-shrink:0; }
.painel-acao-titulo { font-size:15px;font-weight:700;color:var(--azul-escuro); }
.painel-acao-sub { font-size:12px;color:var(--cinza-400);margin-top:2px; }

/* ── Botões extras ───────────────────────── */
.btn-amarelo { display:inline-flex;align-items:center;gap:8px;padding:9px 18px;border-radius:var(--radius-sm);border:none;cursor:pointer;font-family:'Sora',sans-serif;font-size:14px;font-weight:600;background:#f59e0b;color:#fff;transition:var(--transicao); }
.btn-amarelo:hover { background:#d97706; }
.btn-verde { display:inline-flex;align-items:center;gap:8px;padding:9px 18px;border-radius:var(--radius-sm);border:none;cursor:pointer;font-family:'Sora',sans-serif;font-size:14px;font-weight:600;background:var(--verde);color:#fff;transition:var(--transicao); }
.btn-verde:hover { background:#059669; }
.btn-vermelho { display:inline-flex;align-items:center;gap:8px;padding:9px 18px;border-radius:var(--radius-sm);border:none;cursor:pointer;font-family:'Sora',sans-serif;font-size:14px;font-weight:600;background:var(--vermelho);color:#fff;transition:var(--transicao); }
.btn-vermelho:hover { background:#dc2626; }
.btn-link-sced { background:none;border:none;cursor:pointer;color:var(--azul-claro);font-size:12px;font-weight:600;padding:4px 0;text-decoration:underline;font-family:'Sora',sans-serif; }

/* ── Anexos ──────────────────────────────── */
.anexo-item { padding:12px 14px;background:var(--cinza-100);border-radius:var(--radius-sm); }
.anexo-item-info { display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap; }
.substituir-wrap { margin-top:8px;padding-top:8px;border-top:1px dashed var(--cinza-200); }

/* ── Mini upload ─────────────────────────── */
.upload-mini { display:flex;align-items:center;gap:8px;padding:10px 14px;border:1.5px dashed var(--cinza-200);border-radius:var(--radius-sm);cursor:pointer;font-size:13px;color:var(--cinza-600);background:var(--cinza-100);transition:var(--transicao); }
.upload-mini:hover { border-color:var(--azul-claro);color:var(--azul-claro); }

.mb-3 { margin-bottom:12px; }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.status-badge, .validation-badge, .history-badge').forEach(function(el) {
        if (el.dataset.bg) {
            el.style.background = el.dataset.bg;
        }
        if (el.dataset.color) {
            el.style.color = el.dataset.color;
        }
    });
});
</script>
@endpush

@push('scripts')
<script>
function toggleSubstituir(id) {
    const el = document.getElementById('sf-' + id);
    el.style.display = el.style.display === 'none' ? 'block' : 'none';
}

function mostrarArquivosRetorno(input) {
    const lista = document.getElementById('listaRetorno');
    lista.innerHTML = '';
    Array.from(input.files).forEach(file => {
        const div = document.createElement('div');
        div.style.cssText = 'display:flex;align-items:center;gap:8px;font-size:12px;color:var(--cinza-600);margin-bottom:4px;';
        div.innerHTML = `<span>📄</span><span>${file.name}</span>`;
        lista.appendChild(div);
    });
}
</script>
@endpush
