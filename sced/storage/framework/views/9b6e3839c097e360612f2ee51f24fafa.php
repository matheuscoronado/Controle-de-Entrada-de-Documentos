<?php $__env->startSection('title', 'Editar Tipo: ' . $tipo->nome); ?>
<?php $__env->startSection('subtitle', 'Altere a parametrização deste tipo de documento'); ?>

<?php $__env->startSection('topbar-actions'); ?>
    <a href="<?php echo e(route('tipos.index')); ?>" class="btn-outline-sced">← Voltar</a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="row justify-content-center">
<div class="col-lg-8">

<form action="<?php echo e(route('tipos.update', $tipo)); ?>" method="POST">
    <?php echo csrf_field(); ?>
    <?php echo method_field('PUT'); ?>

    
    <div class="card-sced mb-4">
        <div style="font-size:13px;font-weight:700;text-transform:uppercase;letter-spacing:1.2px;color:var(--cinza-400);margin-bottom:20px;padding-bottom:12px;border-bottom:1px solid var(--cinza-200);">
            📋 Identificação do Documento
        </div>

        <div class="mb-3">
            <label class="form-label-sced">Nome do Documento <span style="color:var(--vermelho)">*</span></label>
            <input type="text" name="nome" class="form-control-sced <?php $__errorArgs = ['nome'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                   value="<?php echo e(old('nome', $tipo->nome)); ?>" required>
            <?php $__errorArgs = ['nome'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div class="mb-3">
            <label class="form-label-sced">Descrição</label>
            <textarea name="descricao" class="form-control-sced" rows="3"><?php echo e(old('descricao', $tipo->descricao)); ?></textarea>
        </div>

        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label-sced">Indicador de Exigência <span style="color:var(--vermelho)">*</span></label>
                <select name="obrigatoriedade" class="form-control-sced" required>
                    <option value="obrigatorio" <?php echo e(old('obrigatoriedade', $tipo->obrigatoriedade) === 'obrigatorio' ? 'selected' : ''); ?>>🔴 Obrigatório</option>
                    <option value="opcional"    <?php echo e(old('obrigatoriedade', $tipo->obrigatoriedade) === 'opcional'    ? 'selected' : ''); ?>>🔵 Opcional</option>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label-sced">Status <span style="color:var(--vermelho)">*</span></label>
                <select name="status" class="form-control-sced" required>
                    <option value="ativo"   <?php echo e(old('status', $tipo->status) === 'ativo'   ? 'selected' : ''); ?>>● Ativo</option>
                    <option value="inativo" <?php echo e(old('status', $tipo->status) === 'inativo' ? 'selected' : ''); ?>>● Inativo</option>
                </select>
                <?php if($tipo->documentos_count > 0): ?>
                    <div style="font-size:12px;color:var(--amarelo);margin-top:4px;">
                        ⚠️ Este tipo possui <?php echo e($tipo->documentos_count); ?> documento(s) vinculado(s). Não é possível inativar.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    
    <div class="card-sced mb-4">
        <div style="font-size:13px;font-weight:700;text-transform:uppercase;letter-spacing:1.2px;color:var(--cinza-400);margin-bottom:20px;padding-bottom:12px;border-bottom:1px solid var(--cinza-200);">
            🏢 Destino e Responsável
        </div>

        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label-sced">Setor de Destino</label>
                <select name="departamento_destino_id" class="form-control-sced">
                    <option value="">— Nenhum —</option>
                    <?php $__currentLoopData = $departamentos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dep): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($dep->id); ?>" <?php echo e(old('departamento_destino_id', $tipo->departamento_destino_id) == $dep->id ? 'selected' : ''); ?>>
                            <?php echo e($dep->nome); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label-sced">Cargo Responsável</label>
                <select name="cargo_responsavel" class="form-control-sced">
                    <option value="">— Nenhum —</option>
                    <option value="N1" <?php echo e(old('cargo_responsavel', $tipo->cargo_responsavel) === 'N1' ? 'selected' : ''); ?>>N1 — Atendimento</option>
                    <option value="N2" <?php echo e(old('cargo_responsavel', $tipo->cargo_responsavel) === 'N2' ? 'selected' : ''); ?>>N2 — Analista</option>
                    <option value="N3" <?php echo e(old('cargo_responsavel', $tipo->cargo_responsavel) === 'N3' ? 'selected' : ''); ?>>N3 — Supervisor</option>
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label-sced">SLA — Prazo Máximo (horas)</label>
                <input type="number" name="sla_horas" class="form-control-sced"
                       min="1" max="8760" value="<?php echo e(old('sla_horas', $tipo->sla_horas)); ?>"
                       placeholder="Ex: 24">
            </div>
        </div>
    </div>

    <div style="display:flex;gap:12px;justify-content:flex-end;">
        <a href="<?php echo e(route('tipos.index')); ?>" class="btn-outline-sced">Cancelar</a>
        <button type="submit" class="btn-primary-sced">💾 Salvar Alterações</button>
    </div>
</form>

</div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
.form-label-sced { font-size:13px;font-weight:600;color:var(--cinza-600);margin-bottom:6px;display:block; }
.form-control-sced { width:100%;padding:10px 14px;border:1.5px solid var(--cinza-200);border-radius:var(--radius-sm);font-size:14px;font-family:'Sora',sans-serif;background:var(--branco);color:var(--cinza-800);transition:var(--transicao);outline:none; }
.form-control-sced:focus { border-color:var(--azul-claro);box-shadow:0 0 0 3px rgba(37,99,235,0.12); }
.invalid-feedback { color:var(--vermelho);font-size:12px;margin-top:4px; }
</style>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\Controle-de-Entrada-de-Documentos\sced\resources\views/admin/tipos/edit.blade.php ENDPATH**/ ?>