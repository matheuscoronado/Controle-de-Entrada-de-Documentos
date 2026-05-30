<?php $__env->startSection('title', 'Documento ' . $documento->numero_protocolo); ?>
<?php $__env->startSection('subtitle', $documento->assunto); ?>

<?php $__env->startSection('topbar-actions'); ?>
    <a href="<?php echo e(route('documentos.index')); ?>" class="btn-secondary-sced">
        ← Voltar à lista
    </a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<div class="row g-3">

    
    <div class="col-12 col-lg-8">

        
        <div class="card-sced card-body-sced" style="margin-bottom:16px;">
            <div style="display:flex; align-items:flex-start; justify-content:space-between; flex-wrap:wrap; gap:12px;">
                <div>
                    <div style="font-size:11px; color:var(--cinza-400); text-transform:uppercase; letter-spacing:1px; margin-bottom:4px;">
                        Número de Protocolo
                    </div>
                    <div class="protocolo-codigo" style="font-size:18px; padding:8px 14px;">
                        <?php echo e($documento->numero_protocolo); ?>

                    </div>
                </div>
                <span class="badge-status badge-<?php echo e($documento->status); ?>" style="font-size:14px; padding:8px 16px;">
                    <?php echo e(['recebido'=>'Recebido','em_analise'=>'Em Análise','encaminhado'=>'Encaminhado','finalizado'=>'Finalizado'][$documento->status]); ?>

                </span>
            </div>
        </div>

        
        <div class="card-sced card-body-sced" style="margin-bottom:16px;">
            <strong style="font-size:15px; color:var(--azul-escuro); display:block; margin-bottom:20px;">
                📋 Informações do Documento
            </strong>
            <div class="row g-3">
                <div class="col-6">
                    <div style="font-size:11px; color:var(--cinza-400); text-transform:uppercase; letter-spacing:0.6px; margin-bottom:4px;">
                        Tipo
                    </div>
                    <div style="font-size:14px; font-weight:500;"><?php echo e($documento->tipoDocumento->nome); ?></div>
                </div>
                <div class="col-6">
                    <div style="font-size:11px; color:var(--cinza-400); text-transform:uppercase; letter-spacing:0.6px; margin-bottom:4px;">
                        Data de Recebimento
                    </div>
                    <div style="font-size:14px; font-weight:500;">
                        <?php echo e(\Carbon\Carbon::parse($documento->data_recebimento)->format('d/m/Y')); ?>

                    </div>
                </div>
                <div class="col-6">
                    <div style="font-size:11px; color:var(--cinza-400); text-transform:uppercase; letter-spacing:0.6px; margin-bottom:4px;">
                        Remetente
                    </div>
                    <div style="font-size:14px; font-weight:500;"><?php echo e($documento->remetente); ?></div>
                </div>
                <div class="col-6">
                    <div style="font-size:11px; color:var(--cinza-400); text-transform:uppercase; letter-spacing:0.6px; margin-bottom:4px;">
                        Setor de Destino
                    </div>
                    <div style="font-size:14px; font-weight:500;"><?php echo e($documento->setor_destino); ?></div>
                </div>
                <div class="col-12">
                    <div style="font-size:11px; color:var(--cinza-400); text-transform:uppercase; letter-spacing:0.6px; margin-bottom:4px;">
                        Assunto
                    </div>
                    <div style="font-size:14px; font-weight:500;"><?php echo e($documento->assunto); ?></div>
                </div>
                <?php if($documento->descricao): ?>
                <div class="col-12">
                    <div style="font-size:11px; color:var(--cinza-400); text-transform:uppercase; letter-spacing:0.6px; margin-bottom:4px;">
                        Descrição
                    </div>
                    <div style="font-size:14px; color:var(--cinza-600); line-height:1.6;">
                        <?php echo e($documento->descricao); ?>

                    </div>
                </div>
                <?php endif; ?>
                <div class="col-12" style="padding-top:8px; border-top:1px solid var(--cinza-200);">
                    <div style="font-size:11px; color:var(--cinza-400);">
                        Registrado por <strong><?php echo e($documento->usuarioRegistro->nome); ?></strong>
                        em <?php echo e($documento->created_at->format('d/m/Y \à\s H:i')); ?>

                    </div>
                </div>
            </div>
        </div>

        
        <?php if($documento->anexos->count() > 0): ?>
        <div class="card-sced card-body-sced" style="margin-bottom:16px;">
            <strong style="font-size:15px; color:var(--azul-escuro); display:block; margin-bottom:16px;">
                📎 Arquivos Anexos
            </strong>
            <?php $__currentLoopData = $documento->anexos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $anexo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div style="display:flex; align-items:center; justify-content:space-between; padding:12px 16px; background:var(--cinza-100); border-radius:var(--radius-sm); margin-bottom:8px;">
                <div style="display:flex; align-items:center; gap:10px;">
                    <span style="font-size:20px;">📄</span>
                    <div>
                        <div style="font-size:13px; font-weight:500;"><?php echo e($anexo->nome_arquivo); ?></div>
                        <div style="font-size:11px; color:var(--cinza-400);">
                            <?php echo e(number_format($anexo->tamanho_bytes / 1024, 1)); ?> KB
                        </div>
                    </div>
                </div>
                <a href="<?php echo e(Storage::url($anexo->caminho_arquivo)); ?>"
                   target="_blank" class="btn-outline-sced" style="font-size:12px;">
                    ⬇ Baixar
                </a>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <?php endif; ?>

        
        <div class="card-sced card-body-sced">
            <strong style="font-size:15px; color:var(--azul-escuro); display:block; margin-bottom:20px;">
                🕐 Histórico de Movimentações
            </strong>
            <ul class="timeline">
                <?php $__currentLoopData = $documento->historicos->sortByDesc('data_hora'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $hist): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li class="timeline-item">
                    <div class="timeline-dot">
                        <?php echo e(['recebido'=>'📥','em_analise'=>'🔍','encaminhado'=>'↗️','finalizado'=>'✅'][$hist->status_novo] ?? '📋'); ?>

                    </div>
                    <div class="timeline-content">
                        <div style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:8px;">
                            <div>
                                <?php if($hist->status_anterior): ?>
                                    <span class="badge-status badge-<?php echo e($hist->status_anterior); ?>" style="font-size:11px;">
                                        <?php echo e(['recebido'=>'Recebido','em_analise'=>'Em Análise','encaminhado'=>'Encaminhado','finalizado'=>'Finalizado'][$hist->status_anterior]); ?>

                                    </span>
                                    <span style="font-size:12px; color:var(--cinza-400); margin:0 4px;">→</span>
                                <?php endif; ?>
                                <span class="badge-status badge-<?php echo e($hist->status_novo); ?>" style="font-size:11px;">
                                    <?php echo e(['recebido'=>'Recebido','em_analise'=>'Em Análise','encaminhado'=>'Encaminhado','finalizado'=>'Finalizado'][$hist->status_novo]); ?>

                                </span>
                            </div>
                            <div class="timeline-date">
                                <?php echo e(\Carbon\Carbon::parse($hist->data_hora)->format('d/m/Y H:i')); ?>

                            </div>
                        </div>
                        <div class="timeline-text" style="margin-top:6px;">
                            Por <strong><?php echo e($hist->usuario->nome); ?></strong>
                            <?php if($hist->observacoes): ?>
                                — <?php echo e($hist->observacoes); ?>

                            <?php endif; ?>
                        </div>
                    </div>
                </li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>

    </div>

    
    <div class="col-12 col-lg-4">
        <div class="card-sced card-body-sced" style="position:sticky; top:80px;">
            <strong style="font-size:15px; color:var(--azul-escuro); display:block; margin-bottom:16px;">
                🔄 Atualizar Status
            </strong>

            <?php if($documento->status === 'finalizado' && !auth()->user()->isAdmin()): ?>
                <div class="alert-sced alert-warning">
                    🔒 Apenas administradores podem alterar documentos finalizados.
                </div>
            <?php else: ?>
                <form method="POST" action="<?php echo e(route('documentos.status', $documento)); ?>">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PATCH'); ?>

                    <div class="form-group">
                        <label class="form-label-sced">Novo Status</label>
                        <select name="status" class="form-input-sced" required>
                            <option value="recebido"    <?php echo e($documento->status=='recebido'    ? 'selected' : ''); ?>>📥 Recebido</option>
                            <option value="em_analise"  <?php echo e($documento->status=='em_analise'  ? 'selected' : ''); ?>>🔍 Em Análise</option>
                            <option value="encaminhado" <?php echo e($documento->status=='encaminhado' ? 'selected' : ''); ?>>↗️ Encaminhado</option>
                            <option value="finalizado"  <?php echo e($documento->status=='finalizado'  ? 'selected' : ''); ?>>✅ Finalizado</option>
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
            <?php endif; ?>

            
            <div style="margin-top:24px; padding-top:20px; border-top:1px solid var(--cinza-200);">
                <div style="font-size:12px; color:var(--cinza-400); margin-bottom:12px; font-weight:600; text-transform:uppercase; letter-spacing:0.6px;">
                    Resumo
                </div>
                <div style="display:flex; flex-direction:column; gap:10px;">
                    <div style="display:flex; justify-content:space-between; font-size:13px;">
                        <span style="color:var(--cinza-600);">Movimentações</span>
                        <span style="font-weight:600;"><?php echo e($documento->historicos->count()); ?></span>
                    </div>
                    <div style="display:flex; justify-content:space-between; font-size:13px;">
                        <span style="color:var(--cinza-600);">Anexos</span>
                        <span style="font-weight:600;"><?php echo e($documento->anexos->count()); ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\Controle-de-Entrada-de-Documentos\sced\resources\views/documentos/show.blade.php ENDPATH**/ ?>