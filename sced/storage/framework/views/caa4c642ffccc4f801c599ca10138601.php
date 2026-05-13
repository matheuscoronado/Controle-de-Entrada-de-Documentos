<?php $__env->startSection('title', 'Novo Usuário'); ?>
<?php $__env->startSection('subtitle', 'Cadastre um novo usuário no sistema'); ?>

<?php $__env->startSection('topbar-actions'); ?>
    <a href="<?php echo e(route('usuarios.index')); ?>" class="btn-secondary-sced">← Voltar</a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="row justify-content-center">
    <div class="col-12 col-lg-7">
        <div class="card-sced card-body-sced">
            <strong style="font-size:16px; color:var(--azul-escuro); display:block; margin-bottom:24px;">
                👤 Dados do Novo Usuário
            </strong>
            <form method="POST" action="<?php echo e(route('usuarios.store')); ?>">
                <?php echo csrf_field(); ?>

                <div class="form-group">
                    <label class="form-label-sced">Nome completo *</label>
                    <input type="text" name="nome"
                           class="form-input-sced <?php echo e($errors->has('nome') ? 'is-invalid' : ''); ?>"
                           value="<?php echo e(old('nome')); ?>"
                           placeholder="Nome completo do usuário"
                           required>
                    <?php $__errorArgs = ['nome'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="form-error"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="form-group">
                    <label class="form-label-sced">E-mail *</label>
                    <input type="email" name="email"
                           class="form-input-sced <?php echo e($errors->has('email') ? 'is-invalid' : ''); ?>"
                           value="<?php echo e(old('email')); ?>"
                           placeholder="email@dominio.com"
                           required>
                    <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="form-error"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="row g-3">
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label class="form-label-sced">Senha *</label>
                            <div style="position:relative;">
                                <input type="password" name="password" id="senha_nova"
                                       class="form-input-sced <?php echo e($errors->has('password') ? 'is-invalid' : ''); ?>"
                                       placeholder="Mínimo 6 caracteres"
                                       style="padding-right:44px;"
                                       required>
                                <button type="button"
                                    onclick="toggleSenha('senha_nova', this)"
                                    style="position:absolute;right:12px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:#94a3b8;display:flex;align-items:center;padding:0;"
                                    title="Mostrar/ocultar senha">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                        <circle cx="12" cy="12" r="3"/>
                                    </svg>
                                </button>
                            </div>
                            <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="form-error"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label class="form-label-sced">Confirmar Senha *</label>
                            <div style="position:relative;">
                                <input type="password" name="password_confirmation" id="senha_confirma"
                                       class="form-input-sced"
                                       placeholder="Repita a senha"
                                       style="padding-right:44px;"
                                       required>
                                <button type="button"
                                    onclick="toggleSenha('senha_confirma', this)"
                                    style="position:absolute;right:12px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:#94a3b8;display:flex;align-items:center;padding:0;"
                                    title="Mostrar/ocultar senha">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                        <circle cx="12" cy="12" r="3"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label-sced">Perfil de acesso *</label>
                    <select name="perfil" class="form-input-sced" required>
                        <option value="operador" <?php echo e(old('perfil')=='operador' ? 'selected' : ''); ?>>
                            👤 Operador — Registra e consulta documentos
                        </option>
                        <option value="administrador" <?php echo e(old('perfil')=='administrador' ? 'selected' : ''); ?>>
                            👑 Administrador — Acesso completo ao sistema
                        </option>
                    </select>
                    <?php $__errorArgs = ['perfil'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="form-error"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                
                <div class="row g-3">
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label class="form-label-sced">Departamento *</label>
                            
                            <select name="departamento_id" class="form-input-sced" required>
                                <option value="" disabled <?php echo e(old('departamento_id') ? '' : 'selected'); ?>>Selecione o departamento</option>
                                <?php $__currentLoopData = $departamentos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $depto): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    
                                    <option value="<?php echo e($depto->id); ?>" <?php echo e(old('departamento_id') == $depto->id ? 'selected' : ''); ?>>
                                        🏢 <?php echo e($depto->nome); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <?php $__errorArgs = ['departamento_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="form-error"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label class="form-label-sced">Cargo *</label>
                            <select name="cargo" class="form-input-sced" required>
                                <option value="" disabled <?php echo e(old('cargo') ? '' : 'selected'); ?>>Selecione o nível</option>
                                <option value="N1" <?php echo e(old('cargo') == 'N1' ? 'selected' : ''); ?>>Nivel 1 (N1)</option>
                                <option value="N2" <?php echo e(old('cargo') == 'N2' ? 'selected' : ''); ?>>Nivel 2 (N2)</option>
                                <option value="N3" <?php echo e(old('cargo') == 'N3' ? 'selected' : ''); ?>>Nivel 3 (N3)</option>
                            </select>
                            <?php $__errorArgs = ['cargo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="form-error"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>
                </div>

                <div style="display:flex; gap:12px; justify-content:flex-end; margin-top:8px; padding-top:20px; border-top:1px solid var(--cinza-200);">
                    <a href="<?php echo e(route('usuarios.index')); ?>" class="btn-secondary-sced">Cancelar</a>
                    <button type="submit" class="btn-primary-sced">💾 Cadastrar Usuário</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function toggleSenha(id, btn) {
        const input = document.getElementById(id);
        const type = input.type === 'password' ? 'text' : 'password';
        input.type = type;
        btn.style.color = type === 'text' ? 'var(--azul-primario)' : '#94a3b8';
    }
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\Controle-de-Entrada-de-Documentos\sced\resources\views/usuarios/create.blade.php ENDPATH**/ ?>