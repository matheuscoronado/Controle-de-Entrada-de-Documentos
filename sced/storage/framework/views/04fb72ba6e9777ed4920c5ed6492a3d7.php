<?php $__env->startSection('title', 'Novo Documento'); ?>
<?php $__env->startSection('subtitle', 'Registre a entrada de um novo documento'); ?>

<?php $__env->startSection('topbar-actions'); ?>
    <a href="<?php echo e(route('documentos.index')); ?>" class="btn-secondary-sced">
        ← Voltar
    </a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<div class="row justify-content-center">
    <div class="col-12 col-lg-9">
        <div class="card-sced">
            <div class="card-header-sced">
                <div>
                    <strong style="font-size:16px; color:var(--azul-escuro);">📄 Dados do Documento</strong>
                    <div style="font-size:13px; color:var(--cinza-400); margin-top:3px;">
                        O protocolo será gerado automaticamente ao salvar.
                    </div>
                </div>
            </div>
            <div class="card-body-sced">
                <form method="POST" action="<?php echo e(route('documentos.store')); ?>" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>

                    <div class="row g-3">

                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label class="form-label-sced">Tipo de Documento *</label>
                                <select name="tipo_documento_id"
                                        class="form-input-sced <?php echo e($errors->has('tipo_documento_id') ? 'is-invalid' : ''); ?>"
                                        required>
                                    <option value="">Selecione o tipo...</option>
                                    <?php $__currentLoopData = $tipos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tipo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($tipo->id); ?>"
                                            <?php echo e(old('tipo_documento_id') == $tipo->id ? 'selected' : ''); ?>>
                                            <?php echo e($tipo->nome); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <?php $__errorArgs = ['tipo_documento_id'];
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
                                <label class="form-label-sced">Data de Recebimento *</label>
                                <input type="date" name="data_recebimento"
                                       class="form-input-sced <?php echo e($errors->has('data_recebimento') ? 'is-invalid' : ''); ?>"
                                       value="<?php echo e(old('data_recebimento', date('Y-m-d'))); ?>"
                                       required>
                                <?php $__errorArgs = ['data_recebimento'];
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
                                <label class="form-label-sced">Remetente *</label>
                                <input type="text" name="remetente"
                                       class="form-input-sced <?php echo e($errors->has('remetente') ? 'is-invalid' : ''); ?>"
                                       value="<?php echo e(old('remetente')); ?>"
                                       placeholder="Nome ou órgão de origem"
                                       required>
                                <?php $__errorArgs = ['remetente'];
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
                                <label class="form-label-sced">Setor de Destino *</label>
                                <input type="text" name="setor_destino"
                                       class="form-input-sced <?php echo e($errors->has('setor_destino') ? 'is-invalid' : ''); ?>"
                                       value="<?php echo e(old('setor_destino')); ?>"
                                       placeholder="Ex: RH, Financeiro, Diretoria"
                                       required>
                                <?php $__errorArgs = ['setor_destino'];
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

                        <div class="col-12">
                            <div class="form-group">
                                <label class="form-label-sced">Assunto *</label>
                                <input type="text" name="assunto"
                                       class="form-input-sced <?php echo e($errors->has('assunto') ? 'is-invalid' : ''); ?>"
                                       value="<?php echo e(old('assunto')); ?>"
                                       placeholder="Descreva brevemente o assunto do documento"
                                       required>
                                <?php $__errorArgs = ['assunto'];
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

                        <div class="col-12">
                            <div class="form-group">
                                <label class="form-label-sced">Descrição complementar</label>
                                <textarea name="descricao"
                                          class="form-input-sced"
                                          rows="4"
                                          placeholder="Informações adicionais sobre o documento (opcional)..."><?php echo e(old('descricao')); ?></textarea>
                            </div>
                        </div>

                        
                        <div class="col-12">
                            <div class="form-group">
                                <label class="form-label-sced">Arquivos Anexos (opcional)</label>

                                <div class="upload-area" onclick="document.getElementById('inputAnexos').click()">
                                    <span class="upload-icon">📎</span>
                                    <div class="upload-text" id="uploadLabel">
                                        Clique para selecionar ou arraste os arquivos aqui
                                    </div>
                                    <div style="font-size:11px; color:var(--cinza-400); margin-top:4px;">
                                        PDF, DOC, DOCX, JPG, PNG — máximo 10MB por arquivo — pode selecionar vários
                                    </div>
                                </div>

                                
                                <input type="file"
                                       id="inputAnexos"
                                       name="anexos[]"
                                       multiple
                                       style="display:none"
                                       accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                                       onchange="mostrarArquivos(this)">

                                
                                <div id="listaArquivos" style="margin-top:10px; display:none;">
                                    <div style="font-size:12px; color:var(--cinza-600); font-weight:600; margin-bottom:6px;">
                                        Arquivos selecionados:
                                    </div>
                                    <div id="arquivosItens"></div>
                                </div>

                                <?php $__errorArgs = ['anexos'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="form-error"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                <?php $__errorArgs = ['anexos.*'];
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
                        <a href="<?php echo e(route('documentos.index')); ?>" class="btn-secondary-sced">
                            Cancelar
                        </a>
                        <button type="submit" class="btn-primary-sced">
                            💾 Registrar Documento
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
function mostrarArquivos(input) {
    const lista = document.getElementById('listaArquivos');
    const itens = document.getElementById('arquivosItens');
    const label = document.getElementById('uploadLabel');

    if (input.files.length === 0) {
        lista.style.display = 'none';
        label.textContent = 'Clique para selecionar ou arraste os arquivos aqui';
        return;
    }

    const total = input.files.length;
    label.textContent = total === 1
        ? '1 arquivo selecionado'
        : total + ' arquivos selecionados';

    itens.innerHTML = '';
    Array.from(input.files).forEach(file => {
        const tamanho = (file.size / 1024).toFixed(1) + ' KB';
        const div = document.createElement('div');
        div.style.cssText = 'display:flex;align-items:center;gap:8px;padding:7px 12px;background:var(--cinza-100);border-radius:6px;margin-bottom:5px;font-size:13px;';
        div.innerHTML = '<span>📄</span><span style="flex:1;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">' + file.name + '</span><span style="font-size:11px;color:var(--cinza-400);">' + tamanho + '</span>';
        itens.appendChild(div);
    });

    lista.style.display = 'block';
}
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\Controle-de-Entrada-de-Documentos\sced\resources\views/documentos/create.blade.php ENDPATH**/ ?>