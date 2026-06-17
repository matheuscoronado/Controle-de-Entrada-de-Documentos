

<?php $__env->startSection('title', 'Cadastro de Documentos'); ?>
<?php $__env->startSection('subtitle', 'Gerencie os tipos de documento do sistema'); ?>

<?php $__env->startSection('topbar-actions'); ?>
    <a href="<?php echo e(route('documentos-tipo.create')); ?>" class="btn-primary-sced">
        ➕ Novo Documento
    </a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<style>
    .documentos-table {
        width: 100%;
        border-collapse: collapse;
    }
    .documentos-table thead th {
        background: var(--cinza-100);
        padding: 14px 16px;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--cinza-500);
        border-bottom: 1px solid var(--cinza-200);
    }
    .documentos-table tbody td {
        padding: 16px;
        border-bottom: 1px solid var(--cinza-100);
        font-size: 13px;
        vertical-align: middle;
    }
    .documentos-table tbody tr:hover {
        background: var(--cinza-100);
    }
    
    .tipo-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
    }
    .tipo-obrigatorio {
        background: #fef2f2;
        color: #dc2626;
    }
    .tipo-opcional {
        background: #f0f9ff;
        color: #0369a1;
    }
    
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
    }
    .status-ativo {
        background: #f0fdf4;
        color: #059669;
    }
    .status-inativo {
        background: #fef2f2;
        color: #dc2626;
    }
    
    .btn-edit {
        padding: 6px 14px;
        background: transparent;
        border: 1.5px solid var(--azul-claro);
        color: var(--azul-claro);
        border-radius: 8px;
        font-size: 12px;
        font-weight: 500;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }
    .btn-edit:hover {
        background: var(--azul-claro);
        color: white;
        text-decoration: none;
    }
    
    .documento-card-mobile {
        background: var(--branco);
        border-radius: 12px;
        border: 1px solid var(--cinza-200);
        padding: 16px;
        margin-bottom: 12px;
    }
    .documento-card-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 12px;
    }
    .documento-card-nome {
        font-weight: 700;
        font-size: 15px;
        color: var(--azul-escuro);
    }
    .documento-card-body {
        margin-bottom: 12px;
        padding: 10px 0;
        border-top: 1px solid var(--cinza-100);
        border-bottom: 1px solid var(--cinza-100);
    }
    .documento-card-footer {
        display: flex;
        justify-content: flex-end;
    }
</style>

<div class="card-sced d-none d-md-block">
    <div class="table-responsive">
        <table class="documentos-table">
            <thead>
                <tr>
                    <th style="width: 60px;">ID</th>
                    <th>Nome do Documento</th>
                    <th>Descrição</th>
                    <th>Tipo</th>
                    <th>Status</th>
                    <th style="text-align: center;">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $documentos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $doc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td style="color: var(--cinza-400);"><?php echo e($doc->id); ?></td>
                    <td>
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <div style="width: 36px; height: 36px; border-radius: 10px; background: var(--azul-claro); display: flex; align-items: center; justify-content: center; color: white;">📄</div>
                            <div>
                                <div style="font-weight: 600;"><?php echo e($doc->nome); ?></div>
                            </div>
                        </div>
                    </td>
                    <td style="max-width: 300px;">
                        <span style="font-size: 12px; color: var(--cinza-500);"><?php echo e(Str::limit($doc->descricao, 60)); ?></span>
                    </td>
                    <td>
                        <span class="tipo-badge <?php echo e($doc->tipo == 'obrigatorio' ? 'tipo-obrigatorio' : 'tipo-opcional'); ?>">
                            <?php echo e($doc->tipo == 'obrigatorio' ? '🔴 Obrigatório' : '🔵 Opcional'); ?>

                        </span>
                    </td>
                    <td>
                        <span class="status-badge <?php echo e($doc->status == 'ativo' ? 'status-ativo' : 'status-inativo'); ?>">
                            ● <?php echo e($doc->status == 'ativo' ? 'Ativo' : 'Inativo'); ?>

                        </span>
                    </td>
                    <td style="text-align: center;">
                        <a href="<?php echo e(route('documentos-tipo.edit', $doc)); ?>" class="btn-edit">✏️ Editar</a>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="6" class="text-center py-5">
                        <div class="text-muted">
                            <div class="fs-1 mb-2">📄</div>
                            <p>Nenhum documento cadastrado ainda.</p>
                            <a href="<?php echo e(route('documentos-tipo.create')); ?>" class="btn-primary-sced" style="display: inline-flex;">
                                Criar o primeiro documento →
                            </a>
                        </div>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="d-md-none">
    <?php $__empty_1 = true; $__currentLoopData = $documentos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $doc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
    <div class="documento-card-mobile">
        <div class="documento-card-header">
            <div class="documento-card-nome">📄 <?php echo e($doc->nome); ?></div>
        </div>
        <div class="documento-card-body">
            <div style="margin-bottom: 8px;">
                <span style="font-size: 11px; color: var(--cinza-400);">Descrição:</span>
                <div style="font-size: 12px;"><?php echo e(Str::limit($doc->descricao, 80)); ?></div>
            </div>
            <div style="margin-bottom: 8px;">
                <span class="tipo-badge <?php echo e($doc->tipo == 'obrigatorio' ? 'tipo-obrigatorio' : 'tipo-opcional'); ?>">
                    <?php echo e($doc->tipo == 'obrigatorio' ? '🔴 Obrigatório' : '🔵 Opcional'); ?>

                </span>
                <span class="status-badge <?php echo e($doc->status == 'ativo' ? 'status-ativo' : 'status-inativo'); ?>" style="margin-left: 8px;">
                    ● <?php echo e($doc->status == 'ativo' ? 'Ativo' : 'Inativo'); ?>

                </span>
            </div>
        </div>
        <div class="documento-card-footer">
            <a href="<?php echo e(route('documentos-tipo.edit', $doc)); ?>" class="btn-edit">✏️ Editar</a>
        </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
    <div class="card-sced text-center py-5">
        <div class="text-muted">
            <div class="fs-1 mb-2">📄</div>
            <p>Nenhum documento cadastrado ainda.</p>
            <a href="<?php echo e(route('documentos-tipo.create')); ?>" class="btn-primary-sced">Criar o primeiro documento →</a>
        </div>
    </div>
    <?php endif; ?>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\Controle-de-Entrada-de-Documentos\sced\resources\views/admin/documentos/index.blade.php ENDPATH**/ ?>