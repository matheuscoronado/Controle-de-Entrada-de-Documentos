

<?php $__env->startSection('title', 'Departamentos'); ?>
<?php $__env->startSection('subtitle', 'Gerencie os departamentos do sistema'); ?>

<?php $__env->startSection('topbar-actions'); ?>
    <button type="button" class="btn-primary-sced" data-bs-toggle="modal" data-bs-target="#modalNovoDepartamento">
        ➕ Novo Departamento
    </button>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<style>
    /* Tabela */
    .departamentos-table {
        width: 100%;
        border-collapse: collapse;
    }
    .departamentos-table thead th {
        background: var(--cinza-100);
        padding: 14px 16px;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--cinza-500);
        border-bottom: 1px solid var(--cinza-200);
    }
    .departamentos-table tbody td {
        padding: 16px;
        border-bottom: 1px solid var(--cinza-100);
        font-size: 13px;
        vertical-align: middle;
    }
    .departamentos-table tbody tr:hover {
        background: var(--cinza-100);
    }
    
    /* Badge de usuários */
    .users-count {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        background: #eff6ff;
        color: #2563eb;
    }
    
    /* Botão excluir */
    .btn-delete {
        padding: 6px 14px;
        background: transparent;
        border: 1.5px solid #ef4444;
        color: #ef4444;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 500;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }
    .btn-delete:hover {
        background: #ef4444;
        color: white;
        text-decoration: none;
    }
    .btn-delete:disabled {
        opacity: 0.5;
        cursor: not-allowed;
        background: transparent;
        color: #ef4444;
    }
    
    /* Card mobile */
    .dept-card-mobile {
        background: var(--branco);
        border-radius: 12px;
        border: 1px solid var(--cinza-200);
        padding: 16px;
        margin-bottom: 12px;
    }
    .dept-card-header {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 12px;
    }
    .dept-icon {
        width: 40px;
        height: 40px;
        border-radius: 12px;
        background: var(--azul-claro);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
    }
    .dept-name {
        font-weight: 700;
        font-size: 16px;
        color: var(--azul-escuro);
    }
    .dept-card-body {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 0;
        border-top: 1px solid var(--cinza-100);
        border-bottom: 1px solid var(--cinza-100);
        margin-bottom: 12px;
    }
    .dept-card-footer {
        display: flex;
        justify-content: flex-end;
    }
    
    /* Modal */
    .modal-custom .modal-content {
        border-radius: 16px;
        border: none;
    }
    .modal-header-custom {
        background: linear-gradient(135deg, var(--azul-escuro) 0%, var(--azul-medio) 100%);
        color: white;
        border-radius: 16px 16px 0 0;
        padding: 20px 24px;
    }
    .modal-body-custom {
        padding: 24px;
    }
    .modal-footer-custom {
        padding: 16px 24px;
        border-top: 1px solid var(--cinza-200);
    }
    .form-error {
        font-size: 12px;
        color: var(--vermelho);
        margin-top: 4px;
    }
    .helper-text {
        font-size: 11px;
        color: var(--cinza-400);
    }
    .btn-close-white {
        filter: brightness(0) invert(1);
    }
</style>


<div class="card-sced d-none d-md-block">
    <div class="table-responsive">
        <table class="departamentos-table">
            <thead>
                <tr>
                    <th style="width: 60px;">ID</th>
                    <th>Departamento</th>
                    <th>Usuários</th>
                    <th>Data de Criação</th>
                    <th style="text-align: center;">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $departamentos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $depto): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td style="color: var(--cinza-400); font-weight: 500;"><?php echo e($depto->id); ?></td>
                    <td>
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <div style="width: 36px; height: 36px; border-radius: 10px; background: var(--azul-claro); display: flex; align-items: center; justify-content: center; color: white;">🏢</div>
                            <div>
                                <div style="font-weight: 600;"><?php echo e($depto->nome); ?></div>
                                <div style="font-size: 11px; color: var(--cinza-400);">Setor Organizacional</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="users-count">👥 <?php echo e($depto->usuarios_count ?? 0); ?> usuário(s)</span>
                    </td>
                    <td style="font-size: 12px; color: var(--cinza-400);">
                        <?php echo e($depto->created_at->format('d/m/Y')); ?>

                    </td>
                    <td style="text-align: center;">
                        <?php if(($depto->usuarios_count ?? 0) == 0): ?>
                            <form action="<?php echo e(route('departamentos.destroy', $depto)); ?>" method="POST" 
                                  onsubmit="return confirm('Deseja realmente excluir este departamento?')" style="display: inline;">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="btn-delete">
                                    🗑️ Excluir
                                </button>
                            </form>
                        <?php else: ?>
                            <button type="button" class="btn-delete" disabled title="Não é possível excluir departamento com usuários vinculados">
                                🔒 Bloqueado
                            </button>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="5" class="text-center py-5">
                        <div class="text-muted">
                            <div class="fs-1 mb-2">🏢</div>
                            <p>Nenhum departamento cadastrado ainda.</p>
                            <button type="button" class="btn-primary-sced" data-bs-toggle="modal" data-bs-target="#modalNovoDepartamento">
                                Criar o primeiro departamento →
                            </button>
                        </div>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>


