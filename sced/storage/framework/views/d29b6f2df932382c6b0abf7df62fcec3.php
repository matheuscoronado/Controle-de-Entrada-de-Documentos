<?php $__env->startSection('title', 'Tipos de Documento'); ?>
<?php $__env->startSection('subtitle', 'Parametrização de documentos e serviços'); ?>

<?php $__env->startSection('topbar-actions'); ?>
    <a href="<?php echo e(route('tipos.create')); ?>" class="btn-primary-sced">
        ➕ Novo Tipo
    </a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>


<div class="row g-3 mb-4">
    <div class="col-sm-6 col-lg-3">
        <div class="card-sced" style="padding:18px 20px;">
            <div style="font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:1.2px;color:var(--cinza-400);margin-bottom:6px;">Total de Tipos</div>
            <div style="font-size:28px;font-weight:700;color:var(--azul-claro);"><?php echo e($tipos->count()); ?></div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card-sced" style="padding:18px 20px;">
            <div style="font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:1.2px;color:var(--cinza-400);margin-bottom:6px;">Obrigatórios</div>
            <div style="font-size:28px;font-weight:700;color:var(--vermelho);"><?php echo e($tipos->where('obrigatoriedade','obrigatorio')->count()); ?></div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card-sced" style="padding:18px 20px;">
            <div style="font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:1.2px;color:var(--cinza-400);margin-bottom:6px;">Ativos</div>
            <div style="font-size:28px;font-weight:700;color:var(--verde);"><?php echo e($tipos->where('status','ativo')->count()); ?></div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card-sced" style="padding:18px 20px;">
            <div style="font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:1.2px;color:var(--cinza-400);margin-bottom:6px;">Com SLA</div>
            <div style="font-size:28px;font-weight:700;color:var(--ciano);"><?php echo e($tipos->whereNotNull('sla_horas')->count()); ?></div>
        </div>
    </div>
</div>

<div class="card-sced">
    <div style="overflow-x:auto;">
        <table class="tabela-sced">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Tipo de Documento</th>
                    <th>Exigência</th>
                    <th>Destino</th>
                    <th>Responsável</th>
                    <th>SLA</th>
                    <th>Docs.</th>
                    <th>Status</th>
                    <th style="text-align:center;">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $tipos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tipo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td style="color:var(--cinza-400);font-size:12px;"><?php echo e($tipo->id); ?></td>

                    <td>
                        <div style="font-weight:600;">🏷️ <?php echo e($tipo->nome); ?></div>
                        <?php if($tipo->descricao): ?>
                            <div style="font-size:12px;color:var(--cinza-400);margin-top:2px;"><?php echo e(Str::limit($tipo->descricao, 60)); ?></div>
                        <?php endif; ?>
                    </td>

                    <td>
                        <?php if($tipo->obrigatoriedade === 'obrigatorio'): ?>
                            <span style="background:#fef2f2;color:#dc2626;padding:3px 10px;border-radius:20px;font-size:12px;font-weight:600;">● Obrigatório</span>
                        <?php else: ?>
                            <span style="background:#f0f9ff;color:#0369a1;padding:3px 10px;border-radius:20px;font-size:12px;font-weight:600;">○ Opcional</span>
                        <?php endif; ?>
                    </td>

                    <td style="font-size:13px;">
                        <?php if($tipo->departamentoDestino): ?>
                            <span style="display:flex;align-items:center;gap:4px;">
                                <span>🏢</span> <?php echo e($tipo->departamentoDestino->nome); ?>

                            </span>
                        <?php else: ?>
                            <span style="color:var(--cinza-400);">—</span>
                        <?php endif; ?>
                    </td>

                    <td>
                        <?php if($tipo->cargo_responsavel): ?>
                            <span style="background:var(--cinza-200);color:var(--cinza-800);padding:3px 10px;border-radius:6px;font-size:12px;font-weight:700;font-family:'JetBrains Mono',monospace;">
                                <?php echo e($tipo->cargo_responsavel); ?>

                            </span>
                        <?php else: ?>
                            <span style="color:var(--cinza-400);">—</span>
                        <?php endif; ?>
                    </td>

                    <td style="font-size:13px;font-weight:600;color:var(--ciano);">
                        <?php echo e($tipo->label_sla); ?>

                    </td>

                    <td>
                        <span style="font-weight:700;color:var(--azul-claro);"><?php echo e($tipo->documentos_count); ?></span>
                        <span style="font-size:12px;color:var(--cinza-400);"> doc(s)</span>
                    </td>

                    <td>
                        <?php if($tipo->status === 'ativo'): ?>
                            <span style="background:#f0fdf4;color:#059669;padding:3px 10px;border-radius:20px;font-size:12px;font-weight:600;">● Ativo</span>
                        <?php else: ?>
                            <span style="background:#fef2f2;color:#dc2626;padding:3px 10px;border-radius:20px;font-size:12px;font-weight:600;">● Inativo</span>
                        <?php endif; ?>
                    </td>

                    <td style="text-align:center;">
                        <a href="<?php echo e(route('tipos.edit', $tipo)); ?>" class="btn-outline-sced">✏️ Editar</a>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="9" style="text-align:center;padding:48px;color:var(--cinza-400);">
                        <div style="font-size:32px;margin-bottom:8px;">🏷️</div>
                        Nenhum tipo cadastrado ainda. Crie o primeiro!
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\Controle-de-Entrada-de-Documentos\sced\resources\views/admin/tipos/index.blade.php ENDPATH**/ ?>