<?php $__env->startSection('title', 'Usuários'); ?>
<?php $__env->startSection('subtitle', 'Gerenciamento de usuários do sistema'); ?>

<?php $__env->startSection('topbar-actions'); ?>
    <a href="<?php echo e(route('usuarios.create')); ?>" class="btn-primary-sced">
        ➕ Novo Usuário
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
                    <th>Departamento</th>
                    <th>Cargo</th>
                    <th>Perfil</th>
                    <th>Status</th>
                    <th>Cadastrado em</th>
                    <th style="text-align:center;">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $usuarios; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $usuario): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td style="color:var(--cinza-400); font-size:13px;"><?php echo e($usuario->id); ?></td>
                    <td>
                        <div style="display:flex; align-items:center; gap:10px;">
                            <div style="width:32px;height:32px;border-radius:50%;background:var(--azul-claro);color:white;display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:700;">
                                <?php echo e(strtoupper(substr($usuario->nome, 0, 1))); ?>

                            </div>
                            <div style="display:flex; flex-direction:column;">
                                <span style="font-weight:500;"><?php echo e($usuario->nome); ?></span>
                                <span style="font-size:11px; color:var(--cinza-500);"><?php echo e($usuario->email); ?></span>
                            </div>
                        </div>
                    </td>
                    
                    <td>
                        <span style="font-size:13px; color:var(--azul-escuro); font-weight:500;">
                            <?php echo e($usuario->departamentoRelacionado->nome ?? '—'); ?>

                        </span>
                    </td>
                    <td>
                        <span style="background:var(--cinza-100); color:var(--azul-escuro); padding:2px 8px; border-radius:4px; font-size:11px; font-weight:700; border:1px solid var(--cinza-200);">
                            <?php echo e($usuario->cargo ?? '—'); ?>

                        </span>
                    </td>
                    <td>
                        <?php if($usuario->perfil === 'administrador'): ?>
                            <span style="background:#eff6ff;color:#2563eb;padding:3px 10px;border-radius:20px;font-size:12px;font-weight:600;">
                                👑 Admin
                            </span>
                        <?php else: ?>
                            <span style="background:var(--cinza-100);color:var(--cinza-600);padding:3px 10px;border-radius:20px;font-size:12px;font-weight:600;">
                                👤 Operador
                            </span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if($usuario->status === 'ativo'): ?>
                            <span style="background:#f0fdf4;color:#059669;padding:3px 10px;border-radius:20px;font-size:12px;font-weight:600;">● Ativo</span>
                        <?php else: ?>
                            <span style="background:#fef2f2;color:#dc2626;padding:3px 10px;border-radius:20px;font-size:12px;font-weight:600;">● Inativo</span>
                        <?php endif; ?>
                    </td>
                    <td style="color:var(--cinza-400); font-size:13px;">
                        <?php echo e($usuario->created_at->format('d/m/Y')); ?>

                    </td>
                    <td style="text-align:center;">
                        <?php if($usuario->id !== auth()->id()): ?>
                            <a href="<?php echo e(route('usuarios.edit', $usuario)); ?>" class="btn-outline-sced">
                                ✏️ Editar
                            </a>
                        <?php else: ?>
                            <span style="font-size:12px;color:var(--cinza-400);">— você —</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="8" style="text-align:center;padding:48px;color:var(--cinza-400);">
                        Nenhum usuário cadastrado.
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\Controle-de-Entrada-de-Documentos\sced\resources\views/usuarios/index.blade.php ENDPATH**/ ?>