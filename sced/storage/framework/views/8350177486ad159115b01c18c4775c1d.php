
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Relatório SCED</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11px;
            color: #1e293b;
            background: #fff;
        }

        /* Cabeçalho */
        .header {
            background: #0f2744;
            color: white;
            padding: 20px 24px;
            margin-bottom: 20px;
        }

        .header-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        .header h1 {
            font-size: 18px;
            font-weight: bold;
            letter-spacing: 1px;
        }

        .header .sub {
            font-size: 10px;
            color: rgba(255,255,255,0.6);
            margin-top: 2px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .header-info {
            text-align: right;
            font-size: 10px;
            color: rgba(255,255,255,0.7);
            line-height: 1.6;
        }

        /* Filtros aplicados */
        .filtros {
            margin: 0 24px 16px;
            padding: 10px 14px;
            background: #f1f5f9;
            border-radius: 6px;
            border-left: 3px solid #2563eb;
        }

        .filtros-title {
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #94a3b8;
            margin-bottom: 4px;
        }

        .filtros-linha {
            font-size: 10px;
            color: #475569;
        }

        /* Resumo numérico */
        .resumo {
            display: flex;
            gap: 12px;
            margin: 0 24px 20px;
        }

        .resumo-item {
            flex: 1;
            text-align: center;
            padding: 10px 8px;
            border-radius: 6px;
            border: 1px solid #e2e8f0;
        }

        .resumo-valor {
            font-size: 20px;
            font-weight: bold;
            line-height: 1;
        }

        .resumo-label {
            font-size: 9px;
            color: #94a3b8;
            margin-top: 3px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Tabela */
        .tabela-container {
            margin: 0 24px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead th {
            background: #1a3f6f;
            color: white;
            padding: 8px 10px;
            text-align: left;
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            font-weight: bold;
        }

        tbody td {
            padding: 8px 10px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 10px;
            vertical-align: middle;
        }

        tbody tr:nth-child(even) {
            background: #f8fafc;
        }

        .protocolo {
            font-family: 'Courier New', monospace;
            font-size: 10px;
            color: #2563eb;
            font-weight: bold;
        }

        .badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 9px;
            font-weight: bold;
        }

        .badge-recebido    { background: #eff6ff; color: #2563eb; }
        .badge-em_analise  { background: #fffbeb; color: #d97706; }
        .badge-encaminhado { background: #ecfeff; color: #0891b2; }
        .badge-finalizado  { background: #f0fdf4; color: #059669; }

        /* Rodapé */
        .footer {
            position: fixed;
            bottom: 0;
            left: 0; right: 0;
            text-align: center;
            font-size: 9px;
            color: #94a3b8;
            padding: 8px 24px;
            border-top: 1px solid #e2e8f0;
            background: white;
        }

        .sem-dados {
            text-align: center;
            padding: 40px;
            color: #94a3b8;
            font-size: 13px;
        }
    </style>
</head>
<body>


<div class="header">
    <div class="header-top">
        <div>
            <h1>📂 SCED</h1>
            <div class="sub">Relatório de Documentos</div>
        </div>
        <div class="header-info">
            Gerado em: <?php echo e(now()->format('d/m/Y \à\s H:i')); ?><br>
            Usuário: <?php echo e(auth()->user()->nome); ?><br>
            Total de registros: <?php echo e($documentos->count()); ?>

        </div>
    </div>
</div>


<div class="filtros">
    <div class="filtros-title">Filtros aplicados</div>
    <div class="filtros-linha">
        <?php if($request->status): ?>
            Status: <strong><?php echo e(['recebido'=>'Recebido','em_analise'=>'Em Análise','encaminhado'=>'Encaminhado','finalizado'=>'Finalizado'][$request->status]); ?></strong> &nbsp;|&nbsp;
        <?php endif; ?>
        <?php if($request->tipo_documento_id): ?>
            Tipo: <strong><?php echo e(\App\Models\TipoDocumento::find($request->tipo_documento_id)?->nome); ?></strong> &nbsp;|&nbsp;
        <?php endif; ?>
        <?php if($request->data_inicio && $request->data_fim): ?>
            Período: <strong><?php echo e(\Carbon\Carbon::parse($request->data_inicio)->format('d/m/Y')); ?></strong>
            até <strong><?php echo e(\Carbon\Carbon::parse($request->data_fim)->format('d/m/Y')); ?></strong>
        <?php endif; ?>
        <?php if(!$request->status && !$request->tipo_documento_id && !$request->data_inicio): ?>
            Sem filtros — todos os documentos
        <?php endif; ?>
    </div>
</div>


<?php
    $contadores = $documentos->groupBy('status')->map->count();
?>
<div class="resumo">
    <div class="resumo-item" style="border-color:#bfdbfe;">
        <div class="resumo-valor" style="color:#2563eb;"><?php echo e($contadores['recebido'] ?? 0); ?></div>
        <div class="resumo-label">Recebidos</div>
    </div>
    <div class="resumo-item" style="border-color:#fde68a;">
        <div class="resumo-valor" style="color:#d97706;"><?php echo e($contadores['em_analise'] ?? 0); ?></div>
        <div class="resumo-label">Em Análise</div>
    </div>
    <div class="resumo-item" style="border-color:#a5f3fc;">
        <div class="resumo-valor" style="color:#0891b2;"><?php echo e($contadores['encaminhado'] ?? 0); ?></div>
        <div class="resumo-label">Encaminhados</div>
    </div>
    <div class="resumo-item" style="border-color:#bbf7d0;">
        <div class="resumo-valor" style="color:#059669;"><?php echo e($contadores['finalizado'] ?? 0); ?></div>
        <div class="resumo-label">Finalizados</div>
    </div>
    <div class="resumo-item" style="border-color:#cbd5e1; background:#f1f5f9;">
        <div class="resumo-valor" style="color:#1e293b;"><?php echo e($documentos->count()); ?></div>
        <div class="resumo-label">Total</div>
    </div>
</div>


<div class="tabela-container">
    <?php if($documentos->isEmpty()): ?>
        <div class="sem-dados">Nenhum documento encontrado com os filtros informados.</div>
    <?php else: ?>
    <table>
        <thead>
            <tr>
                <th>Protocolo</th>
                <th>Tipo</th>
                <th>Assunto</th>
                <th>Remetente</th>
                <th>Destino</th>
                <th>Recebido em</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $documentos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $doc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td><span class="protocolo"><?php echo e($doc->numero_protocolo); ?></span></td>
                <td><?php echo e($doc->tipoDocumento->nome); ?></td>
                <td><?php echo e(\Illuminate\Support\Str::limit($doc->assunto, 40)); ?></td>
                <td><?php echo e($doc->remetente); ?></td>
                <td><?php echo e($doc->setor_destino); ?></td>
                <td><?php echo e(\Carbon\Carbon::parse($doc->data_recebimento)->format('d/m/Y')); ?></td>
                <td>
                    <span class="badge badge-<?php echo e($doc->status); ?>">
                        <?php echo e(['recebido'=>'Recebido','em_analise'=>'Em Análise','encaminhado'=>'Encaminhado','finalizado'=>'Finalizado'][$doc->status]); ?>

                    </span>
                </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
    <?php endif; ?>
</div>


<div class="footer">
    SCED — Sistema de Controle de Entrada de Documentos &nbsp;|&nbsp;
    Documento confidencial — uso interno &nbsp;|&nbsp;
    Gerado automaticamente em <?php echo e(now()->format('d/m/Y H:i')); ?>

</div>

</body>
</html>
<?php /**PATH C:\xampp\htdocs\Controle-de-Entrada-de-Documentos\sced\resources\views/relatorios/pdf.blade.php ENDPATH**/ ?>