<?php $__env->startSection('title', 'Dashboard'); ?>
<?php $__env->startSection('subtitle', 'Visão geral do sistema'); ?>

<?php $__env->startSection('topbar-actions'); ?>
    <a href="<?php echo e(route('documentos.create')); ?>" class="btn-primary-sced">
        ➕ Novo Documento
    </a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>


<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-icon azul">📄</div>
            <div>
                <div class="stat-valor"><?php echo e($total); ?></div>
                <div class="stat-label">Total de Documentos</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-icon amarel">🔍</div>
            <div>
                <div class="stat-valor"><?php echo e($porStatus['em_analise'] ?? 0); ?></div>
                <div class="stat-label">Em Análise</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-icon ciano">↗️</div>
            <div>
                <div class="stat-valor"><?php echo e($porStatus['encaminhado'] ?? 0); ?></div>
                <div class="stat-label">Encaminhados</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-icon verde">✅</div>
            <div>
                <div class="stat-valor"><?php echo e($porStatus['finalizado'] ?? 0); ?></div>
                <div class="stat-label">Finalizados</div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    
    <div class="col-lg-8">
        <div class="card-sced">
            <div class="card-header-sced" style="padding-bottom:16px;">
                <strong style="font-size:15px; color:var(--azul-escuro);">📋 Documentos Recentes</strong>
                <a href="<?php echo e(route('documentos.index')); ?>" class="btn-outline-sced" style="font-size:12px;">
                    Ver todos →
                </a>
            </div>
            <div style="overflow-x:auto;">
                <table class="tabela-sced">
                    <thead>
                        <tr>
                            <th>Protocolo</th>
                            <th>Assunto</th>
                            <th>Remetente</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $recentes ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $doc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><span class="protocolo-codigo"><?php echo e($doc->numero_protocolo); ?></span></td>
                            <td style="max-width:200px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">
                                <?php echo e($doc->assunto); ?>

                            </td>
                            <td><?php echo e($doc->remetente); ?></td>
                            <td>
                                <span class="badge-status badge-<?php echo e($doc->status); ?>">
                                    <?php echo e(['recebido'=>'Recebido','em_analise'=>'Em Análise','encaminhado'=>'Encaminhado','finalizado'=>'Finalizado'][$doc->status]); ?>

                                </span>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="4" style="text-align:center; color:var(--cinza-400); padding:32px;">
                                Nenhum documento registrado ainda.
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    
    <div class="col-lg-4">
        <div class="card-sced card-body-sced" style="padding:24px;">
            <strong style="font-size:15px; color:var(--azul-escuro); display:block; margin-bottom:20px;">
                📊 Por Status
            </strong>

            <?php
                $statusConfig = [
                    'recebido'    => ['label' => 'Recebido',    'color' => '#2563eb'],
                    'em_analise'  => ['label' => 'Em Análise',  'color' => '#d97706'],
                    'encaminhado' => ['label' => 'Encaminhado', 'color' => '#0891b2'],
                    'finalizado'  => ['label' => 'Finalizado',  'color' => '#059669'],
                ];
                $totalLocal = max($total, 1);
            ?>

            <?php $__currentLoopData = $statusConfig; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $cfg): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php $qtd = $porStatus[$key] ?? 0; $pct = round(($qtd / $totalLocal) * 100); ?>
            <div style="margin-bottom:16px;">
                <div style="display:flex; justify-content:space-between; font-size:13px; margin-bottom:5px;">
                    <span style="color:var(--cinza-600); font-weight:500;"><?php echo e($cfg['label']); ?></span>
                    <span style="font-weight:700; color:var(--cinza-800);"><?php echo e($qtd); ?></span>
                </div>
                <div style="background:var(--cinza-200); border-radius:10px; height:8px; overflow:hidden;">
                    <div style="height:100%; width:<?php echo e($pct); ?>%; background:<?php echo e($cfg['color']); ?>; border-radius:10px; transition:width 0.5s ease;"></div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
// Atualiza o dashboard a cada 60 segundos
// setTimeout(() => location.reload(), 60000);
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\Controle-de-Entrada-de-Documentos\sced\resources\views/dashboard.blade.php ENDPATH**/ ?>