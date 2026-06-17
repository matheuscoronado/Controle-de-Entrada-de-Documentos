

<?php $__env->startSection('title', 'Processo ' . $documento->numero_protocolo); ?>
<?php $__env->startSection('subtitle', $documento->tipoDocumento->nome); ?>

<?php $__env->startSection('topbar-actions'); ?>
    <a href="<?php echo e(route('documentos.index')); ?>" class="btn-secondary-sced">← Voltar</a>
    <?php if(in_array('editar', $acoes ?? [])): ?>
        <a href="<?php echo e(route('documentos.edit', $documento)); ?>" class="btn-outline-sced">✏️ Editar</a>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<style>
    .processo-header {
        background: linear-gradient(135deg, var(--azul-escuro) 0%, var(--azul-medio) 100%);
        border-radius: 16px;
        padding: 24px;
        margin-bottom: 24px;
        color: white;
    }
    .processo-header-protocolo {
        font-size: 24px;
        font-weight: 700;
        font-family: monospace;
        margin-bottom: 8px;
    }
    .processo-header-meta {
        font-size: 12px;
        opacity: 0.8;
    }
    .processo-header-status {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 16px;
        border-radius: 30px;
        font-size: 13px;
        font-weight: 600;
        background: rgba(255,255,255,0.2);
    }
    
    .info-card {
        background: var(--branco);
        border-radius: 16px;
        border: 1px solid var(--cinza-200);
        overflow: hidden;
        margin-bottom: 24px;
    }
    .info-card-header {
        padding: 16px 20px;
        background: var(--cinza-100);
        border-bottom: 1px solid var(--cinza-200);
        display: flex;
        align-items: center;
        gap: 10px;
        font-weight: 600;
        font-size: 14px;
        color: var(--azul-escuro);
    }
    .info-card-body {
        padding: 20px;
    }
    
    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
    }
    .info-item {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }
    .info-label {
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--cinza-400);
    }
    .info-value {
        font-size: 14px;
        font-weight: 600;
        color: var(--cinza-800);
    }
    
    .docs-stats {
        display: flex;
        gap: 16px;
        flex-wrap: wrap;
        margin-bottom: 20px;
    }
    .docs-stat {
        flex: 1;
        min-width: 100px;
        background: var(--cinza-100);
        border-radius: 12px;
        padding: 14px;
        text-align: center;
    }
    .docs-stat-value {
        font-size: 24px;
        font-weight: 700;
    }
    .docs-stat-label {
        font-size: 11px;
        color: var(--cinza-400);
        margin-top: 4px;
    }
    .docs-stat.approved .docs-stat-value { color: var(--verde); }
    .docs-stat.rejected .docs-stat-value { color: var(--vermelho); }
    .docs-stat.pending .docs-stat-value { color: var(--amarelo); }
    .docs-stat.total .docs-stat-value { color: var(--azul-claro); }
    
    .documento-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 14px 16px;
        background: var(--cinza-100);
        border-radius: 12px;
        margin-bottom: 10px;
        transition: all 0.2s;
        flex-wrap: wrap;
        gap: 10px;
    }
    .documento-item:hover {
        background: var(--cinza-200);
    }
    .documento-info {
        display: flex;
        align-items: center;
        gap: 12px;
        flex: 1;
        min-width: 250px;
    }
    .documento-icon {
        font-size: 24px;
    }
    .documento-details {
        flex: 1;
    }
    .documento-nome {
        font-weight: 700;
        font-size: 13px;
        color: var(--azul-claro);
        margin-bottom: 2px;
    }
    .documento-meta {
        font-size: 11px;
        color: var(--cinza-400);
        margin-top: 2px;
    }
    .documento-status {
        font-size: 11px;
        font-weight: 600;
        padding: 4px 12px;
        border-radius: 20px;
        white-space: nowrap;
    }
    .documento-status.aprovado { background: #d1fae5; color: #065f46; }
    .documento-status.recusado { background: #fef2f2; color: #991b1b; }
    .documento-status.pendente { background: #fef3c7; color: #92400e; }
    .documento-actions {
        display: flex;
        gap: 8px;
        align-items: center;
    }
    .btn-doc {
        padding: 6px 12px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 500;
        transition: all 0.2s;
        cursor: pointer;
        border: none;
        background: var(--cinza-200);
    }
    .btn-download {
        background: var(--cinza-200);
        color: var(--cinza-600);
    }
    .btn-download:hover {
        background: var(--cinza-300);
    }
    .btn-approve {
        background: #d1fae5;
        color: #065f46;
    }
    .btn-approve:hover {
        background: #a7f3d0;
    }
    .btn-reject {
        background: #fef2f2;
        color: #991b1b;
    }
    .btn-reject:hover {
        background: #fecaca;
    }
    
    .timeline {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    .timeline-item {
        display: flex;
        gap: 14px;
        padding-bottom: 20px;
        position: relative;
    }
    .timeline-item:not(:last-child)::before {
        content: '';
        position: absolute;
        left: 17px;
        top: 35px;
        bottom: 0;
        width: 2px;
        background: var(--cinza-200);
    }
    .timeline-dot {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: var(--azul-claro);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 16px;
        flex-shrink: 0;
        box-shadow: 0 2px 8px rgba(37,99,235,.3);
    }
    .timeline-content {
        flex: 1;
        background: var(--cinza-100);
        border-radius: 12px;
        padding: 12px 16px;
    }
    .timeline-date {
        font-size: 11px;
        color: var(--cinza-400);
        margin-bottom: 4px;
    }
    .timeline-text {
        font-size: 13px;
        color: var(--cinza-800);
    }
    .timeline-user {
        font-size: 11px;
        color: var(--azul-claro);
        margin-top: 4px;
    }
    
    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0,0,0,0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1000;
    }
    .modal-container {
        background: white;
        border-radius: 16px;
        width: 90%;
        max-width: 500px;
        animation: modalFadeIn 0.2s ease;
    }
    @keyframes modalFadeIn {
        from { opacity: 0; transform: scale(0.95); }
        to { opacity: 1; transform: scale(1); }
    }
    .modal-header {
        padding: 20px;
        border-bottom: 1px solid var(--cinza-200);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .modal-body {
        padding: 20px;
    }
    .modal-footer {
        padding: 16px 20px;
        border-top: 1px solid var(--cinza-200);
        display: flex;
        justify-content: flex-end;
        gap: 12px;
    }
    
    .badge-status {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 14px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }
    .badge-status::before {
        content: "●";
        font-size: 8px;
    }
    .badge-novo { background: #eff6ff; color: #2563eb; }
    .badge-em_analise { background: #fffbeb; color: #d97706; }
    .badge-pendente { background: #fef3c7; color: #92400e; }
    .badge-finalizado { background: #f0fdf4; color: #059669; }
    .badge-desativado { background: #f1f5f9; color: #64748b; }
    
    .acao-bloco {
        background: var(--cinza-100);
        border-radius: 12px;
        padding: 16px;
        margin-bottom: 20px;
    }
    .acao-bloco:last-child {
        margin-bottom: 0;
    }
    .acao-bloco-title {
        font-size: 13px;
        font-weight: 600;
        color: var(--azul-escuro);
        margin-bottom: 12px;
        padding-bottom: 8px;
        border-bottom: 1px solid var(--cinza-200);
    }
    
    .btn-acao {
        padding: 10px 16px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 13px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        cursor: pointer;
        transition: all 0.2s;
        border: none;
        width: 100%;
    }
    .btn-assumir { background: var(--azul-claro); color: white; }
    .btn-assumir:hover { background: var(--azul-hover); transform: translateY(-1px); }
    .btn-devolver { background: #fef3c7; color: #92400e; }
    .btn-devolver:hover { background: #fde68a; }
    .btn-finalizar { background: #d1fae5; color: #065f46; }
    .btn-finalizar:hover { background: #a7f3d0; }
    .btn-reenviar { background: #e0e7ff; color: #3730a3; }
    .btn-reenviar:hover { background: #c7d2fe; }
    .btn-desativar { background: #fef2f2; color: #991b1b; }
    .btn-desativar:hover { background: #fecaca; }
    .btn-reabrir { background: #e0e7ff; color: #3730a3; }
    .btn-reabrir:hover { background: #c7d2fe; }
    .btn-atribuir { background: #e0e7ff; color: #3730a3; }
    .btn-atribuir:hover { background: #c7d2fe; transform: translateY(-1px); }
    
    .alert-warning-box {
        background: #fef3c7;
        color: #92400e;
        padding: 12px;
        border-radius: 10px;
        font-size: 12px;
        margin-bottom: 12px;
        border-left: 3px solid #f59e0b;
    }
    
    .acoes-card {
        background: var(--branco);
        border-radius: 16px;
        border: 1px solid var(--cinza-200);
        padding: 20px;
        margin-bottom: 24px;
    }
    .acoes-title {
        font-size: 14px;
        font-weight: 700;
        color: var(--azul-escuro);
        margin-bottom: 16px;
        padding-bottom: 12px;
        border-bottom: 2px solid var(--cinza-200);
    }
    
    /* Estilos para o upload de reenvio */
    .upload-zone-reenvio {
        border: 2px dashed var(--cinza-200);
        border-radius: 10px;
        padding: 20px;
        text-align: center;
        cursor: pointer;
        background: var(--cinza-100);
        transition: all 0.2s;
        margin-bottom: 15px;
    }
    .upload-zone-reenvio:hover {
        border-color: var(--azul-claro);
        background: rgba(37,99,235,.04);
    }
    .reenvio-arquivo-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 12px;
        background: var(--branco);
        border: 1px solid var(--cinza-200);
        border-radius: 10px;
        margin-bottom: 10px;
        flex-wrap: wrap;
        gap: 10px;
    }
    .reenvio-arquivo-info {
        display: flex;
        align-items: center;
        gap: 12px;
        flex: 1;
        flex-wrap: wrap;
    }
    .reenvio-arquivo-nome {
        font-size: 13px;
        font-weight: 500;
        color: var(--cinza-800);
    }
    .reenvio-arquivo-select {
        padding: 8px 12px;
        border-radius: 8px;
        border: 1px solid var(--cinza-200);
        background: white;
        font-size: 12px;
        min-width: 180px;
        cursor: pointer;
    }
    .btn-remove-arquivo {
        background: none;
        border: none;
        cursor: pointer;
        color: var(--vermelho);
        font-size: 18px;
        padding: 5px 10px;
        border-radius: 6px;
        transition: all 0.2s;
    }
    .btn-remove-arquivo:hover {
        background: #fef2f2;
    }
    
    .w-100 { width: 100%; }
    .mt-3 { margin-top: 16px; }
    .mb-2 { margin-bottom: 10px; }
    .mt-2 { margin-top: 10px; }
    .text-center { text-align: center; }
</style>

<div class="container-fluid px-0">


<div class="processo-header">
    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
        <div>
            <div class="processo-header-protocolo">
                📄 <?php echo e($documento->numero_protocolo); ?>

            </div>
            <div class="processo-header-meta">
                Aberto em <?php echo e($documento->created_at->format('d/m/Y \à\s H:i')); ?>

                · por <strong><?php echo e($documento->usuarioRegistro->nome); ?></strong>
            </div>
        </div>
        <div class="d-flex flex-column align-items-end gap-2">
            <span class="processo-header-status">
                <?php
                    $statusIcon = [
                        'novo' => '🆕',
                        'em_analise' => '🔍',
                        'pendente' => '⏳',
                        'finalizado' => '✅',
                        'desativado' => '🚫',
                    ];
                ?>
                <?php echo e($statusIcon[$documento->status] ?? '📋'); ?>

                <?php echo e($documento->label_status); ?>

            </span>
            <?php if($documento->atribuidoA): ?>
                <span class="processo-header-meta">
                    👤 Responsável: <?php echo e($documento->atribuidoA->nome); ?>

                </span>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="row g-4">

    
    <div class="col-12 col-lg-8">

        
        <div class="info-card">
            <div class="info-card-header">📋 Informações do Processo</div>
            <div class="info-card-body">
                <div class="info-grid">
                    <div class="info-item">
                        <span class="info-label">Serviço</span>
                        <span class="info-value"><?php echo e($documento->tipoDocumento->nome); ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Solicitante</span>
                        <span class="info-value"><?php echo e($documento->remetente); ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Setor de Destino</span>
                        <span class="info-value"><?php echo e($documento->setor_destino); ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Data de Abertura</span>
                        <span class="info-value"><?php echo e($documento->created_at->format('d/m/Y H:i')); ?></span>
                    </div>
                    <?php if($documento->descricao): ?>
                    <div class="info-item" style="grid-column: span 2;">
                        <span class="info-label">Descrição</span>
                        <span class="info-value"><?php echo e($documento->descricao); ?></span>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        
        <div class="info-card">
            <div class="info-card-header">📎 Documentos Anexos</div>
            <div class="info-card-body">
                
                <?php
                    $totalDocs = $documento->anexos->count();
                    $aprovados = $documento->anexos->where('status_validacao', 'aprovado')->count();
                    $recusados = $documento->anexos->where('status_validacao', 'rejeitado')->count();
                    $pendentes = $documento->anexos->where('status_validacao', 'pendente')->count();
                ?>
                
                <div class="docs-stats">
                    <div class="docs-stat total"><div class="docs-stat-value"><?php echo e($totalDocs); ?></div><div class="docs-stat-label">Total</div></div>
                    <div class="docs-stat approved"><div class="docs-stat-value"><?php echo e($aprovados); ?></div><div class="docs-stat-label">Aprovados</div></div>
                    <div class="docs-stat rejected"><div class="docs-stat-value"><?php echo e($recusados); ?></div><div class="docs-stat-label">Recusados</div></div>
                    <div class="docs-stat pending"><div class="docs-stat-value"><?php echo e($pendentes); ?></div><div class="docs-stat-label">Pendentes</div></div>
                </div>

                <?php $__currentLoopData = $documento->anexos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $anexo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="documento-item">
                    <div class="documento-info">
                        <div class="documento-icon"><?php echo e(str_contains($anexo->tipo_mime, 'image') ? '🖼️' : (str_ends_with($anexo->nome_arquivo, '.pdf') ? '📕' : '📄')); ?></div>
                        <div class="documento-details">
                            <div class="documento-nome"><?php echo e($anexo->label_tipo_anexo); ?></div>
                            <div class="documento-meta">Arquivo: <?php echo e($anexo->nome_arquivo); ?> · <?php echo e(number_format($anexo->tamanho_bytes / 1024, 1)); ?> KB</div>
                            <?php if($anexo->observacao_validacao): ?><div class="documento-meta" style="color: var(--cinza-500);">Motivo: "<?php echo e($anexo->observacao_validacao); ?>"</div><?php endif; ?>
                        </div>
                        <div class="documento-status <?php echo e($anexo->status_validacao); ?>">
                            <?php if($anexo->status_validacao == 'aprovado'): ?> ✅ Aprovado
                            <?php elseif($anexo->status_validacao == 'rejeitado'): ?> ❌ Recusado
                            <?php else: ?> ⏳ Pendente <?php endif; ?>
                        </div>
                    </div>
                    <div class="documento-actions">
                        <a href="<?php echo e(Storage::url($anexo->caminho_arquivo)); ?>" target="_blank" class="btn-doc btn-download">⬇️</a>
                        <?php if(in_array('validar_anexo', $acoes ?? [])): ?>
                            <?php if($anexo->status_validacao == 'pendente'): ?>
                                <form method="POST" action="<?php echo e(route('documentos.anexo.validar', ['documento' => $documento->id, 'anexo' => $anexo->id])); ?>" style="display: inline-block;" onsubmit="return confirm('Aprovar este documento?')">
                                    <?php echo csrf_field(); ?>
                                    <input type="hidden" name="status_validacao" value="aprovado">
                                    <button type="submit" class="btn-doc btn-approve">✅</button>
                                </form>
                                <button type="button" class="btn-doc btn-reject" onclick="abrirModalRejeicao(<?php echo e($anexo->id); ?>)">❌</button>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                <?php if($documento->anexos->isEmpty()): ?>
                <div class="text-center py-4 text-muted">📭 Nenhum documento anexado</div>
                <?php endif; ?>
            </div>
        </div>

        
        <div class="info-card">
            <div class="info-card-header">🕐 Histórico de Movimentações</div>
            <div class="info-card-body">
                <ul class="timeline">
                    <?php $__currentLoopData = $documento->historicos->sortByDesc('created_at'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $hist): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li class="timeline-item">
                        <div class="timeline-dot"><?php echo e($timelineIcons[$hist->status_novo] ?? '●'); ?></div>
                        <div class="timeline-content">
                            <div class="timeline-date"><?php echo e(\Carbon\Carbon::parse($hist->created_at)->format('d/m/Y H:i')); ?></div>
                            <div class="timeline-text">
                                <?php if($hist->status_anterior): ?><span class="badge-status badge-<?php echo e($hist->status_anterior); ?>" style="font-size: 11px;"><?php echo e($statusLabel[$hist->status_anterior] ?? $hist->status_anterior); ?></span> → <?php endif; ?>
                                <span class="badge-status badge-<?php echo e($hist->status_novo); ?>" style="font-size: 11px;"><?php echo e($statusLabel[$hist->status_novo] ?? $hist->status_novo); ?></span>
                            </div>
                            <div class="timeline-user">Por: <?php echo e($hist->usuario->nome ?? '—'); ?></div>
                            <?php if($hist->observacoes): ?><div style="font-size: 12px; color: var(--cinza-500); margin-top: 6px;"><?php echo e($hist->observacoes); ?></div><?php endif; ?>
                        </div>
                    </li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        </div>

    </div>

    
    <div class="col-12 col-lg-4">

        
        <div class="acoes-card">
            <div class="acoes-title">⚡ Ações Disponíveis</div>

            
            <?php if(in_array('atribuir', $acoes ?? [])): ?>
            <div class="acao-bloco">
                <div class="acao-bloco-title">👥 Atribuir Processo</div>
                <button type="button" class="btn-acao btn-atribuir w-100" onclick="abrirModalAtribuir(<?php echo e($documento->id); ?>)">
                    📤 Atribuir Processo
                </button>
            </div>
            <?php endif; ?>

            <?php if(in_array('assumir', $acoes ?? [])): ?>
            <div class="acao-bloco">
                <div class="acao-bloco-title">🎯 Assumir Processo</div>
                <form method="POST" action="<?php echo e(route('documentos.assumir', $documento)); ?>">
                    <?php echo csrf_field(); ?>
                    <textarea name="observacoes" class="form-input-sced mb-2" rows="2" placeholder="Observações (opcional)"></textarea>
                    <button type="submit" class="btn-acao btn-assumir w-100">🎯 Assumir Processo</button>
                </form>
            </div>
            <?php endif; ?>

            <?php if(in_array('devolver', $acoes ?? [])): ?>
            <div class="acao-bloco">
                <div class="acao-bloco-title">↩️ Devolver ao Solicitante</div>
                <form method="POST" action="<?php echo e(route('documentos.devolver', $documento)); ?>">
                    <?php echo csrf_field(); ?>
                    <textarea name="motivo" class="form-input-sced mb-2" rows="3" placeholder="Motivo da devolução (obrigatório)" required></textarea>
                    <button type="submit" class="btn-acao btn-devolver w-100">↩️ Devolver ao Solicitante</button>
                </form>
            </div>
            <?php endif; ?>

            <?php if(in_array('finalizar', $acoes ?? [])): ?>
            <div class="acao-bloco">
                <div class="acao-bloco-title">✅ Finalizar Processo</div>
                <?php if($pendentes > 0): ?>
                    <div class="alert-warning-box">⚠️ Existem <?php echo e($pendentes); ?> documento(s) pendente(s) de validação. Finalize-os antes de concluir o processo.</div>
                <?php else: ?>
                    <form method="POST" action="<?php echo e(route('documentos.finalizar', $documento)); ?>">
                        <?php echo csrf_field(); ?>
                        <textarea name="observacoes" class="form-input-sced mb-2" rows="2" placeholder="Observações finais (opcional)"></textarea>
                        <button type="submit" class="btn-acao btn-finalizar w-100">✅ Finalizar Processo</button>
                    </form>
                <?php endif; ?>
            </div>
            <?php endif; ?>

            
            <?php if(in_array('retornar', $acoes ?? [])): ?>
            <div class="acao-bloco">
                <div class="acao-bloco-title">📤 Reenviar</div>
                <form method="POST" action="<?php echo e(route('documentos.retornar', $documento)); ?>" enctype="multipart/form-data" id="formReenvio">
                    <?php echo csrf_field(); ?>
                    <div class="mb-2">
                        <label class="form-label-sced">Descreva os ajustes realizados</label>
                        <textarea name="observacoes" class="form-input-sced" rows="3" placeholder="Informe quais correções ou complementos foram feitos..."></textarea>
                    </div>
                    
                    <div class="mb-2">
                        <label class="form-label-sced">Novos anexos</label>
                        <div class="upload-zone-reenvio" onclick="document.getElementById('fileInputReenvio').click()">
                            📎 Clique para selecionar arquivos (pode selecionar vários)
                        </div>
                        <input type="file" id="fileInputReenvio" multiple style="display:none" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" onchange="adicionarArquivosReenvio(this.files)">
                        <div id="reenvioLista" class="mt-2"></div>
                    </div>
                    
                    <button type="submit" class="btn-acao btn-reenviar w-100">📤 Reenviar</button>
                </form>
            </div>
            <?php endif; ?>

        </div>

        
        <?php if(in_array('desativar', $acoes ?? []) || in_array('reabrir', $acoes ?? [])): ?>
        <div class="acoes-card">
            <div class="acoes-title">🔧 Ações Administrativas</div>

            <?php if(in_array('desativar', $acoes ?? [])): ?>
            <div class="acao-bloco">
                <div class="acao-bloco-title">🚫 Desativar Processo</div>
                <form method="POST" action="<?php echo e(route('documentos.desativar', $documento)); ?>">
                    <?php echo csrf_field(); ?>
                    <textarea name="motivo" class="form-input-sced mb-2" rows="3" placeholder="Motivo da desativação (obrigatório)" required></textarea>
                    <button type="submit" class="btn-acao btn-desativar w-100">🚫 Desativar Processo</button>
                </form>
            </div>
            <?php endif; ?>

            <?php if(in_array('reabrir', $acoes ?? [])): ?>
            <div class="acao-bloco">
                <div class="acao-bloco-title">🔄 Reabrir Processo</div>
                <form method="POST" action="<?php echo e(route('documentos.reabrir', $documento)); ?>">
                    <?php echo csrf_field(); ?>
                    <textarea name="observacoes" class="form-input-sced mb-2" rows="2" placeholder="Justificativa para reabertura..."></textarea>
                    <button type="submit" class="btn-acao btn-reabrir w-100">🔄 Reabrir Processo</button>
                </form>
            </div>
            <?php endif; ?>

        </div>
        <?php endif; ?>

        
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('alterarStatusManual', $documento)): ?>
        <div class="acoes-card">
            <div class="acoes-title">🔄 Alterar Status Manual</div>
            <form method="POST" action="<?php echo e(route('documentos.status-manual', $documento)); ?>">
                <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                <select name="status" class="form-input-sced mb-2">
                    <option value="novo" <?php echo e($documento->status=='novo' ? 'selected' : ''); ?>>🆕 Novo</option>
                    <option value="em_analise" <?php echo e($documento->status=='em_analise' ? 'selected' : ''); ?>>🔍 Em Análise</option>
                    <option value="pendente" <?php echo e($documento->status=='pendente' ? 'selected' : ''); ?>>⏳ Pendente</option>
                    <option value="finalizado" <?php echo e($documento->status=='finalizado' ? 'selected' : ''); ?>>✅ Finalizado</option>
                    <option value="desativado" <?php echo e($documento->status=='desativado' ? 'selected' : ''); ?>>🚫 Desativado</option>
                </select>
                <textarea name="observacoes" class="form-input-sced mb-2" rows="2" placeholder="Motivo da alteração..."></textarea>
                <button type="submit" class="btn-primary-sced w-100">Salvar Status</button>
            </form>
        </div>
        <?php endif; ?>

    </div>

</div>
</div>


<div id="modalRejeitar" class="modal-overlay" style="display: none;">
    <div class="modal-container">
        <div class="modal-header">
            <h4>❌ Recusar Documento</h4>
            <button onclick="fecharModalRejeicao()" style="background: none; border: none; font-size: 20px; cursor: pointer;">✕</button>
        </div>
        <form id="formRejeitar" method="POST">
            <?php echo csrf_field(); ?>
            <div class="modal-body">
                <label class="form-label-sced">Motivo da recusa <span style="color: var(--vermelho);">*</span></label>
                <textarea name="observacao" id="motivoRecusa" class="form-input-sced" rows="4" placeholder="Descreva o motivo da recusa..." required></textarea>
                <input type="hidden" name="status_validacao" value="rejeitado">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-secondary-sced" onclick="fecharModalRejeicao()">Cancelar</button>
                <button type="submit" class="btn-primary-sced" style="background: var(--vermelho);">Confirmar Recusa</button>
            </div>
        </form>
    </div>
</div>


<div id="modalAtribuir" class="modal-overlay" style="display: none;">
    <div class="modal-container">
        <div class="modal-header">
            <h4>👥 Atribuir Processo</h4>
            <button onclick="fecharModalAtribuir()" style="background: none; border: none; font-size: 20px; cursor: pointer;">✕</button>
        </div>
        <form id="formAtribuir" method="POST">
            <?php echo csrf_field(); ?>
            <div class="modal-body">
                <label class="form-label-sced">Selecione o responsável</label>
                <select name="usuario_id" id="selectUsuarioAtribuir" class="form-input-sced" required>
                    <option value="">Carregando...</option>
                </select>
                <div class="mt-2">
                    <label class="form-label-sced">Observações (opcional)</label>
                    <textarea name="observacoes" class="form-input-sced" rows="3" placeholder="Justificativa para a atribuição..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-secondary-sced" onclick="fecharModalAtribuir()">Cancelar</button>
                <button type="submit" class="btn-primary-sced" style="background: var(--azul-claro);">📤 Atribuir Processo</button>
            </div>
        </form>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
// Variáveis globais
let reenvioArquivos = [];
let todosDocumentos = [];
let anexoIdAtual = null;
let processoIdAtual = null;

// Carregar todos os documentos cadastrados
async function carregarDocumentos() {
    try {
        const response = await fetch('/api/documentos/todos', {
            headers: { 'Accept': 'application/json' }
        });
        todosDocumentos = await response.json();
        console.log('Documentos carregados:', todosDocumentos);
    } catch (e) {
        console.error('Erro ao carregar documentos:', e);
    }
}

// Adicionar arquivos ao reenvio
function adicionarArquivosReenvio(files) {
    Array.from(files).forEach(file => {
        reenvioArquivos.push({
            file: file,
            tipoDocumentoId: '',
            tipoDocumentoNome: ''
        });
    });
    atualizarListaReenvio();
    sincronizarFormReenvio();
}

// Atualizar a lista visual de arquivos
function atualizarListaReenvio() {
    const container = document.getElementById('reenvioLista');
    if (!container) return;
    
    container.innerHTML = '';
    
    reenvioArquivos.forEach((item, index) => {
        const div = document.createElement('div');
        div.className = 'reenvio-arquivo-item';
        div.setAttribute('data-index', index);
        
        // Monta o select de tipos
        let selectHtml = '<select class="reenvio-arquivo-select" data-index="' + index + '" onchange="mudarTipoReenvio(this)">';
        selectHtml += '<option value="">Selecione o tipo de documento</option>';
        todosDocumentos.forEach(doc => {
            const selected = item.tipoDocumentoId == doc.id ? 'selected' : '';
            selectHtml += `<option value="${doc.id}" data-nom="${escapeHtml(doc.nome)}" ${selected}>${escapeHtml(doc.nome)}</option>`;
        });
        selectHtml += '</select>';
        
        div.innerHTML = `
            <div class="reenvio-arquivo-info">
                <span class="reenvio-arquivo-nome">📄 ${escapeHtml(item.file.name)} (${(item.file.size/1024).toFixed(1)} KB)</span>
                ${selectHtml}
            </div>
            <button type="button" class="btn-remove-arquivo" onclick="removerArquivoReenvio(${index})">✕</button>
        `;
        container.appendChild(div);
    });
    
    if (reenvioArquivos.length === 0) {
        container.innerHTML = '<div class="text-muted text-center" style="padding: 10px;">Nenhum arquivo selecionado</div>';
    }
}

// Mudar o tipo do documento no reenvio
function mudarTipoReenvio(select) {
    const index = parseInt(select.getAttribute('data-index'));
    const selectedOption = select.options[select.selectedIndex];
    const tipoDocumentoId = select.value;
    const tipoDocumentoNome = selectedOption ? selectedOption.getAttribute('data-nom') : '';
    
    if (reenvioArquivos[index]) {
        reenvioArquivos[index].tipoDocumentoId = tipoDocumentoId;
        reenvioArquivos[index].tipoDocumentoNome = tipoDocumentoNome;
    }
    sincronizarFormReenvio();
}

// Remover arquivo do reenvio
function removerArquivoReenvio(index) {
    reenvioArquivos = reenvioArquivos.filter((_, i) => i !== index);
    atualizarListaReenvio();
    sincronizarFormReenvio();
}

// Sincronizar o formulário com os dados atuais
function sincronizarFormReenvio() {
    const form = document.getElementById('formReenvio');
    if (!form) return;
    
    // Remove inputs hidden antigos
    const oldInputs = form.querySelectorAll('.hidden-tipo-input');
    oldInputs.forEach(input => input.remove());
    
    // Cria novo DataTransfer para o input file
    const dt = new DataTransfer();
    reenvioArquivos.forEach(item => {
        dt.items.add(item.file);
    });
    
    const fileInput = document.getElementById('fileInputReenvio');
    if (fileInput) fileInput.files = dt.files;
    
    // Adiciona inputs hidden com os tipos
    reenvioArquivos.forEach((item, i) => {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = `tipos_anexo[${i}]`;
        input.value = item.tipoDocumentoId || 'outros';
        input.className = 'hidden-tipo-input';
        form.appendChild(input);
    });
    
    console.log('Formulário sincronizado:', reenvioArquivos.length, 'arquivo(s)');
}

// Funções do modal de rejeição
function abrirModalRejeicao(anexoId) {
    anexoIdAtual = anexoId;
    const modal = document.getElementById('modalRejeitar');
    const form = document.getElementById('formRejeitar');
    const documentoId = <?php echo e($documento->id ?? 'null'); ?>;
    if (form && documentoId) {
        form.action = `/documentos/${documentoId}/anexos/${anexoId}/validar`;
    }
    if (modal) modal.style.display = 'flex';
}

function fecharModalRejeicao() {
    const modal = document.getElementById('modalRejeitar');
    const motivoRecusa = document.getElementById('motivoRecusa');
    if (modal) modal.style.display = 'none';
    if (motivoRecusa) motivoRecusa.value = '';
    anexoIdAtual = null;
}

// ⭐ FUNÇÃO CORRIGIDA PARA ATRIBUIR PROCESSO
function abrirModalAtribuir(processoId) {
    console.log('abrirModalAtribuir chamado com ID:', processoId);
    
    processoIdAtual = processoId;
    const modal = document.getElementById('modalAtribuir');
    const form = document.getElementById('formAtribuir');
    const select = document.getElementById('selectUsuarioAtribuir');
    
    if (!modal || !form || !select) {
        console.error('Elementos do modal não encontrados');
        return;
    }
    
    form.action = `/documentos/${processoId}/atribuir`;
    select.innerHTML = '<option value="">Carregando usuários...</option>';
    modal.style.display = 'flex';
    
    // Tentar primeiro com a rota do ProcessoController
    fetch(`/documentos/${processoId}/usuarios-atribuicao`, {
        method: 'GET',
        headers: { 
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
            'X-Requested-With': 'XMLHttpRequest'
        },
        credentials: 'same-origin'
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.error) {
            throw new Error(data.error);
        }
        
        if (data.length === 0) {
            select.innerHTML = '<option value="">Nenhum usuário disponível para atribuição</option>';
            return;
        }
        
        let options = '<option value="">Selecione um usuário</option>';
        data.forEach(user => {
            const departamentoTexto = user.departamento_nome || 'Setor não definido';
            options += `<option value="${user.id}">${user.nome} (${user.cargo}) - ${departamentoTexto}</option>`;
        });
        select.innerHTML = options;
    })
    .catch(error => {
        console.error('Erro ao carregar usuários (via ProcessoController):', error);
        
        // Tentar com a rota da API como fallback
        fetch(`/api/usuarios/para-atribuir/${processoId}`, {
            method: 'GET',
            headers: { 
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
            },
            credentials: 'same-origin'
        })
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                throw new Error(data.error);
            }
            
            if (data.length === 0) {
                select.innerHTML = '<option value="">Nenhum usuário disponível para atribuição</option>';
                return;
            }
            
            let options = '<option value="">Selecione um usuário</option>';
            data.forEach(user => {
                const departamentoTexto = user.departamento_nome || 'Setor não definido';
                options += `<option value="${user.id}">${user.nome} (${user.cargo}) - ${departamentoTexto}</option>`;
            });
            select.innerHTML = options;
        })
        .catch(apiError => {
            console.error('Erro ao carregar usuários (via API):', apiError);
            select.innerHTML = '<option value="">Erro ao carregar usuários. Verifique o console.</option>';
        });
    });
}

function fecharModalAtribuir() {
    const modal = document.getElementById('modalAtribuir');
    const select = document.getElementById('selectUsuarioAtribuir');
    if (modal) modal.style.display = 'none';
    if (select) select.innerHTML = '<option value="">Carregando...</option>';
    processoIdAtual = null;
}

function escapeHtml(str) {
    if (!str) return '';
    return String(str).replace(/[&<>]/g, function(m) {
        if (m === '&') return '&amp;';
        if (m === '<') return '&lt;';
        if (m === '>') return '&gt;';
        return m;
    });
}

// Inicializar quando o DOM estiver pronto
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM carregado, inicializando scripts...');
    
    carregarDocumentos();
    
    // Fechar modal ao clicar fora
    const modalRejeitar = document.getElementById('modalRejeitar');
    const modalAtribuir = document.getElementById('modalAtribuir');
    
    if (modalRejeitar) {
        modalRejeitar.addEventListener('click', function(e) {
            if (e.target === this) fecharModalRejeicao();
        });
    }
    
    if (modalAtribuir) {
        modalAtribuir.addEventListener('click', function(e) {
            if (e.target === this) fecharModalAtribuir();
        });
    }
    
    // Verificar se as funções estão disponíveis globalmente
    console.log('Função abrirModalAtribuir disponível:', typeof abrirModalAtribuir === 'function');
});
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\Controle-de-Entrada-de-Documentos\sced\resources\views/processos/show.blade.php ENDPATH**/ ?>