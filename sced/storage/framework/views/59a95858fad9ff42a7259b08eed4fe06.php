<?php $__env->startSection('title', 'Tipos de Documento'); ?>
<?php $__env->startSection('subtitle', 'Categorias de documentos aceitos no sistema'); ?>

<?php $__env->startSection('topbar-actions'); ?>
    <a href="<?php echo e(route('tipos.create')); ?>" class="btn-primary-sced">
        ➕ Novo Tipo
    </a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="card-sced">
    <div style="overflow-x:auto;">
        <table class="tabela-sced">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nome</th>
                    <th>Descrição</th>
                    <th>Documentos</th>
                    <th>Status</th>
                    <th style="text-align:center;">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $tipos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tipo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td style="color:var(--cinza-400); font-size:13px;"><?php echo e($tipo->id); ?></td>
                    <td style="font-weight:600;">🏷️ <?php echo e($tipo->nome); ?></td>
                    <td style="color:var(--cinza-600); font-size:13px;">
                        <?php echo e($tipo->descricao ?? '—'); ?>

                    </td>
                    <td>
                        <span style="font-weight:700; color:var(--azul-claro);">
                            <?php echo e($tipo->documentos->count()); ?>

                        </span>
                        <span style="font-size:12px; color:var(--cinza-400);"> doc(s)</span>
                    </td>
                    <td>
                        <?php if($tipo->status === 'ativo'): ?>
                            <span style="background:#f0fdf4;color:#059669;padding:3px 10px;border-radius:20px;font-size:12px;font-weight:600;">● Ativo</span>
                        <?php else: ?>
                            <span style="background:#fef2f2;color:#dc2626;padding:3px 10px;border-radius:20px;font-size:12px;font-weight:600;">● Inativo</span>
                        <?php endif; ?>
                    </td>
                    <td style="text-align:center;">
                        <a href="<?php echo e(route('tipos.edit', $tipo)); ?>" class="btn-outline-sced">
                            ✏️ Editar
                        </a>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="6" style="text-align:center; padding:48px; color:var(--cinza-400);">
                        <div style="font-size:32px; margin-bottom:8px;">🏷️</div>
                        Nenhum tipo cadastrado. Crie o primeiro!
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php $__env->stopSection(); ?>




<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\Controle-de-Entrada-de-Documentos\sced\resources\views/tipos/index.blade.php ENDPATH**/ ?>