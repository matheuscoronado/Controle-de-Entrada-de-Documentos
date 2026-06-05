<?php $__env->startSection('title', 'Logs de Auditoria'); ?>
<?php $__env->startSection('subtitle', 'Rastreamento completo de ações no sistema'); ?>

<?php $__env->startSection('content'); ?>


<div class="card-sced mb-4">
    <div style="font-size:13px;font-weight:700;text-transform:uppercase;letter-spacing:1.2px;color:var(--cinza-400);margin-bottom:16px;">
        🔍 Filtros
    </div>
    <form method="GET" action="<?php echo e(route('logs.index')); ?>">
        <div class="row g-3 align-items-end">

            <div class="col-sm-6 col-lg-3">
                <label class="form-label-sced">Usuário</label>
                <select name="usuario_id" class="form-control-sced">
                    <option value="">Todos</option>
                    <?php $__currentLoopData = $usuarios; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($u->id); ?>" <?php echo e(request('usuario_id') == $u->id ? 'selected' : ''); ?>>
                            <?php echo e($u->nome); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            <div class="col-sm-6 col-lg-2">
                <label class="form-label-sced">Módulo</label>
                <select name="modulo" class="form-control-sced">
                    <option value="">Todos</option>
                    <?php $__currentLoopData = $modulos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mod): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($mod); ?>" <?php echo e(request('modulo') === $mod ? 'selected' : ''); ?>>
                            <?php echo e(ucfirst($mod)); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            <div class="col-sm-6 col-lg-2">
                <label class="form-label-sced">Ação</label>
                <input type="text" name="acao" class="form-control-sced"
                       placeholder="Ex: ATUALIZAR..." value="<?php echo e(request('acao')); ?>">
            </div>

            <div class="col-sm-6 col-lg-2">
                <label class="form-label-sced">Data Início</label>
                <input type="date" name="data_inicio" class="form-control-sced"
                       value="<?php echo e(request('data_inicio')); ?>">
            </div>

            <div class="col-sm-6 col-lg-2">
                <label class="form-label-sced">Data Fim</label>
                <input type="date" name="data_fim" class="form-control-sced"
                       value="<?php echo e(request('data_fim')); ?>">
            </div>

            <div class="col-sm-6 col-lg-1">
                <button type="submit" class="btn-primary-sced" style="width:100%;">Filtrar</button>
            </div>

        </div>
    </form>
</div>


<div class="card-sced">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;">
        <div style="font-size:13px;color:var(--cinza-400);">
            Exibindo <strong style="color:var(--cinza-800);"><?php echo e($logs->total()); ?></strong> registros
        </div>
        <?php if(request()->hasAny(['usuario_id','modulo','acao','data_inicio','data_fim'])): ?>
            <a href="<?php echo e(route('logs.index')); ?>" style="font-size:13px;color:var(--vermelho);">✕ Limpar filtros</a>
        <?php endif; ?>
    </div>

    <div style="overflow-x:auto;">
        <table class="tabela-sced">
            <thead>
                <tr>
                    <th style="width:140px;">Data / Hora</th>
                    <th>Usuário</th>
                    <th>Módulo</th>
                    <th>Ação</th>
                    <th>Status Anterior</th>
                    <th>Novo Status</th>
                    <th>Descrição</th>
                    <th>IP</th>
                    <th style="text-align:center;">Det.</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $logs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td style="font-size:12px;font-family:'JetBrains Mono',monospace;white-space:nowrap;color:var(--cinza-600);">
                        <?php echo e($log->data_hora->format('d/m/Y')); ?><br>
                        <span style="color:var(--cinza-400);"><?php echo e($log->data_hora->format('H:i:s')); ?></span>
                    </td>

                    <td>
                        <div style="font-weight:600;font-size:14px;"><?php echo e($log->usuario->nome ?? '—'); ?></div>
                        <div style="font-size:11px;color:var(--cinza-400);"><?php echo e($log->usuario->label_perfil ?? ''); ?></div>
                    </td>

                    <td>
                        <?php if($log->modulo): ?>
                            <span style="background:var(--cinza-200);padding:2px 8px;border-radius:6px;font-size:12px;font-weight:600;">
                                <?php echo e(ucfirst($log->modulo)); ?>

                            </span>
                        <?php else: ?>
                            <span style="color:var(--cinza-400);">—</span>
                        <?php endif; ?>
                    </td>

                    <td>
                        <span style="font-family:'JetBrains Mono',monospace;font-size:12px;color:var(--azul-claro);font-weight:600;">
                            <?php echo e($log->acao); ?>

                        </span>
                    </td>

                    <td style="font-size:13px;">
                        <?php if($log->status_anterior): ?>
                            <span style="background:#fef3c7;color:#92400e;padding:2px 8px;border-radius:6px;font-size:12px;">
                                <?php echo e($log->status_anterior); ?>

                            </span>
                        <?php else: ?>
                            <span style="color:var(--cinza-400);">—</span>
                        <?php endif; ?>
                    </td>

                    <td style="font-size:13px;">
                        <?php if($log->status_novo): ?>
                            <span style="background:#d1fae5;color:#065f46;padding:2px 8px;border-radius:6px;font-size:12px;">
                                <?php echo e($log->status_novo); ?>

                            </span>
                        <?php else: ?>
                            <span style="color:var(--cinza-400);">—</span>
                        <?php endif; ?>
                    </td>

                    <td style="font-size:13px;max-width:280px;">
                        <span style="color:var(--cinza-600);">
                            <?php echo e(Str::limit($log->descricao_legivel ?? '—', 80)); ?>

                        </span>
                        <?php if($log->uploads_realizados && count($log->uploads_realizados) > 0): ?>
                            <div style="margin-top:3px;">
                                <span style="background:#eff6ff;color:#1d4ed8;padding:2px 7px;border-radius:6px;font-size:11px;font-weight:600;">
                                    📎 <?php echo e(count($log->uploads_realizados)); ?> arquivo(s)
                                </span>
                            </div>
                        <?php endif; ?>
                    </td>

                    <td style="font-size:12px;font-family:'JetBrains Mono',monospace;color:var(--cinza-400);">
                        <?php echo e($log->ip_origem ?? '—'); ?>

                    </td>

                    <td style="text-align:center;">
                        <a href="<?php echo e(route('logs.show', $log)); ?>" class="btn-outline-sced" style="padding:4px 10px;font-size:12px;">
                            🔎
                        </a>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="9" style="text-align:center;padding:48px;color:var(--cinza-400);">
                        <div style="font-size:32px;margin-bottom:8px;">📋</div>
                        Nenhum registro encontrado para os filtros selecionados.
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    
    <?php if($logs->hasPages()): ?>
    <div style="margin-top:20px;display:flex;justify-content:center;">
        <?php echo e($logs->links()); ?>

    </div>
    <?php endif; ?>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
.form-label-sced { font-size:13px;font-weight:600;color:var(--cinza-600);margin-bottom:6px;display:block; }
.form-control-sced { width:100%;padding:9px 12px;border:1.5px solid var(--cinza-200);border-radius:var(--radius-sm);font-size:13px;font-family:'Sora',sans-serif;background:var(--branco);color:var(--cinza-800);transition:var(--transicao);outline:none; }
.form-control-sced:focus { border-color:var(--azul-claro);box-shadow:0 0 0 3px rgba(37,99,235,0.12); }
</style>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\Controle-de-Entrada-de-Documentos\sced\resources\views/admin/logs/index.blade.php ENDPATH**/ ?>