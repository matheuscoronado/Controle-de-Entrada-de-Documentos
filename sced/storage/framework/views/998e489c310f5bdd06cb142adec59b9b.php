<?php $__env->startSection('title', 'Processo ' . $documento->numero_protocolo); ?>
<?php $__env->startSection('subtitle', $documento->tipoDocumento->nome); ?>

<?php $__env->startSection('topbar-actions'); ?>
    <a href="<?php echo e(route('documentos.index')); ?>" class="btn-secondary-sced">← Processos</a>
    <?php if(in_array('editar', $acoes)): ?>
        <a href="<?php echo e(route('documentos.edit', $documento)); ?>" class="btn-outline-sced">✏️ Editar</a>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>


<?php if($documento->status === 'pendente' && $documento->usuario_registro_id === auth()->id()): ?>
<div class="alerta-status alerta-pendente">
    <div class="alerta-status-icone">⚠️</div>
    <div>
        <strong>Ação necessária: este processo está pendente.</strong><br>
        <span style="font-size:13px;"><?php echo e($documento->motivo_pendencia); ?></span>
    </div>
</div>
<?php endif; ?>

<?php if($documento->status === 'desativado'): ?>
<div class="alerta-status alerta-desativado">
    <div class="alerta-status-icone">🚫</div>
    <div>
        <strong>Processo desativado.</strong>
        <?php if($documento->motivo_desativacao): ?>
            <br><span style="font-size:13px;"><?php echo e($documento->motivo_desativacao); ?></span>
        <?php endif; ?>
    </div>
</div>
<?php endif; ?>

<div class="row g-3">


