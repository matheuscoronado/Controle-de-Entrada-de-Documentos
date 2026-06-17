

<?php $__env->startSection('title', 'Usuários'); ?>
<?php $__env->startSection('subtitle', 'Gerencie os usuários do sistema'); ?>

<?php $__env->startSection('topbar-actions'); ?>
    <a href="<?php echo e(route('usuarios.create')); ?>" class="btn-primary-sced">
        ➕ Novo Usuário
    </a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<style>
    /* Cards de estatísticas */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
        gap: 20px;
        margin-bottom: 32px;
    }
    .stat-card-user {
        background: var(--branco);
        border-radius: 16px;
        padding: 20px;
        border: 1px solid var(--cinza-200);
        transition: all 0.3s ease;
    }
    .stat-card-user:hover {
        transform: translateY(-2px);
        box-shadow: var(--sombra-hover);
    }
    .stat-card-user .stat-value {
        font-size: 28px;
        font-weight: 700;
        color: var(--azul-claro);
        line-height: 1.2;
    }
    .stat-card-user .stat-label {
        font-size: 12px;
        color: var(--cinza-400);
        margin-top: 6px;
    }
    
    /* Tabela */
    .usuarios-table {
        width: 100%;
        border-collapse: collapse;
    }
    .usuarios-table thead th {
        background: var(--cinza-100);
        padding: 14px 16px;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--cinza-500);
        border-bottom: 1px solid var(--cinza-200);
    }
    .usuarios-table tbody td {
        padding: 16px;
        border-bottom: 1px solid var(--cinza-100);
        font-size: 13px;
        vertical-align: middle;
    }
    .usuarios-table tbody tr:hover {
        background: var(--cinza-100);
    }
    
    /* Avatar */
    .user-avatar-table {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: var(--azul-claro);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 14px;
        flex-shrink: 0;
    }
    
    /* Badges */
    .perfil-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
    }
    .perfil-admin {
        background: #e0e7ff;
        color: #3730a3;
    }
    .perfil-n3 {
        background: #d1fae5;
        color: #065f46;
    }
    .perfil-operador {
        background: #f1f5f9;
        color: #475569;
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
    
    .cargo-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
    }
    .cargo-N1 { background: #e0e7ff; color: #3730a3; }
    .cargo-N2 { background: #fef3c7; color: #92400e; }
    .cargo-N3 { background: #d1fae5; color: #065f46; }
    
    .btn-edit-user {
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
    .btn-edit-user:hover {
        background: var(--azul-claro);
        color: white;
        text-decoration: none;
    }
    
    /* Card mobile */
    .user-card-mobile {
        background: var(--branco);
        border-radius: 12px;
        border: 1px solid var(--cinza-200);
        padding: 16px;
        margin-bottom: 12px;
    }
    .user-card-header {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 12px;
    }
    .user-card-info {
        flex: 1;
    }
    .user-card-nome {
        font-weight: 700;
        font-size: 15px;
        color: var(--azul-escuro);
    }
    .user-card-email {
        font-size: 11px;
        color: var(--cinza-400);
    }
    .user-card-body {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 12px;
        padding: 10px 0;
        border-top: 1px solid var(--cinza-100);
        border-bottom: 1px solid var(--cinza-100);
    }
    .user-card-footer {
        display: flex;
        justify-content: flex-end;
    }
</style>


<div class="stats-grid">
    <div class="stat-card-user">
        <div class="stat-value"><?php echo e($usuarios->count()); ?></div>
        <div class="stat-label">Total de Usuários</div>
    </div>
    <div class="stat-card-user">
        <div class="stat-value"><?php echo e($usuarios->where('status', 'ativo')->count()); ?></div>
        <div class="stat-label">Ativos</div>
    </div>
    <div class="stat-card-user">
        <div class="stat-value"><?php echo e($usuarios->where('perfil', 'administrador')->count()); ?></div>
        <div class="stat-label">Administradores</div>
    </div>
    <div class="stat-card-user">
        <div class="stat-value"><?php echo e($usuarios->where('perfil', 'operador')->count()); ?></div>
        <div class="stat-label">Operadores</div>
    </div>
</div>


<div class="card-sced d-none d-md-block">
    <div class="table-responsive">
        <table class="usuarios-table">
            <thead>
                <tr>
                    <th>Usuário</th>
                    <th>Departamento</th>
                    <th>Cargo</th>
                    <th>Perfil</th>
                    <th>Status</th>
                    <th>Cadastro</th>
                    <th class="text-center">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $usuarios; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $usuario): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td>
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <div class="user-avatar-table">
                                <?php echo e(strtoupper(substr($usuario->nome, 0, 1))); ?>

                            </div>
                            <div>
                                <div style="font-weight: 600;"><?php echo e($usuario->nome); ?></div>
                                <div style="font-size: 11px; color: var(--cinza-400);"><?php echo e($usuario->email); ?></div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span style="font-size: 13px; color: var(--azul-escuro); font-weight: 500;">
                            <?php echo e($usuario->departamentoRelacionado->nome ?? '—'); ?>

                        </span>
                    </td>
                    <td>
                        <span class="cargo-badge cargo-<?php echo e($usuario->cargo); ?>">
                            <?php echo e($usuario->cargo ?? '—'); ?>

                        </span>
                    </td>
                    <td>
                        <?php if($usuario->perfil === 'administrador'): ?>
                            <span class="perfil-badge perfil-admin">👑 Administrador</span>
                        <?php elseif($usuario->perfil === 'n3'): ?>
                            <span class="perfil-badge perfil-n3">⭐ Supervisor N3</span>
                        <?php else: ?>
                            <span class="perfil-badge perfil-operador">👤 Operador</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if($usuario->status === 'ativo'): ?>
                            <span class="status-badge status-ativo">● Ativo</span>
                        <?php else: ?>
                            <span class="status-badge status-inativo">● Inativo</span>
                        <?php endif; ?>
                    </td>
                    <td style="font-size: 12px; color: var(--cinza-400);">
                        <?php echo e($usuario->created_at->format('d/m/Y')); ?>

                    </td>
                    <td class="text-center">
                        <?php if($usuario->id !== auth()->id()): ?>
                            <a href="<?php echo e(route('usuarios.edit', $usuario)); ?>" class="btn-edit-user">
                                ✏️ Editar
                            </a>
                        <?php else: ?>
                            <span style="font-size: 12px; color: var(--cinza-400);">— você —</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="7" class="text-center py-5">
                        <div class="text-muted">
                            <div class="fs-1 mb-2">👥</div>
                            <p>Nenhum usuário cadastrado ainda.</p>
                            <a href="<?php echo e(route('usuarios.create')); ?>" class="btn-primary-sced" style="display: inline-flex;">
                                Criar o primeiro usuário →
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
    <?php $__empty_1 = true; $__currentLoopData = $usuarios; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $usuario): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
    <div class="user-card-mobile">
        <div class="user-card-header">
            <div class="user-avatar-table">
                <?php echo e(strtoupper(substr($usuario->nome, 0, 1))); ?>

            </div>
            <div class="user-card-info">
                <div class="user-card-nome"><?php echo e($usuario->nome); ?></div>
                <div class="user-card-email"><?php echo e($usuario->email); ?></div>
            </div>
        </div>
        <div class="user-card-body">
            <div>
                <span style="font-size: 10px; color: var(--cinza-400);">Departamento</span>
                <div style="font-size: 13px; font-weight: 500;"><?php echo e($usuario->departamentoRelacionado->nome ?? '—'); ?></div>
            </div>
            <div>
                <span style="font-size: 10px; color: var(--cinza-400);">Cargo</span>
                <div><span class="cargo-badge cargo-<?php echo e($usuario->cargo); ?>"><?php echo e($usuario->cargo ?? '—'); ?></span></div>
            </div>
            <div>
                <span style="font-size: 10px; color: var(--cinza-400);">Perfil</span>
                <div>
                    <?php if($usuario->perfil === 'administrador'): ?>
                        <span class="perfil-badge perfil-admin">👑 Admin</span>
                    <?php elseif($usuario->perfil === 'n3'): ?>
                        <span class="perfil-badge perfil-n3">⭐ N3</span>
                    <?php else: ?>
                        <span class="perfil-badge perfil-operador">👤 Operador</span>
                    <?php endif; ?>
                </div>
            </div>
            <div>
                <span style="font-size: 10px; color: var(--cinza-400);">Status</span>
                <div>
                    <?php if($usuario->status === 'ativo'): ?>
                        <span class="status-badge status-ativo">Ativo</span>
                    <?php else: ?>
                        <span class="status-badge status-inativo">Inativo</span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="user-card-footer">
            <?php if($usuario->id !== auth()->id()): ?>
                <a href="<?php echo e(route('usuarios.edit', $usuario)); ?>" class="btn-edit-user">
                    ✏️ Editar
                </a>
            <?php else: ?>
                <span style="font-size: 12px; color: var(--cinza-400);">— você —</span>
            <?php endif; ?>
        </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
    <div class="card-sced text-center py-5">
        <div class="text-muted">
            <div class="fs-1 mb-2">👥</div>
            <p>Nenhum usuário cadastrado ainda.</p>
            <a href="<?php echo e(route('usuarios.create')); ?>" class="btn-primary-sced">
                Criar o primeiro usuário →
            </a>
        </div>
    </div>
    <?php endif; ?>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\Controle-de-Entrada-de-Documentos\sced\resources\views/usuarios/index.blade.php ENDPATH**/ ?>