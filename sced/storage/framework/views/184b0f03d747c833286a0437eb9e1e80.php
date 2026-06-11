<?php $__env->startSection('title', 'Departamentos'); ?>
<?php $__env->startSection('subtitle', 'Gerenciamento de unidades organizacionais do sistema'); ?>

<?php $__env->startSection('content'); ?>
<div class="row g-3">
    
    <div class="col-12 col-lg-5">
        <div class="card-sced card-body-sced">
            <strong style="font-size:15px; color:var(--azul-escuro); display:block; margin-bottom:20px;">
                🏢 Cadastrar Nova Unidade
            </strong>
            
            <form method="POST" action="<?php echo e(route('departamentos.store')); ?>">
                <?php echo csrf_field(); ?>
                <div class="form-group">
                    <label class="form-label-sced">Nome do Departamento *</label>
                    <input type="text" name="nome" 
                           class="form-input-sced <?php $__errorArgs = ['nome'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                           placeholder="Ex: Recursos Humanos" 
                           value="<?php echo e(old('nome')); ?>" required autofocus>
                    <?php $__errorArgs = ['nome'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div style="color: #dc2626; font-size: 12px; margin-top: 5px;"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div style="padding-top:20px; border-top:1px solid var(--cinza-200); margin-top:20px;">
                    <button type="submit" class="btn-primary-sced" style="width:100%; justify-content:center; font-size:15px; padding:13px;">
                        💾 Salvar Departamento
                    </button>
                    <div style="text-align:center; font-size:12px; color:var(--cinza-400); margin-top:8px;">
                        A unidade ficará disponível para seleção no cadastro de usuários.
                    </div>
                </div>
            </form>
        </div>
    </div>

    
    <div class="col-12 col-lg-7">
        <div class="card-sced">
            <div class="card-header-sced" style="padding-bottom:16px;">
                <strong style="font-size:15px; color:var(--azul-escuro);">📋 Unidades Ativas</strong>
            </div>
            
            <div style="overflow-x:auto;">
                <table class="tabela-sced">
                    <thead>
                        <tr>
                            <th style="width: 80px;">#</th>
                            <th>Departamento</th>
                            <th>Total de Usuários</th>
                            <th style="text-align:center;">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $departamentos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $depto): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td style="color:var(--cinza-400); font-size:13px;"><?php echo e($depto->id); ?></td>
                            <td>
                                <div style="display:flex; align-items:center; gap:10px;">
                                    <div style="width:32px;height:32px;border-radius:8px;background:var(--azul-claro);color:white;display:flex;align-items:center;justify-content:center;font-size:14px;">
                                        🏢
                                    </div>
                                    <div style="display:flex; flex-direction:column;">
                                        <span style="font-weight:600; color:var(--azul-escuro);"><?php echo e($depto->nome); ?></span>
                                        <span style="font-size:11px; color:var(--cinza-500);">Unidade Organizacional</span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                
                                <span style="background:var(--cinza-100); color:var(--azul-escuro); padding:2px 10px; border-radius:12px; font-size:11px; font-weight:700; border:1px solid var(--cinza-200);">
                                    <?php echo e($depto->usuarios_count ?? 0); ?> usuários
                                </span>
                            </td>
                            <td style="text-align:center;">
                                <div style="display:flex; gap:8px; justify-content:center;">
                                    <form action="<?php echo e(route('departamentos.destroy', $depto)); ?>" method="POST" 
                                          onsubmit="return confirm('Deseja realmente remover esta unidade?')">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="btn-outline-sced" style="color:#dc2626; border-color:#fecaca; padding: 5px 10px; font-size: 12px;">
                                            🗑️ Excluir
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="4" style="text-align:center; padding:48px; color:var(--cinza-400);">
                                <div style="font-size: 24px; margin-bottom: 10px;">📂</div>
                                Nenhum departamento cadastrado até o momento.
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            
            <div style="padding:15px; background:var(--cinza-50); border-top:1px solid var(--cinza-200); border-radius: 0 0 12px 12px;">
                <span style="font-size:12px; color:var(--cinza-500);">
                    Total: <strong><?php echo e(count($departamentos)); ?></strong> registros encontrados.
                </span>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\Controle-de-Entrada-de-Documentos-main\sced\resources\views/departamentos/index.blade.php ENDPATH**/ ?>