<div class="col-12 col-lg-8">

    
    <div class="card-sced card-body-sced mb-3">
        <div style="display:flex;align-items:flex-start;justify-content:space-between;flex-wrap:wrap;gap:12px;">
            <div>
                <div style="font-size:11px;color:var(--cinza-400);text-transform:uppercase;letter-spacing:1px;margin-bottom:4px;">Protocolo</div>
                <div class="protocolo-codigo" style="font-size:18px;padding:8px 14px;"><?php echo e($documento->numero_protocolo); ?></div>
            </div>
            <?php
                $cores = \App\Models\Documento::STATUS_CORES[$documento->status] ?? [];
            ?>
            <span class="status-badge" data-bg="<?php echo e($cores['bg'] ?? '#f1f5f9'); ?>" data-color="<?php echo e($cores['color'] ?? '#64748b'); ?>" style="padding:8px 18px;border-radius:20px;font-size:14px;font-weight:700;">
                ● <?php echo e($documento->label_status); ?>

            </span>
        </div>
    </div>

    
    <div class="card-sced card-body-sced mb-3">
        <strong style="font-size:15px;color:var(--azul-escuro);display:block;margin-bottom:20px;">📋 Informações do Processo</strong>
        <div class="row g-3">
            <div class="col-6">
                <div class="dado-label">Serviço</div>
                <div class="dado-valor"><?php echo e($documento->tipoDocumento->nome); ?></div>
            </div>
            <div class="col-6">
                <div class="dado-label">Data de Abertura</div>
                <div class="dado-valor"><?php echo e(\Carbon\Carbon::parse($documento->data_recebimento)->format('d/m/Y')); ?></div>
            </div>
            <div class="col-6">
                <div class="dado-label">Solicitante</div>
                <div class="dado-valor"><?php echo e($documento->remetente); ?></div>
            </div>
            <div class="col-6">
                <div class="dado-label">Setor de Destino</div>
                <div class="dado-valor"><?php echo e($documento->setor_destino); ?></div>
            </div>
            <?php if($documento->descricao): ?>
            <div class="col-12">
                <div class="dado-label">Descrição</div>
                <div class="dado-valor" style="color:var(--cinza-600);line-height:1.6;"><?php echo e($documento->descricao); ?></div>
            </div>
            <?php endif; ?>
            <div class="col-12" style="padding-top:8px;border-top:1px solid var(--cinza-200);">
                <div style="font-size:11px;color:var(--cinza-400);">
                    Aberto por <strong><?php echo e($documento->usuarioRegistro->nome); ?></strong>
                    em <?php echo e($documento->created_at->format('d/m/Y \à\s H:i')); ?>

                    <?php if($documento->atribuidoA): ?>
                    · Responsável atual: <strong><?php echo e($documento->atribuidoA->nome); ?></strong>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    

    
    <?php if(in_array('assumir', $acoes)): ?>
    <div class="card-sced card-body-sced mb-3 painel-acao painel-azul">
        <div class="painel-acao-header">
            <span class="painel-acao-icone">👤</span>
            <div>
                <div class="painel-acao-titulo">Assumir este processo</div>
                <div class="painel-acao-sub">O status mudará automaticamente para <strong>Em Análise</strong></div>
            </div>
        </div>
        <form method="POST" action="<?php echo e(route('documentos.assumir', $documento)); ?>">
            <?php echo csrf_field(); ?>
            <div class="mb-3">
                <label class="dado-label">Observações (opcional)</label>
                <textarea name="observacoes" class="form-input-sced" rows="2"
                          placeholder="Informe detalhes caso necessário..."></textarea>
            </div>
            <button type="submit" class="btn-primary-sced">👤 Assumir Processo</button>
        </form>
    </div>
    <?php endif; ?>

    
    <?php if(in_array('devolver', $acoes)): ?>
    <div class="card-sced card-body-sced mb-3 painel-acao painel-amarelo">
        <div class="painel-acao-header">
            <span class="painel-acao-icone">↩️</span>
            <div>
                <div class="painel-acao-titulo">Devolver ao Solicitante</div>
                <div class="painel-acao-sub">O status mudará para <strong>Pendente</strong> e o solicitante será notificado</div>
            </div>
        </div>
        <form method="POST" action="<?php echo e(route('documentos.devolver', $documento)); ?>">
            <?php echo csrf_field(); ?>
            <div class="mb-3">
                <label class="dado-label">Motivo da devolução <span style="color:var(--vermelho)">*</span></label>
                <textarea name="motivo" class="form-input-sced" rows="3" required
                          placeholder="Descreva o que está faltando ou o que precisa ser corrigido..."></textarea>
            </div>
            <button type="submit" class="btn-amarelo">↩️ Devolver ao Solicitante</button>
        </form>
    </div>
    <?php endif; ?>

    
    <?php if(in_array('retornar', $acoes)): ?>
    <div class="card-sced card-body-sced mb-3 painel-acao painel-verde">
        <div class="painel-acao-header">
            <span class="painel-acao-icone">🔄</span>
            <div>
                <div class="painel-acao-titulo">Reenviar Processo</div>
                <div class="painel-acao-sub">Faça os ajustes e reenvie. O status voltará para <strong>Em Análise</strong></div>
            </div>
        </div>
        <form method="POST" action="<?php echo e(route('documentos.retornar', $documento)); ?>" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            <div class="mb-3">
                <label class="dado-label">Observações sobre os ajustes</label>
                <textarea name="observacoes" class="form-input-sced" rows="2"
                          placeholder="Descreva o que foi corrigido..."></textarea>
            </div>
            
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
    <?php endif; ?>

    
    <?php if(in_array('finalizar', $acoes)): ?>
    <div class="card-sced card-body-sced mb-3 painel-acao painel-verde">
        <div class="painel-acao-header">
            <span class="painel-acao-icone">✅</span>
            <div>
                <div class="painel-acao-titulo">Finalizar Processo</div>
                <div class="painel-acao-sub">Use após validar que tudo está correto. Esta ação não é reversível por operadores.</div>
            </div>
        </div>
        <form method="POST" action="<?php echo e(route('documentos.finalizar', $documento)); ?>">
            <?php echo csrf_field(); ?>
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
    <?php endif; ?>

    
    <?php if($documento->anexos->count() > 0): ?>
    <div class="card-sced card-body-sced mb-3">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;">
            <strong style="font-size:15px;color:var(--azul-escuro);">📎 Documentos Anexos</strong>
            <span style="font-size:12px;color:var(--cinza-400);">
                <?php echo e($documento->anexos->where('status_validacao','aprovado')->count()); ?> aprovado(s) ·
                <?php echo e($documento->anexos->where('status_validacao','pendente')->count()); ?> pendente(s) ·
                <?php echo e($documento->anexos->where('status_validacao','rejeitado')->count()); ?> rejeitado(s)
            </span>
        </div>

        <?php $__currentLoopData = $documento->anexos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $anexo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="anexo-item mb-3" id="anexo-<?php echo e($anexo->id); ?>">
            <div class="anexo-item-info">
                <div style="display:flex;align-items:center;gap:10px;">
                    <span style="font-size:22px;"><?php echo e(str_contains($anexo->tipo_mime,'image') ? '🖼️' : (str_ends_with($anexo->nome_arquivo,'.pdf') ? '📕' : '📄')); ?></span>
                    <div style="min-width:0;">
                        <div style="font-size:13px;font-weight:600;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;"><?php echo e($anexo->nome_arquivo); ?></div>
                        <div style="font-size:11px;color:var(--cinza-400);">
                            <?php echo e($anexo->label_tipo_anexo); ?> · <?php echo e(number_format($anexo->tamanho_bytes/1024,1)); ?> KB
                        </div>
                    </div>
                </div>
                <div style="display:flex;align-items:center;gap:8px;flex-shrink:0;flex-wrap:wrap;">
                    <?php
                        $vBg    = ['pendente'=>'#fef3c7','aprovado'=>'#d1fae5','rejeitado'=>'#fef2f2'];
                        $vColor = ['pendente'=>'#92400e','aprovado'=>'#065f46','rejeitado'=>'#991b1b'];
                        $vLabel = ['pendente'=>'⏳ Pendente','aprovado'=>'✅ Aprovado','rejeitado'=>'❌ Rejeitado'];
                    ?>
                    <span class="validation-badge" data-bg="<?php echo e($vBg[$anexo->status_validacao]); ?>" data-color="<?php echo e($vColor[$anexo->status_validacao]); ?>" style="font-size:11px;font-weight:700;padding:3px 10px;border-radius:10px;">
                        <?php echo e($vLabel[$anexo->status_validacao]); ?>

                    </span>
                    <a href="<?php echo e(Storage::url($anexo->caminho_arquivo)); ?>" target="_blank"
                       class="btn-outline-sced" style="font-size:12px;padding:4px 10px;">⬇</a>
                </div>
            </div>

            
            <?php if(in_array('substituir_anexo', $acoes)): ?>
            <div class="substituir-wrap" id="sw-<?php echo e($anexo->id); ?>">
                <button type="button" class="btn-link-sced" data-anexo-id="<?php echo e($anexo->id); ?>" onclick="toggleSubstituir(this.dataset.anexoId)">
                    🔄 Substituir arquivo
                </button>
                <div class="substituir-form" id="sf-<?php echo e($anexo->id); ?>" style="display:none;">
                    <form method="POST"
                          action="<?php echo e(route('documentos.anexo.substituir', [$documento, $anexo])); ?>"
                          enctype="multipart/form-data"
                          style="display:flex;gap:8px;align-items:flex-end;flex-wrap:wrap;margin-top:8px;">
                        <?php echo csrf_field(); ?>
                        <div style="flex:1;">
                            <input type="file" name="arquivo" class="form-input-sced" required
                                   accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" style="font-size:13px;">
                        </div>
                        <div>
                            <select name="tipo_anexo" class="form-input-sced" style="font-size:13px;">
                                <?php $__currentLoopData = \App\Models\ArquivoAnexo::$tiposAnexo; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v => $l): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($v); ?>" <?php echo e($v === $anexo->tipo_anexo ? 'selected':''); ?>><?php echo e($l); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <button type="submit" class="btn-primary-sced" style="font-size:13px;padding:9px 14px;">Enviar</button>
                    </form>
                </div>
            </div>
            <?php endif; ?>

            
            <?php if(in_array('validar_anexo', $acoes)): ?>
            <div style="margin-top:10px;padding-top:10px;border-top:1px dashed var(--cinza-200);">
                <form method="POST"
                      action="<?php echo e(route('documentos.anexo.validar', [$documento, $anexo])); ?>"
                      style="display:flex;gap:8px;align-items:flex-end;flex-wrap:wrap;">
                    <?php echo csrf_field(); ?>
                    <div style="flex:1;">
                        <input type="text" name="observacao" class="form-input-sced"
                               placeholder="Observação (opcional)" style="font-size:13px;"
                               value="<?php echo e($anexo->observacao_validacao); ?>">
                    </div>
                    <button name="status_validacao" value="aprovado" type="submit"
                            class="btn-verde" style="font-size:13px;padding:9px 14px;">✅ Aprovar</button>
                    <button name="status_validacao" value="rejeitado" type="submit"
                            class="btn-vermelho" style="font-size:13px;padding:9px 14px;">❌ Rejeitar</button>
                </form>
            </div>
            <?php endif; ?>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
    <?php endif; ?>

    
    <div class="card-sced card-body-sced mb-3">
        <strong style="font-size:15px;color:var(--azul-escuro);display:block;margin-bottom:20px;">🕐 Histórico</strong>
        <ul class="timeline">
            <?php $__currentLoopData = $documento->historicos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $hist): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <li class="timeline-item">
                <div class="timeline-dot"><?php echo e($hist->icone_tipo); ?></div>
                <div class="timeline-content">
                    <div class="timeline-date">
                        <?php echo e($hist->label_tipo); ?>

                        · <strong><?php echo e($hist->usuario->nome ?? '—'); ?></strong>
                        <?php if($hist->usuarioDestino): ?>
                            → <strong><?php echo e($hist->usuarioDestino->nome); ?></strong>
                        <?php endif; ?>
                        · <?php echo e(\Carbon\Carbon::parse($hist->data_hora)->format('d/m/Y H:i')); ?>

                    </div>
                    <?php if($hist->status_anterior || $hist->status_novo): ?>
                    <div class="timeline-text">
                        <?php if($hist->status_anterior): ?>
                            <?php $ca = \App\Models\Documento::STATUS_CORES[$hist->status_anterior] ?? []; ?>
                            <span class="history-badge" data-bg="<?php echo e($ca['bg'] ?? '#f1f5f9'); ?>" data-color="<?php echo e($ca['color'] ?? '#64748b'); ?>" style="font-size:11px;font-weight:700;padding:2px 8px;border-radius:10px;">
                                <?php echo e(\App\Models\Documento::STATUS[$hist->status_anterior] ?? $hist->status_anterior); ?>

                            </span>
                            →
                        <?php endif; ?>
                        <?php $cn = \App\Models\Documento::STATUS_CORES[$hist->status_novo] ?? []; ?>
                        <span class="history-badge" data-bg="<?php echo e($cn['bg'] ?? '#f1f5f9'); ?>" data-color="<?php echo e($cn['color'] ?? '#64748b'); ?>" style="font-size:11px;font-weight:700;padding:2px 8px;border-radius:10px;">
                            <?php echo e(\App\Models\Documento::STATUS[$hist->status_novo] ?? $hist->status_novo); ?>

                        </span>
                    </div>
                    <?php endif; ?>
                    <?php if($hist->observacoes): ?>
                    <div style="font-size:12px;color:var(--cinza-600);margin-top:4px;font-style:italic;">
                        "<?php echo e($hist->observacoes); ?>"
                    </div>
                    <?php endif; ?>
                </div>
            </li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
    </div>

