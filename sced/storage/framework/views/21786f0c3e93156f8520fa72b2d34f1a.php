<?php $__env->startSection('title', 'Novo Processo'); ?>
<?php $__env->startSection('subtitle', 'Abertura de solicitação de serviço'); ?>

<?php $__env->startSection('topbar-actions'); ?>
    <a href="<?php echo e(route('documentos.index')); ?>" class="btn-secondary-sced">← Voltar</a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<form method="POST" action="<?php echo e(route('documentos.store')); ?>"
      enctype="multipart/form-data" id="formProcesso" novalidate>
<?php echo csrf_field(); ?>

<div class="row g-4">


<div class="col-lg-8">

    
    <div class="processo-card mb-4">
        <div class="processo-bloco-header">
            <div class="processo-step">1</div>
            <div>
                <div class="processo-bloco-titulo">Identificação do Serviço</div>
                <div class="processo-bloco-sub">Busque e selecione o serviço solicitado</div>
            </div>
        </div>

        
        <div class="ac-container" id="acContainer">
            <label class="p-label">
                Serviço <span class="p-req">*</span>
                <span class="p-hint">— comece a digitar para buscar</span>
            </label>

            <div class="ac-field-wrap" id="acWrap">
                <span class="ac-icon">🔍</span>
                <input type="text" id="acInput" class="ac-input"
                       placeholder="Ex: Memorando, Ofício, Requerimento..."
                       autocomplete="off" spellcheck="false">
                <span class="ac-spinner" id="acSpinner"></span>
                <button type="button" class="ac-clear" id="acClear"
                        onclick="limparServico()" style="display:none">✕</button>
            </div>
            <input type="hidden" name="tipo_documento_id" id="servicoId">

            <div class="ac-dropdown" id="acDropdown"></div>

            <?php $__errorArgs = ['tipo_documento_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <div class="p-error"><?php echo e($message); ?></div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        
        <div class="docs-alert" id="docsAlert" style="display:none">
            <div class="docs-alert-head">
                <span class="docs-alert-icon">📋</span>
                <strong>Para este serviço é obrigatório anexar:</strong>
            </div>
            <ul class="docs-alert-list" id="docsAlertList"></ul>
            <p class="docs-alert-empty" id="docsAlertEmpty" style="display:none">
                Nenhum documento adicional obrigatório para este serviço.
            </p>
        </div>

        
        <div class="servico-info-strip" id="servicoInfoStrip" style="display:none">
            <div class="servico-info-item">
                <span class="servico-info-label">Destino</span>
                <span class="servico-info-valor" id="siSetor">—</span>
            </div>
            <div class="servico-info-item">
                <span class="servico-info-label">Responsável</span>
                <span class="servico-info-valor" id="siCargo">—</span>
            </div>
            <div class="servico-info-item">
                <span class="servico-info-label">SLA</span>
                <span class="servico-info-valor" id="siSla">—</span>
            </div>
        </div>
    </div>

    
    <div class="processo-card mb-4">
        <div class="processo-bloco-header">
            <div class="processo-step">2</div>
            <div>
                <div class="processo-bloco-titulo">Dados do Solicitante</div>
                <div class="processo-bloco-sub">Identifique quem está abrindo este processo</div>
            </div>
        </div>

        <div class="row g-3">
            <div class="col-md-8">
                <label class="p-label">Solicitante / Remetente <span class="p-req">*</span></label>
                <input type="text" name="remetente"
                       class="p-input <?php $__errorArgs = ['remetente'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> p-input--erro <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                       placeholder="Nome completo ou razão social"
                       value="<?php echo e(old('remetente')); ?>" required>
                <?php $__errorArgs = ['remetente'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="p-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="col-md-4">
                <label class="p-label">Data de Abertura</label>
                <div class="p-date-wrap">
                    <input type="date" name="data_recebimento"
                           class="p-input p-input--locked"
                           value="<?php echo e(date('Y-m-d')); ?>"
                           readonly tabindex="-1">
                    <span class="p-lock-badge">🔒 Hoje</span>
                </div>
                <div class="p-hint-text">Fixada automaticamente na data atual</div>
            </div>

            
            <div class="col-12">
                <label class="p-label">
                    Setor de Destino <span class="p-req">*</span>
                    <span class="p-badge-auto" id="badgeAutoSetor" style="display:none">✨ automático</span>
                </label>
                <input type="text" name="setor_destino" id="setorDestino"
                       class="p-input <?php $__errorArgs = ['setor_destino'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> p-input--erro <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                       placeholder="Selecione o serviço para preencher automaticamente"
                       value="<?php echo e(old('setor_destino')); ?>" readonly>
                <input type="hidden" name="departamento_destino_id" id="depDestinoId">
                <div class="p-hint-text" id="hintSetor">
                    Será preenchido ao selecionar o serviço
                </div>
                <?php $__errorArgs = ['setor_destino'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="p-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="col-12">
                <label class="p-label">Descrição / Observações</label>
                <textarea name="descricao" class="p-input p-textarea" rows="3"
                          placeholder="Detalhes adicionais sobre a solicitação (opcional)..."><?php echo e(old('descricao')); ?></textarea>
            </div>
        </div>
    </div>

    
    <div class="processo-card mb-4">
        <div class="processo-bloco-header">
            <div class="processo-step">3</div>
            <div>
                <div class="processo-bloco-titulo">Documentos Anexos</div>
                <div class="processo-bloco-sub">Adicione os arquivos necessários para o processo</div>
            </div>
            <div class="bloco-badge-validacao">⚠️ Validação manual posterior</div>
        </div>

        
        <div class="upload-aviso">
            <span>ℹ️</span>
            <p>Os documentos enviados passarão por <strong>validação manual</strong> pela equipe responsável.
               O processo é iniciado mesmo com arquivos pendentes de aprovação.</p>
        </div>

        
        <div class="upload-zone" id="uploadZone"
             ondragover="event.preventDefault(); this.classList.add('--over')"
             ondragleave="this.classList.remove('--over')"
             ondrop="handleDrop(event)"
             onclick="document.getElementById('fileInput').click()">
            <div class="upload-zone-icone">📎</div>
            <div class="upload-zone-texto">
                Arraste arquivos aqui ou <span class="upload-zone-link">clique para selecionar</span>
            </div>
            <div class="upload-zone-formatos">PDF · DOC · DOCX · JPG · PNG — máx. 10 MB por arquivo</div>
        </div>

        <input type="file" id="fileInput" name="anexos[]"
               multiple style="display:none"
               accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
               onchange="adicionarArquivos(this.files)">

        
        <div id="uploadList"></div>

        
        <div id="hiddenInputs"></div>

        <?php $__errorArgs = ['anexos'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <div class="p-error mt-2"><?php echo e($message); ?></div>
        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>

</div>


<div class="col-lg-4">

    
    <div class="resumo-card" id="resumoCard">
        <div class="resumo-titulo">📋 Resumo do Processo</div>

        <div class="resumo-vazio" id="resumoVazio">
            <div class="resumo-vazio-icone">🔍</div>
            <p>Selecione um serviço para ver o resumo</p>
        </div>

        <div id="resumoConteudo" style="display:none">
            <div class="resumo-linha">
                <span class="resumo-label">Serviço</span>
                <span class="resumo-valor" id="rServico">—</span>
            </div>
            <div class="resumo-linha">
                <span class="resumo-label">Destino</span>
                <span class="resumo-valor" id="rDestino">—</span>
            </div>
            <div class="resumo-linha">
                <span class="resumo-label">Responsável</span>
                <span class="resumo-valor" id="rCargo">—</span>
            </div>
            <div class="resumo-linha">
                <span class="resumo-label">SLA / Prazo</span>
                <span class="resumo-valor" id="rSla">—</span>
            </div>
            <div class="resumo-linha">
                <span class="resumo-label">Arquivos</span>
                <span class="resumo-valor">
                    <span id="rAnexos">0</span> anexado(s)
                </span>
            </div>
            <div class="resumo-linha resumo-linha--last">
                <span class="resumo-label">Abertura</span>
                <span class="resumo-valor"><?php echo e(now()->format('d/m/Y')); ?></span>
            </div>
        </div>
    </div>

    
    <div class="acao-card">
        <button type="submit" class="btn-primary-sced btn-abrir" id="btnAbrir">
            🚀 Abrir Processo
        </button>
        <a href="<?php echo e(route('documentos.index')); ?>"
           class="btn-secondary-sced btn-cancelar">
            Cancelar
        </a>
    </div>

</div>

</div>
</form>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
/* ── Variáveis locais (herdam do sistema) ───────────────── */
:root {
    --p-radius: var(--radius, 12px);
    --p-radius-sm: var(--radius-sm, 8px);
    --p-trans: var(--transicao, all .22s cubic-bezier(.4,0,.2,1));
}

/* ── Card de processo ───────────────────────────────────── */
.processo-card {
    background: var(--branco);
    border-radius: var(--p-radius);
    border: 1px solid var(--cinza-200);
    box-shadow: var(--sombra-card);
    padding: 24px;
    transition: var(--p-trans);
}
.processo-card:hover { box-shadow: var(--sombra-hover); }

.processo-bloco-header {
    display: flex; align-items: flex-start; gap: 14px;
    margin-bottom: 22px; padding-bottom: 18px;
    border-bottom: 1px solid var(--cinza-200);
}
.processo-step {
    width: 30px; height: 30px; border-radius: 50%;
    background: var(--azul-claro); color: #fff;
    font-size: 13px; font-weight: 700;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0; margin-top: 2px;
    box-shadow: 0 2px 8px rgba(37,99,235,.3);
}
.processo-bloco-titulo {
    font-size: 15px; font-weight: 700; color: var(--azul-escuro);
}
.processo-bloco-sub {
    font-size: 12px; color: var(--cinza-400); margin-top: 2px;
}
.bloco-badge-validacao {
    margin-left: auto; flex-shrink: 0;
    font-size: 11px; font-weight: 600;
    color: #92400e; background: #fef3c7;
    padding: 3px 10px; border-radius: 20px;
}

/* ── Elementos de formulário ────────────────────────────── */
.p-label {
    display: block; font-size: 12px; font-weight: 600;
    color: var(--cinza-600); text-transform: uppercase;
    letter-spacing: .6px; margin-bottom: 7px;
}
.p-req { color: var(--vermelho); }
.p-hint { font-size: 11px; font-weight: 400; color: var(--cinza-400); text-transform: none; letter-spacing: 0; }
.p-hint-text { font-size: 11px; color: var(--cinza-400); margin-top: 5px; }
.p-error { font-size: 12px; color: var(--vermelho); margin-top: 5px; }
.p-badge-auto {
    font-size: 10px; font-weight: 600; color: #059669;
    background: #d1fae5; padding: 1px 7px; border-radius: 10px;
    text-transform: none; letter-spacing: 0; margin-left: 6px;
}
.p-input {
    width: 100%; padding: 11px 14px;
    border: 1.5px solid var(--cinza-200); border-radius: var(--p-radius-sm);
    font-family: 'Sora', sans-serif; font-size: 14px;
    color: var(--cinza-800); background: var(--branco);
    transition: var(--p-trans); outline: none;
    appearance: none;
}
.p-input:focus { border-color: var(--azul-claro); box-shadow: 0 0 0 3px rgba(37,99,235,.12); }
.p-input--erro { border-color: var(--vermelho); }
.p-input--locked { background: var(--cinza-100); color: var(--cinza-600); cursor: not-allowed; }
.p-textarea { resize: vertical; min-height: 90px; }
.p-date-wrap { position: relative; }
.p-lock-badge {
    position: absolute; right: 10px; top: 50%; transform: translateY(-50%);
    font-size: 11px; font-weight: 600; color: var(--cinza-400);
    background: var(--cinza-200); padding: 2px 8px; border-radius: 6px;
    pointer-events: none;
}

/* ── Autocomplete ───────────────────────────────────────── */
.ac-container { position: relative; margin-bottom: 0; }
.ac-field-wrap { position: relative; }
.ac-icon {
    position: absolute; left: 13px; top: 50%; transform: translateY(-50%);
    font-size: 15px; pointer-events: none;
}
.ac-input {
    width: 100%; padding: 11px 80px 11px 40px;
    border: 1.5px solid var(--cinza-200); border-radius: var(--p-radius-sm);
    font-family: 'Sora', sans-serif; font-size: 14px;
    color: var(--cinza-800); background: var(--branco);
    transition: var(--p-trans); outline: none;
}
.ac-input:focus { border-color: var(--azul-claro); box-shadow: 0 0 0 3px rgba(37,99,235,.12); }
.ac-input.--selected { border-color: var(--verde); background: #f0fdf4; font-weight: 600; }
.ac-input.--erro { border-color: var(--vermelho); }
.ac-spinner {
    position: absolute; right: 40px; top: 50%; transform: translateY(-50%);
    width: 16px; height: 16px;
    border: 2px solid var(--cinza-200); border-top-color: var(--azul-claro);
    border-radius: 50%; animation: girar .7s linear infinite;
    display: none;
}
.ac-spinner.--ativo { display: block; }
@keyframes girar { to { transform: translateY(-50%) rotate(360deg); } }
.ac-clear {
    position: absolute; right: 10px; top: 50%; transform: translateY(-50%);
    background: none; border: none; cursor: pointer;
    font-size: 14px; color: var(--cinza-400); padding: 4px 8px;
    line-height: 1; transition: color .15s;
}
.ac-clear:hover { color: var(--vermelho); }

.ac-dropdown {
    position: absolute; top: calc(100% + 5px); left: 0; right: 0; z-index: 300;
    background: var(--branco); border: 1.5px solid var(--cinza-200);
    border-radius: var(--p-radius-sm); box-shadow: var(--sombra-hover);
    max-height: 340px; overflow-y: auto; display: none;
}
.ac-dropdown.--aberto { display: block; animation: fadeSlide .18s ease; }
@keyframes fadeSlide { from { opacity: 0; transform: translateY(-6px); } to { opacity: 1; transform: none; } }

.ac-item {
    padding: 12px 16px; cursor: pointer;
    border-bottom: 1px solid var(--cinza-200);
    transition: background .12s;
}
.ac-item:last-child { border-bottom: none; }
.ac-item:hover, .ac-item.--foco { background: var(--cinza-100); }
.ac-item-nome { font-size: 14px; font-weight: 600; color: var(--cinza-800); display: flex; align-items: center; gap: 6px; }
.ac-item-desc { font-size: 12px; color: var(--cinza-400); margin-top: 2px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
.ac-item-setor { font-size: 11px; font-weight: 600; color: var(--azul-claro); margin-top: 4px; }
.ac-badge { font-size: 10px; font-weight: 700; padding: 2px 6px; border-radius: 8px; background: #fef2f2; color: #dc2626; }
.ac-vazio { padding: 24px; text-align: center; color: var(--cinza-400); font-size: 13px; }

/* ── Alerta docs obrigatórios ───────────────────────────── */
.docs-alert {
    margin-top: 16px; padding: 16px;
    background: #eff6ff; border: 1.5px solid #bfdbfe;
    border-radius: var(--p-radius-sm);
    animation: fadeSlide .22s ease;
}
.docs-alert-head {
    display: flex; align-items: center; gap: 8px;
    font-size: 13px; font-weight: 600; color: #1d4ed8;
    margin-bottom: 10px;
}
.docs-alert-icon { font-size: 16px; }
.docs-alert-list {
    margin: 0; padding: 0; list-style: none;
    display: flex; flex-direction: column; gap: 5px;
}
.docs-alert-list li {
    display: flex; align-items: center; gap: 8px;
    font-size: 13px; color: #1e40af; font-weight: 500;
}
.docs-alert-list li::before { content: "📄"; font-size: 14px; }
.docs-alert-empty { font-size: 13px; color: var(--cinza-400); margin: 0; }

/* ── Info strip do serviço ──────────────────────────────── */
.servico-info-strip {
    margin-top: 12px; padding: 12px 16px;
    background: var(--cinza-100); border-radius: var(--p-radius-sm);
    display: flex; gap: 24px; flex-wrap: wrap;
    animation: fadeSlide .2s ease;
}
.servico-info-item { display: flex; flex-direction: column; gap: 2px; }
.servico-info-label { font-size: 10px; font-weight: 600; text-transform: uppercase; letter-spacing: .8px; color: var(--cinza-400); }
.servico-info-valor { font-size: 13px; font-weight: 600; color: var(--cinza-800); }

/* ── Upload ─────────────────────────────────────────────── */
.upload-aviso {
    display: flex; gap: 10px; align-items: flex-start;
    background: #fffbeb; border: 1.5px solid #fde68a;
    border-radius: var(--p-radius-sm); padding: 12px 16px;
    margin-bottom: 18px;
}
.upload-aviso span { font-size: 16px; flex-shrink: 0; margin-top: 1px; }
.upload-aviso p { font-size: 13px; color: #92400e; margin: 0; line-height: 1.5; }

.upload-zone {
    border: 2px dashed var(--cinza-200); border-radius: var(--p-radius-sm);
    padding: 40px 24px; text-align: center; cursor: pointer;
    background: var(--cinza-100); transition: var(--p-trans);
}
.upload-zone:hover, .upload-zone.--over {
    border-color: var(--azul-claro);
    background: rgba(37,99,235,.04);
}
.upload-zone.--over { transform: scale(1.01); }
.upload-zone-icone { font-size: 38px; margin-bottom: 10px; }
.upload-zone-texto { font-size: 14px; color: var(--cinza-600); font-weight: 500; }
.upload-zone-link { color: var(--azul-claro); font-weight: 700; text-decoration: underline; }
.upload-zone-formatos { font-size: 11px; color: var(--cinza-400); margin-top: 6px; }

.upload-item {
    display: flex; gap: 12px; align-items: flex-start;
    padding: 14px; margin-top: 10px;
    background: var(--branco); border: 1.5px solid var(--cinza-200);
    border-radius: var(--p-radius-sm);
    animation: fadeSlide .2s ease;
    transition: var(--p-trans);
}
.upload-item:hover { border-color: var(--azul-claro); }
.upload-item-icone { font-size: 24px; flex-shrink: 0; margin-top: 2px; }
.upload-item-corpo { flex: 1; min-width: 0; }
.upload-item-nome { font-size: 13px; font-weight: 600; color: var(--cinza-800); overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
.upload-item-meta { font-size: 11px; color: var(--cinza-400); margin-top: 2px; }
.upload-item-pendente {
    display: inline-flex; align-items: center; gap: 4px;
    font-size: 10px; font-weight: 600; color: #92400e;
    background: #fef3c7; padding: 2px 8px; border-radius: 10px;
    margin-top: 6px; margin-bottom: 8px;
}
.upload-item-select {
    width: 100%; padding: 8px 10px; font-size: 12px;
    border: 1.5px solid var(--cinza-200); border-radius: 6px;
    font-family: 'Sora', sans-serif; background: var(--cinza-100);
    color: var(--cinza-800); cursor: pointer; outline: none;
    transition: var(--p-trans);
}
.upload-item-select:focus { border-color: var(--azul-claro); }
.upload-item-remove {
    flex-shrink: 0; background: none; border: none;
    cursor: pointer; color: var(--cinza-400);
    font-size: 18px; padding: 2px 4px; line-height: 1;
    transition: color .15s; margin-top: 2px;
}
.upload-item-remove:hover { color: var(--vermelho); }

/* ── Lateral ─────────────────────────────────────────────── */
.resumo-card {
    background: var(--branco); border-radius: var(--p-radius);
    border: 1px solid var(--cinza-200); box-shadow: var(--sombra-card);
    padding: 22px; margin-bottom: 16px;
    position: sticky; top: 82px;
}
.resumo-titulo {
    font-size: 13px; font-weight: 700; color: var(--azul-escuro);
    margin-bottom: 18px; padding-bottom: 14px;
    border-bottom: 1px solid var(--cinza-200);
}
.resumo-vazio { text-align: center; padding: 20px 0 8px; }
.resumo-vazio-icone { font-size: 32px; margin-bottom: 8px; opacity: .35; }
.resumo-vazio p { font-size: 13px; color: var(--cinza-400); margin: 0; }
.resumo-linha {
    display: flex; flex-direction: column; gap: 2px;
    padding: 9px 0; border-bottom: 1px solid var(--cinza-200);
}
.resumo-linha--last { border-bottom: none; }
.resumo-label { font-size: 10px; font-weight: 600; text-transform: uppercase; letter-spacing: .8px; color: var(--cinza-400); }
.resumo-valor { font-size: 13px; font-weight: 600; color: var(--cinza-800); }

.acao-card {
    display: flex; flex-direction: column; gap: 10px;
}
.btn-abrir {
    width: 100%; justify-content: center;
    font-size: 15px; padding: 13px 20px;
}
.btn-cancelar {
    width: 100%; justify-content: center;
    font-size: 14px; padding: 10px 18px;
}

.mt-2 { margin-top: 8px; }
</style>
<?php $__env->stopPush(); ?>


<?php $__env->startPush('scripts'); ?>
<script>
    // ══════════════════════════════════════════════════════════
    // CONFIGURAÇÕES E ESTADO GLOBAL DO FORMULÁRIO
    // ══════════════════════════════════════════════════════════
    const URL_TIPOS_JSON = "<?php echo e(url('documentos/tipos-json')); ?>";
    
    let arquivosSelecionados = [];
    let documentosObrigatoriosAtuais = [];
    let debounceTimer = null;

    // Elementos DOM cacheáveis
    const acInput = document.getElementById('acInput');
    const acDropdown = document.getElementById('acDropdown');
    const acSpinner = document.getElementById('acSpinner');
    const acClear = document.getElementById('acClear');
    const servicoId = document.getElementById('servicoId');
    const setorDestino = document.getElementById('setorDestino');
    const depDestinoId = document.getElementById('depDestinoId');
    const docsAlert = document.getElementById('docsAlert');
    const docsAlertList = document.getElementById('docsAlertList');
    const docsAlertEmpty = document.getElementById('docsAlertEmpty');

    // ══════════════════════════════════════════════════════════
    // 1. AUTOCOMPLETE DE SERVIÇOS (MATCHING COM CLASSES CSS CORRIGIDO)
    // ══════════════════════════════════════════════════════════
    acInput.addEventListener('input', function() {
        const busca = this.value.trim();
        clearTimeout(debounceTimer);

        if (busca.length < 2) {
            fecharDropdown();
            acClear.style.display = busca.length > 0 ? 'block' : 'none';
            return;
        }

        acClear.style.display = 'block';
        acSpinner.classList.add('--ativo'); 

        // Debounce de 300ms para evitar requisições excessivas
        debounceTimer = setTimeout(() => {
            fetch(`${URL_TIPOS_JSON}?q=${encodeURIComponent(busca)}`)
                .then(res => res.json())
                .then(dados => renderizarDropdown(dados))
                .catch(err => console.error("Erro ao buscar serviços:", err))
                .finally(() => acSpinner.classList.remove('--ativo'));
        }, 300);
    });

    function renderizarDropdown(itens) {
        acDropdown.innerHTML = '';
        if (itens.length === 0) {
            acDropdown.innerHTML = `<div class="ac-vazio">Nenhum serviço encontrado</div>`;
            acDropdown.classList.add('--aberto');
            return;
        }

        itens.forEach(item => {
            const div = document.createElement('div');
            div.className = 'ac-item';
            // Injeta as classes mapeadas no CSS (.ac-item-nome, .ac-item-desc, .ac-item-setor)
            div.innerHTML = `
                <div class="ac-item-nome">${item.nome}</div>
                ${item.descricao ? `<div class="ac-item-desc">${item.descricao}</div>` : ''}
                <div class="ac-item-setor">${item.setor_nome || 'Setor não definido'}</div>
            `;
            div.addEventListener('click', () => selecionarServico(item));
            acDropdown.appendChild(div);
        });
        acDropdown.classList.add('--aberto'); 
    }

    function selecionarServico(item) {
        acInput.value = item.nome;
        acInput.classList.add('--selected');
        servicoId.value = item.id;
        fecharDropdown();

        // Vincula as informações do Setor/Departamento automaticamente
        setorDestino.value = item.setor_nome || '—';
        depDestinoId.value = item.setor_id || '';

        // Altera a UI das tiras informativas e badges
        document.getElementById('badgeAutoSetor').style.display = 'inline-block';
        document.getElementById('hintSetor').innerText = "Setor vinculado por regra de negócio.";

        // Preenche o Strip do Bloco 1
        document.getElementById('siSetor').innerText = item.setor_nome || '—';
        document.getElementById('siCargo').innerText = item.cargo_responsavel || '—';
        document.getElementById('siSla').innerText = item.sla_label || '—';
        document.getElementById('servicoInfoStrip').style.display = 'flex';

        // Atualiza o Resumo Lateral
        document.getElementById('rServico').innerText = item.nome;
        document.getElementById('rDestino').innerText = item.setor_nome || '—';
        document.getElementById('rCargo').innerText = item.cargo_responsavel || '—';
        document.getElementById('rSla').innerText = item.sla_label || '—';
        document.getElementById('resumoVazio').style.display = 'none';
        document.getElementById('resumoConteudo').style.display = 'block';

        // Busca a lista dinâmica de requisitos de documentos
        carregarRequisitosDocumentos(item.id);
    }

    function limparServico() {
        acInput.value = '';
        acInput.classList.remove('--selected');
        servicoId.value = '';
        setorDestino.value = '';
        depDestinoId.value = '';
        acClear.style.display = 'none';
        document.getElementById('badgeAutoSetor').style.display = 'none';
        document.getElementById('hintSetor').innerText = "Será preenchido ao selecionar o serviço";
        document.getElementById('servicoInfoStrip').style.display = 'none';
        
        document.getElementById('resumoConteudo').style.display = 'none';
        document.getElementById('resumoVazio').style.display = 'block';
        docsAlert.style.display = 'none';
        fecharDropdown();
        documentosObrigatoriosAtuais = [];
        atualizarSelectsDeTipos();
    }

    function fecharDropdown() {
        acDropdown.classList.remove('--aberto');
    }

    // Fecha dropdown se clicar fora
    document.addEventListener('click', function(e) {
        if (!document.getElementById('acContainer').contains(e.target)) {
            fecharDropdown();
        }
    });

    // ══════════════════════════════════════════════════════════
    // 2. REQUISITOS DINÂMICOS DE DOCUMENTOS
    // ══════════════════════════════════════════════════════════
    function carregarRequisitosDocumentos(tipoId) {
        fetch(`/documentos/${tipoId}/requisitos`)
            .then(res => res.json())
            .then(dados => {
                docsAlertList.innerHTML = '';
                documentosObrigatoriosAtuais = dados.documentos_obrigatorios || [];

                if (documentosObrigatoriosAtuais.length > 0) {
                    documentosObrigatoriosAtuais.forEach(doc => {
                        const li = document.createElement('li');
                        li.innerText = doc;
                        docsAlertList.appendChild(li);
                    });
                    docsAlertEmpty.style.display = 'none';
                    docsAlertList.style.display = 'flex';
                } else {
                    docsAlertEmpty.style.display = 'block';
                    docsAlertList.style.display = 'none';
                }
                docsAlert.style.display = 'block';
                
                // Força atualização dos selects nos uploads já inseridos
                atualizarSelectsDeTipos();
            })
            .catch(err => console.error("Erro ao carregar requisitos:", err));
    }

    // ══════════════════════════════════════════════════════════
    // 3. UPLOAD DE ARQUIVOS (MAINTAINING STRUCTURAL INTEGRITY)
    // ══════════════════════════════════════════════════════════
    const uploadZone = document.getElementById('uploadZone');

    function handleDrop(e) {
        e.preventDefault();
        uploadZone.classList.remove('--over');
        if (e.dataTransfer.files ? e.dataTransfer.files.length > 0 : false) {
            adicionarArquivos(e.dataTransfer.files);
        }
    }

    function adicionarArquivos(files) {
        const dt = new DataTransfer();
        
        // Mantém arquivos anteriores no input nativo do formulário
        arquivosSelecionados.forEach(f => dt.items.add(f));

        // Filtra e adiciona os novos
        for (let i = 0; i < files.length; i++) {
            const arquivo = files[i];
            
            // Validação simples de tamanho (10 MB)
            if (arquivo.size > 10 * 1024 * 1024) {
                alert(`O arquivo "${arquivo.name}" excede o limite máximo de 10 MB.`);
                continue;
            }
            
            arquivosSelecionados.push(arquivo);
            dt.items.add(arquivo);
        }

        document.getElementById('fileInput').files = dt.files;
        renderizarListaArquivos();
    }

    function removerArquivo(index) {
        arquivosSelecionados.splice(index, 1);
        const dt = new DataTransfer();
        arquivosSelecionados.forEach(f => dt.items.add(f));
        document.getElementById('fileInput').files = dt.files;
        
        renderizarListaArquivos();
    }

    function renderizarListaArquivos() {
        const container = document.getElementById('uploadList');
        const hiddenContainer = document.getElementById('hiddenInputs');
        
        container.innerHTML = '';
        hiddenContainer.innerHTML = '';
        
        document.getElementById('rAnexos').innerText = arquivosSelecionados.length;

        arquivosSelecionados.forEach((arq, index) => {
            const tamanhoMB = (arq.size / (1024 * 1024)).toFixed(2);
            const item = document.createElement('div');
            item.className = 'upload-item';
            
            // Layout HTML corrigido para refletir exatamente os nós do seu CSS
            item.innerHTML = `
                <span class="upload-item-icone">📄</span>
                <div class="upload-item-corpo">
                    <div class="upload-item-nome" title="${arq.name}">${arq.name}</div>
                    <div class="upload-item-meta">${tamanhoMB} MB</div>
                    <div class="upload-item-pendente">⚠️ Aguardando validação</div>
                    
                    <select class="upload-item-select p-select-tipo-anexo" data-index="${index}" onchange="atualizarHiddenTipo(${index}, this.value)">
                        <option value="outros">Outros Documentos / Geral</option>
                    </select>
                </div>
                <button type="button" class="upload-item-remove" onclick="removerArquivo(${index})">✕</button>
            `;
            container.appendChild(item);

            // Cria o input oculto padrão que o Laravel lerá no request como tipos_anexo[n]
            const hidden = document.createElement('input');
            hidden.type = 'hidden';
            hidden.name = `tipos_anexo[${index}]`;
            hidden.id = `hidden_tipo_${index}`;
            hidden.value = 'outros';
            hiddenContainer.appendChild(hidden);
        });

        atualizarSelectsDeTipos();
    }

    function atualizarHiddenTipo(index, valor) {
        const input = document.getElementById(`hidden_tipo_${index}`);
        if (input) input.value = valor;
    }

    function atualizarSelectsDeTipos() {
        const selects = document.querySelectorAll('.p-select-tipo-anexo');
        
        selects.forEach(select => {
            const index = select.getAttribute('data-index');
            const valorSalvo = document.getElementById(`hidden_tipo_${index}`)?.value || 'outros';
            
            // Reseta mantendo a opção geral padrão
            select.innerHTML = `<option value="outros">Outros Documentos / Geral</option>`;
            
            // Popula as opções baseado nos requisitos obrigatórios atuais mapeados pelo serviço
            if (documentosObrigatoriosAtuais.length > 0) {
                const grupo = document.createElement('optgroup');
                grupo.label = "Documentos Exigidos para o Serviço";
                
                documentosObrigatoriosAtuais.forEach(doc => {
                    const opt = document.createElement('option');
                    opt.value = doc;
                    opt.innerText = doc;
                    grupo.appendChild(opt);
                });
                select.appendChild(grupo);
            }
            
            // Restaura o valor selecionado
            select.value = valorSalvo;
        });
    }

    // Intercepta cliques nos selects internos da dropzone para não disparar o seletor de arquivos de fundo
    document.getElementById('uploadList').addEventListener('click', function(e) {
        if (e.target.tagName === 'SELECT' || e.target.tagName === 'BUTTON' || e.target.tagName === 'OPTION') {
            e.stopPropagation();
        }
    });
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\Controle-de-Entrada-de-Documentos-main\sced\resources\views/processos/create.blade.php ENDPATH**/ ?>