<?php $__env->startSection('title', 'Relatórios'); ?>
<?php $__env->startSection('subtitle', 'Geração de relatórios do sistema'); ?>

<?php $__env->startSection('content'); ?>
<div class="row g-4">
    <div class="col-lg-4">
        <div class="card-sced card-body-sced">
            <strong style="font-size:15px;color:var(--azul-escuro);display:block;margin-bottom:20px;">
                📊 Gerar Relatório
            </strong>

            <form method="POST" action="<?php echo e(route('relatorios.gerar')); ?>">
                <?php echo csrf_field(); ?>

                <div class="mb-3">
                    <label class="form-label-sced">Período — Data Inicial</label>
                    <input type="date" name="data_inicio" class="form-input-sced"
                           value="<?php echo e(date('Y-m-01')); ?>">
                </div>

                <div class="mb-3">
                    <label class="form-label-sced">Período — Data Final</label>
                    <input type="date" name="data_fim" class="form-input-sced"
                           value="<?php echo e(date('Y-m-d')); ?>">
                </div>

                <div class="mb-3">
                    <label class="form-label-sced">Serviço (Tipo)</label>
                    <select name="tipo_documento_id" class="form-input-sced">
                        <option value="">Todos os serviços</option>
                        <?php $__currentLoopData = $tipos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tipo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($tipo->id); ?>"><?php echo e($tipo->nome); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label-sced">Status</label>
                    
                    <select name="status" class="form-input-sced">
                        <option value="">Todos os status</option>
                        <option value="novo">🆕 Novo</option>
                        <option value="em_analise">🔍 Em Análise</option>
                        <option value="pendente">⏳ Pendente</option>
                        <option value="finalizado">✅ Finalizado</option>
                        <option value="desativado">🚫 Desativado</option>
                    </select>
                </div>

                <button type="submit" class="btn-primary-sced" style="width:100%;justify-content:center;">
                    📄 Gerar PDF
                </button>
            </form>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card-sced card-body-sced">
            <strong style="font-size:15px;color:var(--azul-escuro);display:block;margin-bottom:16px;">
                ℹ️ Sobre os Relatórios
            </strong>
            <p style="font-size:13px;color:var(--cinza-600);line-height:1.7;">
                Os relatórios são gerados em PDF e incluem todos os processos do período
                selecionado com o status e serviço escolhidos. Selecione os filtros
                desejados e clique em <strong>Gerar PDF</strong>.
            </p>
            <div style="margin-top:16px;padding:14px;background:var(--cinza-100);border-radius:var(--radius-sm);">
                <?php
                    $statusInfo = [
                        'novo'       => ['label'=>'Novo',        'icon'=>'🆕', 'color'=>'#2563eb'],
                        'em_analise' => ['label'=>'Em Análise',  'icon'=>'🔍', 'color'=>'#d97706'],
                        'pendente'   => ['label'=>'Pendente',    'icon'=>'⏳', 'color'=>'#92400e'],
                        'finalizado' => ['label'=>'Finalizado',  'icon'=>'✅', 'color'=>'#059669'],
                        'desativado' => ['label'=>'Desativado',  'icon'=>'🚫', 'color'=>'#64748b'],
                    ];
                ?>
                <div style="font-size:12px;font-weight:600;color:var(--cinza-400);text-transform:uppercase;letter-spacing:.8px;margin-bottom:10px;">
                    Legenda de Status
                </div>
                <div style="display:flex;flex-wrap:wrap;gap:8px;">
                    <?php $__currentLoopData = $statusInfo; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <span style="font-size:12px;font-weight:600;padding:4px 12px;border-radius:20px;background:#f1f5f9;color:<?php echo e($s['color']); ?>;">
                        <?php echo e($s['icon']); ?> <?php echo e($s['label']); ?>

                    </span>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
.mb-3 { margin-bottom: 14px; }
</style>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\Controle-de-Entrada-de-Documentos\sced\resources\views/relatorios/index.blade.php ENDPATH**/ ?>