</div>


<div class="col-12 col-lg-4">

    
    <div class="card-sced card-body-sced mb-3">
        <strong style="font-size:14px;color:var(--azul-escuro);display:block;margin-bottom:16px;">ℹ️ Dados Técnicos</strong>
        <div style="display:flex;flex-direction:column;gap:12px;">
            <?php $__currentLoopData = [
                ['Serviço',      $documento->tipoDocumento->nome],
                ['Setor',        $documento->setor_destino],
                ['Responsável',  $documento->tipoDocumento->cargo_responsavel ?? '—'],
                ['SLA',          $documento->tipoDocumento->label_sla],
                ['Protocolo',    $documento->numero_protocolo],
                ['Aberto por',   $documento->usuarioRegistro->nome],
                ['Analista',     $documento->atribuidoA?->nome ?? 'Sem responsável'],
            ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$label,$valor]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div>
                <div style="font-size:10px;font-weight:600;text-transform:uppercase;letter-spacing:.8px;color:var(--cinza-400);margin-bottom:2px;"><?php echo e($label); ?></div>
                <div style="font-size:13px;font-weight:600;color:var(--cinza-800);"><?php echo e($valor); ?></div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>

    
    <?php if(in_array('desativar', $acoes) || in_array('reabrir', $acoes) || in_array('alteracao_manual', $acoes)): ?>
    <div class="card-sced card-body-sced mb-3" style="border:1.5px solid #fde68a;">
        <strong style="font-size:13px;color:var(--cinza-600);display:block;margin-bottom:14px;text-transform:uppercase;letter-spacing:.8px;">
            ⚙️ Controles Admin / N3
        </strong>

        <?php if(in_array('alteracao_manual', $acoes)): ?>
        <div style="margin-bottom:16px;">
            <label class="dado-label" style="margin-bottom:6px;">Alterar status manualmente</label>
            <form method="POST" action="<?php echo e(route('documentos.status-manual', $documento)); ?>">
                <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                <select name="status" class="form-input-sced" style="margin-bottom:8px;font-size:13px;">
                    <?php $__currentLoopData = \App\Models\Documento::STATUS; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if($val !== $documento->status): ?>
                        <option value="<?php echo e($val); ?>"><?php echo e($label); ?></option>
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <input type="text" name="observacoes" class="form-input-sced"
                       placeholder="Motivo (opcional)" style="margin-bottom:8px;font-size:13px;">
                <button type="submit" class="btn-outline-sced" style="width:100%;justify-content:center;font-size:13px;">
                    ⚙️ Aplicar Alteração
                </button>
            </form>
        </div>
        <?php endif; ?>

        <?php if(in_array('reabrir', $acoes)): ?>
        <div style="margin-bottom:12px;">
            <form method="POST" action="<?php echo e(route('documentos.reabrir', $documento)); ?>">
                <?php echo csrf_field(); ?>
                <input type="text" name="observacoes" class="form-input-sced"
                       placeholder="Motivo da reabertura (opcional)" style="margin-bottom:8px;font-size:13px;">
                <button type="submit" class="btn-outline-sced" style="width:100%;justify-content:center;font-size:13px;"
                        onclick="return confirm('Confirmar reabertura?')">
                    🔓 Reabrir Processo
                </button>
            </form>
        </div>
        <?php endif; ?>

        <?php if(in_array('desativar', $acoes)): ?>
        <div>
            <form method="POST" action="<?php echo e(route('documentos.desativar', $documento)); ?>">
                <?php echo csrf_field(); ?>
                <textarea name="motivo" class="form-input-sced" rows="2" required
                          placeholder="Motivo da desativação *" style="margin-bottom:8px;font-size:13px;"></textarea>
                <button type="submit" class="btn-vermelho" style="width:100%;justify-content:center;font-size:13px;"
                        onclick="return confirm('Desativar este processo?')">
                    🚫 Desativar Processo
                </button>
            </form>
        </div>
        <?php endif; ?>
    </div>
    <?php endif; ?>

</div>

</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
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
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
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
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
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
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\Controle-de-Entrada-de-Documentos-main\sced\resources\views/processos/show.blade.php ENDPATH**/ ?>