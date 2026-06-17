

<?php $__env->startSection('title', 'Novo Documento'); ?>
<?php $__env->startSection('subtitle', 'Cadastre um novo tipo de documento'); ?>

<?php $__env->startSection('topbar-actions'); ?>
    <a href="<?php echo e(route('documentos-tipo.index')); ?>" class="btn-outline-sced">← Voltar</a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<style>
    .form-card {
        background: var(--branco);
        border-radius: 16px;
        border: 1px solid var(--cinza-200);
        padding: 28px;
        margin-bottom: 24px;
    }
    .form-section-title {
        font-size: 14px;
        font-weight: 700;
        color: var(--azul-escuro);
        margin-bottom: 24px;
        padding-bottom: 12px;
        border-bottom: 1px solid var(--cinza-200);
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .form-error {
        font-size: 12px;
        color: var(--vermelho);
        margin-top: 4px;
    }
    .helper-text {
        font-size: 11px;
        color: var(--cinza-400);
        margin-top: 4px;
    }
    
    .radio-group {
        display: flex;
        gap: 20px;
        margin-top: 8px;
    }
    .radio-option {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 12px 20px;
        border: 2px solid var(--cinza-200);
        border-radius: 12px;
        cursor: pointer;
        transition: all 0.2s;
        flex: 1;
    }
    .radio-option:hover {
        border-color: var(--azul-claro);
    }
    .radio-option.selected-obrigatorio {
        border-color: #dc2626;
        background: #fff8f8;
    }
    .radio-option.selected-opcional {
        border-color: var(--azul-claro);
        background: #f0f7ff;
    }
    .radio-option input[type="radio"] {
        width: 18px;
        height: 18px;
        accent-color: var(--azul-claro);
        cursor: pointer;
    }
</style>

<div class="row justify-content-center">
    <div class="col-lg-7">

        <div class="form-card">
            <div class="form-section-title">
                📄 Dados do Documento
            </div>

            <?php if($errors->any()): ?>
                <div class="alert alert-danger mb-4">
                    <ul style="margin: 0; padding-left: 16px;">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><li><?php echo e($e); ?></li><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form method="POST" action="<?php echo e(route('documentos-tipo.store')); ?>">
                <?php echo csrf_field(); ?>

                <div class="mb-3">
                    <label class="form-label-sced">Nome do Documento <span class="text-danger">*</span></label>
                    <input type="text" name="nome" class="form-input-sced <?php $__errorArgs = ['nome'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                           value="<?php echo e(old('nome')); ?>" placeholder="Ex: RG, CPF, Certidão de Casamento" required>
                    <?php $__errorArgs = ['nome'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="form-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="mb-3">
                    <label class="form-label-sced">Descrição <span class="text-danger">*</span></label>
                    <textarea name="descricao" class="form-input-sced <?php $__errorArgs = ['descricao'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                              rows="3" placeholder="Descreva o documento e quando ele deve ser apresentado" required><?php echo e(old('descricao')); ?></textarea>
                    <?php $__errorArgs = ['descricao'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="form-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="mb-4">
                    <label class="form-label-sced">Tipo <span class="text-danger">*</span></label>
                    <div class="radio-group">
                        <label class="radio-option" id="label-obrigatorio">
                            <input type="radio" name="tipo" value="obrigatorio" <?php echo e(old('tipo') == 'obrigatorio' ? 'checked' : ''); ?> onchange="highlightTipo(this)">
                            <div>
                                <div style="font-weight: 600; color: #dc2626;">🔴 Obrigatório</div>
                                <div style="font-size: 11px; color: var(--cinza-400);">Sempre exigido no processo</div>
                            </div>
                        </label>
                        <label class="radio-option" id="label-opcional">
                            <input type="radio" name="tipo" value="opcional" <?php echo e(old('tipo', 'opcional') == 'opcional' ? 'checked' : ''); ?> onchange="highlightTipo(this)">
                            <div>
                                <div style="font-weight: 600; color: var(--azul-claro);">🔵 Opcional</div>
                                <div style="font-size: 11px; color: var(--cinza-400);">Complementar ao processo</div>
                            </div>
                        </label>
                    </div>
                    <?php $__errorArgs = ['tipo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="form-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="d-flex justify-content-end gap-3 mt-4 pt-3 border-top">
                    <a href="<?php echo e(route('documentos-tipo.index')); ?>" class="btn-secondary-sced">Cancelar</a>
                    <button type="submit" class="btn-primary-sced">💾 Salvar Documento</button>
                </div>

            </form>
        </div>

    </div>
</div>

<script>
    function highlightTipo(el) {
        const labelObrig = document.getElementById('label-obrigatorio');
        const labelOpc = document.getElementById('label-opcional');
        labelObrig.classList.remove('selected-obrigatorio', 'selected-opcional');
        labelOpc.classList.remove('selected-obrigatorio', 'selected-opcional');
        
        if (el.value === 'obrigatorio') {
            labelObrig.classList.add('selected-obrigatorio');
        } else {
            labelOpc.classList.add('selected-opcional');
        }
    }
    
    document.addEventListener('DOMContentLoaded', () => {
        const checked = document.querySelector('input[name="tipo"]:checked');
        if (checked) highlightTipo(checked);
    });
</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\Controle-de-Entrada-de-Documentos\sced\resources\views/admin/documentos/create.blade.php ENDPATH**/ ?>