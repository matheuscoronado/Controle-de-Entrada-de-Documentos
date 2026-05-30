<?php $__env->startSection('title', 'Documentos'); ?>
<?php $__env->startSection('subtitle', 'Consulta e gerenciamento de documentos'); ?>

<?php $__env->startSection('topbar-actions'); ?>
    <a href="<?php echo e(route('documentos.create')); ?>" class="btn-primary-sced">
        ➕ Novo Documento
    </a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>


<div class="filtros-card">
    <form method="GET" action="<?php echo e(route('documentos.index')); ?>">
        <div style="display:flex; align-items:center; gap:8px; margin-bottom:16px;">
            <span style="font-size:14px; font-weight:600; color:var(--azul-escuro);">🔍 Filtros de busca</span>
            <?php if(request()->anyFilled(['protocolo','remetente','tipo_documento_id','status','data_inicio','data_fim'])): ?>
                <a href="<?php echo e(route('documentos.index')); ?>" class="btn-secondary-sced" style="padding:4px 10px; font-size:12px;">
                    ✕ Limpar filtros
                </a>
            <?php endif; ?>
        </div>
        <div class="row g-2">
            <div class="col-12 col-md-2">
                <label>Protocolo</label>
                <input type="text" name="protocolo" class="form-input-sced"
                       placeholder="Ex: 2026-000001"
                       value="<?php echo e(request('protocolo')); ?>">
            </div>
            <div class="col-12 col-md-3">
                <label>Remetente</label>
                <input type="text" name="remetente" class="form-input-sced"
                       placeholder="Nome do remetente"
                       value="<?php echo e(request('remetente')); ?>">
            </div>
            <div class="col-12 col-md-2">
                <label>Tipo</label>
                <select name="tipo_documento_id" class="form-input-sced">
                    <option value="">Todos os tipos</option>
                    <?php $__currentLoopData = $tipos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tipo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($tipo->id); ?>"
                            <?php echo e(request('tipo_documento_id') == $tipo->id ? 'selected' : ''); ?>>
                            <?php echo e($tipo->nome); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="col-12 col-md-2">
                <label>Status</label>
                <select name="status" class="form-input-sced">
                    <option value="">Todos</option>
                    <option value="recebido"    <?php echo e(request('status') == 'recebido'    ? 'selected' : ''); ?>>Recebido</option>
                    <option value="em_analise"  <?php echo e(request('status') == 'em_analise'  ? 'selected' : ''); ?>>Em Análise</option>
                    <option value="encaminhado" <?php echo e(request('status') == 'encaminhado' ? 'selected' : ''); ?>>Encaminhado</option>
                    <option value="finalizado"  <?php echo e(request('status') == 'finalizado'  ? 'selected' : ''); ?>>Finalizado</option>
                </select>
            </div>
            <div class="col-6 col-md-1">
                <label>De</label>
                <input type="date" name="data_inicio" class="form-input-sced"
                       value="<?php echo e(request('data_inicio')); ?>">
            </div>
            <div class="col-6 col-md-1">
                <label>Até</label>
                <input type="date" name="data_fim" class="form-input-sced"
                       value="<?php echo e(request('data_fim')); ?>">
            </div>
            <div class="col-12 col-md-1" style="display:flex; align-items:flex-end;">
                <button type="submit" class="btn-primary-sced" style="width:100%;">
                    Filtrar
                </button>
            </div>
        </div>
    </form>
</div>


<div class="card-sced">
    <div style="display:flex; align-items:center; justify-content:space-between; padding:16px 24px; border-bottom:1px solid var(--cinza-200);">
        <span style="font-size:14px; color:var(--cinza-600);">
            <?php echo e($documentos->total()); ?> documento(s) encontrado(s)
        </span>
    </div>
    <div style="overflow-x:auto;">
        <table class="tabela-sced">
            <thead>
                <tr>
                    <th>Protocolo</th>
                    <th>Tipo</th>
                    <th>Assunto</th>
                    <th>Remetente</th>
                    <th>Destino</th>
                    <th>Recebido em</th>
                    <th>Status</th>
                    <th style="text-align:center;">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $documentos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $doc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td><span class="protocolo-codigo"><?php echo e($doc->numero_protocolo); ?></span></td>
                    <td><?php echo e($doc->tipoDocumento->nome); ?></td>
                    <td style="max-width:220px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">
                        <?php echo e($doc->assunto); ?>

                    </td>
                    <td><?php echo e($doc->remetente); ?></td>
                    <td><?php echo e($doc->setor_destino); ?></td>
                    <td style="white-space:nowrap;">
                        <?php echo e(\Carbon\Carbon::parse($doc->data_recebimento)->format('d/m/Y')); ?>

                    </td>
                    <td>
                        <span class="badge-status badge-<?php echo e($doc->status); ?>">
                            <?php echo e(['recebido'=>'Recebido','em_analise'=>'Em Análise','encaminhado'=>'Encaminhado','finalizado'=>'Finalizado'][$doc->status]); ?>

                        </span>
                    </td>
                    <td style="text-align:center;">
                        <a href="<?php echo e(route('documentos.show', $doc)); ?>" class="btn-outline-sced">
                            👁 Ver
                        </a>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="8" style="text-align:center; padding:48px; color:var(--cinza-400);">
                        <div style="font-size:32px; margin-bottom:8px;">📭</div>
                        Nenhum documento encontrado com os filtros informados.
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    
    <?php if($documentos->hasPages()): ?>
    <div style="padding:16px 24px; border-top:1px solid var(--cinza-200);">
        <?php echo e($documentos->withQueryString()->links()); ?>

    </div>
    <?php endif; ?>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\Controle-de-Entrada-de-Documentos\sced\resources\views/documentos/index.blade.php ENDPATH**/ ?>