<div class="d-md-none">
    <?php $__empty_1 = true; $__currentLoopData = $departamentos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $depto): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
    <div class="dept-card-mobile">
        <div class="dept-card-header">
            <div class="dept-icon">🏢</div>
            <div class="dept-name"><?php echo e($depto->nome); ?></div>
        </div>
        <div class="dept-card-body">
            <div>
                <span style="font-size: 11px; color: var(--cinza-400);">Usuários</span>
                <div class="users-count" style="margin-top: 4px;">👥 <?php echo e($depto->usuarios_count ?? 0); ?></div>
            </div>
            <div>
                <span style="font-size: 11px; color: var(--cinza-400);">Criado em</span>
                <div style="font-size: 13px;"><?php echo e($depto->created_at->format('d/m/Y')); ?></div>
            </div>
        </div>
        <div class="dept-card-footer">
            <?php if(($depto->usuarios_count ?? 0) == 0): ?>
                <form action="<?php echo e(route('departamentos.destroy', $depto)); ?>" method="POST" 
                      onsubmit="return confirm('Deseja realmente excluir este departamento?')">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('DELETE'); ?>
                    <button type="submit" class="btn-delete">🗑️ Excluir</button>
                </form>
            <?php else: ?>
                <button type="button" class="btn-delete" disabled>🔒 Bloqueado</button>
            <?php endif; ?>
        </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
    <div class="card-sced text-center py-5">
        <div class="text-muted">
            <div class="fs-1 mb-2">🏢</div>
            <p>Nenhum departamento cadastrado ainda.</p>
            <button type="button" class="btn-primary-sced" data-bs-toggle="modal" data-bs-target="#modalNovoDepartamento">
                Criar o primeiro departamento →
            </button>
        </div>
    </div>
    <?php endif; ?>
</div>


<div class="modal fade modal-custom" id="modalNovoDepartamento" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header-custom">
                <h5 class="modal-title">🏢 Novo Departamento</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="<?php echo e(route('departamentos.store')); ?>">
                <?php echo csrf_field(); ?>
                <div class="modal-body-custom">
                    <div class="mb-3">
                        <label class="form-label-sced">Nome do Departamento <span class="text-danger">*</span></label>
                        <input type="text" name="nome" class="form-input-sced <?php $__errorArgs = ['nome'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               placeholder="Ex: Recursos Humanos, Tecnologia da Informação..." required autofocus>
                        <?php $__errorArgs = ['nome'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="form-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        <div class="helper-text mt-2">Digite o nome do novo departamento/setor.</div>
                    </div>
                </div>
                <div class="modal-footer-custom">
                    <button type="button" class="btn-secondary-sced" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn-primary-sced">💾 Salvar Departamento</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    <?php if(session('success')): ?>
        var modal = bootstrap.Modal.getInstance(document.getElementById('modalNovoDepartamento'));
        if (modal) modal.hide();
    <?php endif; ?>
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\Controle-de-Entrada-de-Documentos\sced\resources\views/departamentos/index.blade.php ENDPATH**/ ?>