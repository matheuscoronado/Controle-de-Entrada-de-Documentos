<?php $__env->startSection('title', 'Novo Tipo de Documento'); ?>
<?php $__env->startSection('subtitle', 'Parametrize o tipo, destino e responsável'); ?>

<?php $__env->startSection('topbar-actions'); ?>
    <a href="<?php echo e(route('tipos.index')); ?>" class="btn-outline-sced">← Voltar</a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="row justify-content-center">
<div class="col-lg-8">

<form action="<?php echo e(route('tipos.store')); ?>" method="POST">
    <?php echo csrf_field(); ?>

    
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
                   placeholder="Ex: Memorando, Ofício, Requerimento..."
                   value="<?php echo e(old('nome')); ?>" required>
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
            <textarea name="descricao" class="form-control-sced <?php $__errorArgs = ['descricao'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                      rows="3" placeholder="Descreva a finalidade deste tipo de documento..."><?php echo e(old('descricao')); ?></textarea>
            <?php $__errorArgs = ['descricao'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div class="mb-0">
            <label class="form-label-sced">Indicador de Exigência <span style="color:var(--vermelho)">*</span></label>
            <div style="display:flex;gap:12px;margin-top:8px;">
                <label style="display:flex;align-items:center;gap:8px;cursor:pointer;padding:12px 20px;border:2px solid var(--cinza-200);border-radius:var(--radius-sm);flex:1;transition:var(--transicao);"
                       id="label-obrigatorio">
                    <input type="radio" name="obrigatoriedade" value="obrigatorio"
                           <?php echo e(old('obrigatoriedade') === 'obrigatorio' ? 'checked' : ''); ?>

                           onchange="highlightObrig(this)">
                    <div>
                        <div style="font-weight:600;font-size:14px;color:var(--vermelho);">🔴 Obrigatório</div>
                        <div style="font-size:12px;color:var(--cinza-400);">Sempre exigido no processo</div>
                    </div>
                </label>
                <label style="display:flex;align-items:center;gap:8px;cursor:pointer;padding:12px 20px;border:2px solid var(--cinza-200);border-radius:var(--radius-sm);flex:1;transition:var(--transicao);"
                       id="label-opcional">
                    <input type="radio" name="obrigatoriedade" value="opcional"
                           <?php echo e(old('obrigatoriedade', 'opcional') === 'opcional' ? 'checked' : ''); ?>

                           onchange="highlightObrig(this)">
                    <div>
                        <div style="font-weight:600;font-size:14px;color:var(--azul-claro);">🔵 Opcional</div>
                        <div style="font-size:12px;color:var(--cinza-400);">Complementar ao processo</div>
                    </div>
                </label>
            </div>
            <?php $__errorArgs = ['obrigatoriedade'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-danger" style="font-size:13px;margin-top:4px;"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>
    </div>

    
    <div class="card-sced mb-4">
        <div style="font-size:13px;font-weight:700;text-transform:uppercase;letter-spacing:1.2px;color:var(--cinza-400);margin-bottom:20px;padding-bottom:12px;border-bottom:1px solid var(--cinza-200);">
            🏢 Destino e Responsável
        </div>

        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label-sced">Setor de Destino</label>
                <select name="departamento_destino_id" class="form-control-sced <?php $__errorArgs = ['departamento_destino_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                    <option value="">— Selecione um departamento —</option>
                    <?php $__currentLoopData = $departamentos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dep): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($dep->id); ?>" <?php echo e(old('departamento_destino_id') == $dep->id ? 'selected' : ''); ?>>
                            <?php echo e($dep->nome); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <?php $__errorArgs = ['departamento_destino_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="col-md-6">
                <label class="form-label-sced">Cargo Responsável</label>
                <select name="cargo_responsavel" class="form-control-sced <?php $__errorArgs = ['cargo_responsavel'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                    <option value="">— Selecione o nível —</option>
                    <option value="N1" <?php echo e(old('cargo_responsavel') === 'N1' ? 'selected' : ''); ?>>N1 — Atendimento</option>
                    <option value="N2" <?php echo e(old('cargo_responsavel') === 'N2' ? 'selected' : ''); ?>>N2 — Analista</option>
                    <option value="N3" <?php echo e(old('cargo_responsavel') === 'N3' ? 'selected' : ''); ?>>N3 — Supervisor</option>
                </select>
                <?php $__errorArgs = ['cargo_responsavel'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="col-md-6">
                <label class="form-label-sced">SLA — Prazo Máximo (em horas)</label>
                <input type="number" name="sla_horas" class="form-control-sced <?php $__errorArgs = ['sla_horas'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                       placeholder="Ex: 24 = 1 dia / 72 = 3 dias"
                       min="1" max="8760" value="<?php echo e(old('sla_horas')); ?>">
                <div style="font-size:12px;color:var(--cinza-400);margin-top:4px;">Deixe vazio se não houver prazo definido.</div>
                <?php $__errorArgs = ['sla_horas'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
        </div>
    </div>

    
    <div style="display:flex;gap:12px;justify-content:flex-end;">
        <a href="<?php echo e(route('tipos.index')); ?>" class="btn-outline-sced">Cancelar</a>
        <button type="submit" class="btn-primary-sced">💾 Salvar Tipo</button>
    </div>
</form>

</div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
.form-label-sced {
    font-size: 13px;
    font-weight: 600;
    color: var(--cinza-600);
    margin-bottom: 6px;
    display: block;
}
.form-control-sced {
    width: 100%;
    padding: 10px 14px;
    border: 1.5px solid var(--cinza-200);
    border-radius: var(--radius-sm);
    font-size: 14px;
    font-family: 'Sora', sans-serif;
    background: var(--branco);
    color: var(--cinza-800);
    transition: var(--transicao);
    outline: none;
}
.form-control-sced:focus {
    border-color: var(--azul-claro);
    box-shadow: 0 0 0 3px rgba(37,99,235,0.12);
}
.form-control-sced.is-invalid { border-color: var(--vermelho); }
.invalid-feedback { color: var(--vermelho); font-size: 12px; margin-top: 4px; }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
function highlightObrig(el) {
    document.getElementById('label-obrigatorio').style.borderColor = 'var(--cinza-200)';
    document.getElementById('label-opcional').style.borderColor = 'var(--cinza-200)';
    const label = el.closest('label');
    label.style.borderColor = el.value === 'obrigatorio' ? 'var(--vermelho)' : 'var(--azul-claro)';
}
// Inicializa estado dos botões ao carregar
document.addEventListener('DOMContentLoaded', () => {
    const checked = document.querySelector('input[name="obrigatoriedade"]:checked');
    if (checked) highlightObrig(checked);
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\Controle-de-Entrada-de-Documentos-main\sced\resources\views/admin/tipos/create.blade.php ENDPATH**/ ?>