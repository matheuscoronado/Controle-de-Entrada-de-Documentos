<?php $__env->startSection('title', 'Editar Tipo'); ?>
<?php $__env->startSection('subtitle', $tipo->nome); ?>

<?php $__env->startSection('topbar-actions'); ?>
    <a href="<?php echo e(route('tipos.index')); ?>" class="btn-secondary-sced">← Voltar</a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="row justify-content-center">
    <div class="col-12 col-lg-6">
        <div class="card-sced card-body-sced">
            <strong style="font-size:16px; color:var(--azul-escuro); display:block; margin-bottom:24px;">
                ✏️ Editar Tipo de Documento
            </strong>
            <form method="POST" action="<?php echo e(route('tipos.update', $tipo)); ?>">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>

                <div class="form-group">
                    <label class="form-label-sced">Nome *</label>
                    <input type="text" name="nome"
                           class="form-input-sced <?php echo e($errors->has('nome') ? 'is-invalid' : ''); ?>"
                           value="<?php echo e(old('nome', $tipo->nome)); ?>"
                           required>
                    <?php $__errorArgs = ['nome'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="form-error"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="form-group">
                    <label class="form-label-sced">Descrição</label>
                    <textarea name="descricao" class="form-input-sced" rows="3"><?php echo e(old('descricao', $tipo->descricao)); ?></textarea>
                </div>

                <div class="form-group">
                    <label class="form-label-sced">Status *</label>
                    <select name="status" class="form-input-sced" required>
                        <option value="ativo"   <?php echo e(old('status', $tipo->status)=='ativo'   ? 'selected' : ''); ?>>● Ativo</option>
                        <option value="inativo" <?php echo e(old('status', $tipo->status)=='inativo' ? 'selected' : ''); ?>>● Inativo</option>
                    </select>
                    <?php if($tipo->documentos()->exists()): ?>
                    <div style="font-size:11px; color:var(--amarelo); margin-top:4px;">
                        ⚠️ Este tipo possui documentos vinculados — não pode ser desativado.
                    </div>
                    <?php endif; ?>
                </div>

                <div style="display:flex; gap:12px; justify-content:flex-end; padding-top:20px; border-top:1px solid var(--cinza-200);">
                    <a href="<?php echo e(route('tipos.index')); ?>" class="btn-secondary-sced">Cancelar</a>
                    <button type="submit" class="btn-primary-sced">💾 Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\Controle-de-Entrada-de-Documentos\sced\resources\views/tipos/edit.blade.php ENDPATH**/ ?>