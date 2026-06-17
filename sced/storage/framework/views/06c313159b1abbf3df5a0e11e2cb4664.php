

<?php $__env->startSection('title', 'Dashboard'); ?>
<?php $__env->startSection('subtitle', 'Visão geral do sistema'); ?>

<?php $__env->startSection('content'); ?>

<style>
    /* Cards modernos */
    .dashboard-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 20px;
        margin-bottom: 32px;
    }
    .stat-card {
        background: var(--branco);
        border-radius: 20px;
        padding: 20px;
        box-shadow: var(--sombra-card);
        border: 1px solid var(--cinza-200);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: var(--sombra-hover);
    }
    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
    }
    .stat-card.primary::before { background: var(--azul-claro); }
    .stat-card.info::before { background: var(--ciano); }
    .stat-card.warning::before { background: var(--amarelo); }
    .stat-card.danger::before { background: var(--vermelho); }
    .stat-card.success::before { background: var(--verde); }
    
    .stat-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 16px;
    }
    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
    }
    .stat-icon.primary { background: rgba(37,99,235,.1); color: var(--azul-claro); }
    .stat-icon.info { background: rgba(6,182,212,.1); color: var(--ciano); }
    .stat-icon.warning { background: rgba(245,158,11,.1); color: var(--amarelo); }
    .stat-icon.danger { background: rgba(239,68,68,.1); color: var(--vermelho); }
    .stat-icon.success { background: rgba(16,185,129,.1); color: var(--verde); }
    
    .stat-value {
        font-size: 32px;
        font-weight: 700;
        color: var(--cinza-800);
        line-height: 1.2;
    }
    .stat-label {
        font-size: 12px;
        color: var(--cinza-400);
        margin-top: 6px;
    }
    
    /* Gráficos lado a lado */
    .charts-row {
        display: grid;
        grid-template-columns: 1fr 1.5fr;
        gap: 24px;
        margin-bottom: 32px;
    }
    .chart-card {
        background: var(--branco);
        border-radius: 20px;
        border: 1px solid var(--cinza-200);
        padding: 20px;
        box-shadow: var(--sombra-card);
        transition: all 0.3s ease;
    }
    .chart-card:hover {
        box-shadow: var(--sombra-hover);
    }
    .chart-title {
        font-size: 14px;
        font-weight: 600;
        color: var(--cinza-600);
        margin-bottom: 20px;
        padding-bottom: 12px;
        border-bottom: 1px solid var(--cinza-200);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        text-align: center;
    }
    
    /* Gráfico de rosca */
    .doughnut-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }
    .doughnut-wrapper {
        position: relative;
        width: 200px;
        height: 200px;
        margin: 0 auto 20px;
    }
    .doughnut-center {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        text-align: center;
    }
    .doughnut-center .total {
        font-size: 28px;
        font-weight: 700;
        color: var(--azul-escuro);
    }
    .doughnut-center .label {
        font-size: 10px;
        color: var(--cinza-400);
    }
    
    /* Legenda */
    .legend-top {
        display: flex;
        justify-content: center;
        flex-wrap: wrap;
        gap: 16px;
        margin-bottom: 20px;
        padding-bottom: 12px;
        border-bottom: 1px solid var(--cinza-100);
    }
    .legend-item {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 11px;
        font-weight: 500;
        color: var(--cinza-600);
    }
    .legend-color {
        width: 12px;
        height: 12px;
        border-radius: 3px;
    }
    .legend-color.novo { background: #2563eb; }
    .legend-color.em_analise { background: #d97706; }
    .legend-color.pendente { background: #92400e; }
    .legend-color.finalizado { background: #059669; }
    .legend-color.desativado { background: #64748b; }
    
    /* Tabela de processos recentes */
    .recent-card {
        background: var(--branco);
        border-radius: 20px;
        border: 1px solid var(--cinza-200);
        overflow: hidden;
        margin-bottom: 24px;
    }
    .recent-header {
        padding: 16px 20px;
        border-bottom: 1px solid var(--cinza-200);
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
    }
    .recent-title {
        font-size: 14px;
        font-weight: 600;
        color: var(--cinza-600);
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    /* Badge de status */
    .badge-status {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
    }
    .badge-status::before {
        content: "●";
        font-size: 7px;
    }
    .badge-novo { background: #eff6ff; color: #2563eb; }
    .badge-em_analise { background: #fffbeb; color: #d97706; }
    .badge-pendente { background: #fef3c7; color: #92400e; }
    .badge-finalizado { background: #f0fdf4; color: #059669; }
    .badge-desativado { background: #f1f5f9; color: #64748b; }
    
    /* Tabela responsiva */
    .table-responsive {
        overflow-x: auto;
    }
    .recent-table {
        width: 100%;
        border-collapse: collapse;
    }
    .recent-table thead th {
        background: var(--cinza-100);
        padding: 12px 16px;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--cinza-500);
        border-bottom: 1px solid var(--cinza-200);
    }
    .recent-table tbody td {
        padding: 12px 16px;
        border-bottom: 1px solid var(--cinza-100);
        font-size: 13px;
        vertical-align: middle;
    }
    .recent-table tbody tr:hover {
        background: var(--cinza-100);
    }
    
    .protocolo-codigo {
        font-family: monospace;
        background: var(--cinza-100);
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
    }
    
    .btn-ver {
        padding: 5px 14px;
        background: transparent;
        border: 1.5px solid var(--azul-claro);
        color: var(--azul-claro);
        border-radius: 8px;
        font-size: 12px;
        transition: all 0.2s;
        display: inline-block;
        text-decoration: none;
    }
    .btn-ver:hover {
        background: var(--azul-claro);
        color: white;
        text-decoration: none;
    }
    
    /* Responsividade */
    @media (max-width: 768px) {
        .charts-row {
            grid-template-columns: 1fr;
        }
        .dashboard-stats {
            gap: 12px;
        }
        .stat-card {
            padding: 14px;
        }
        .stat-value {
            font-size: 24px;
        }
        .legend-top {
            gap: 10px;
        }
        .legend-item {
            font-size: 9px;
        }
    }
</style>


<div class="dashboard-stats">
    <div class="stat-card primary">
        <div class="stat-header">
            <div class="stat-icon primary">📊</div>
        </div>
        <div class="stat-value"><?php echo e($kpis['total'] ?? 0); ?></div>
        <div class="stat-label">Meus Processos</div>
    </div>
    
    <div class="stat-card info">
        <div class="stat-header">
            <div class="stat-icon info">🆕</div>
        </div>
        <div class="stat-value"><?php echo e($kpis['novo'] ?? 0); ?></div>
        <div class="stat-label">Processos Novos</div>
    </div>
    
    <div class="stat-card warning">
        <div class="stat-header">
            <div class="stat-icon warning">⚙️</div>
        </div>
        <div class="stat-value"><?php echo e($kpis['em_analise'] ?? 0); ?></div>
        <div class="stat-label">Em Análise</div>
    </div>
    
    <div class="stat-card danger">
        <div class="stat-header">
            <div class="stat-icon danger">⏳</div>
        </div>
        <div class="stat-value"><?php echo e($kpis['pendente'] ?? 0); ?></div>
        <div class="stat-label">Pendentes</div>
    </div>
    
    <div class="stat-card success">
        <div class="stat-header">
            <div class="stat-icon success">✅</div>
        </div>
        <div class="stat-value"><?php echo e($kpis['finalizado'] ?? 0); ?></div>
        <div class="stat-label">Finalizados</div>
    </div>
</div>


<div class="charts-row">
    
    <div class="chart-card">
        <div class="chart-title">
            🍩 Processos por Status
        </div>
        <div class="legend-top">
            <div class="legend-item">
                <div class="legend-color novo"></div>
                <span>Novos (<?php echo e($kpis['novo'] ?? 0); ?>)</span>
            </div>
            <div class="legend-item">
                <div class="legend-color em_analise"></div>
                <span>Em Análise (<?php echo e($kpis['em_analise'] ?? 0); ?>)</span>
            </div>
            <div class="legend-item">
                <div class="legend-color pendente"></div>
                <span>Pendentes (<?php echo e($kpis['pendente'] ?? 0); ?>)</span>
            </div>
            <div class="legend-item">
                <div class="legend-color finalizado"></div>
                <span>Finalizados (<?php echo e($kpis['finalizado'] ?? 0); ?>)</span>
            </div>
            <div class="legend-item">
                <div class="legend-color desativado"></div>
                <span>Desativados (<?php echo e($kpis['desativado'] ?? 0); ?>)</span>
            </div>
        </div>
        <div class="doughnut-container">
            <div class="doughnut-wrapper">
                <canvas id="statusChart" width="200" height="200"></canvas>
                <div class="doughnut-center">
                    <div class="total"><?php echo e($kpis['total'] ?? 0); ?></div>
                    <div class="label">Total</div>
                </div>
            </div>
        </div>
    </div>

    
    <div class="chart-card">
        <div class="chart-title">
            📊 Processos por Mês
        </div>
        <canvas id="monthlyChart" style="width:100%; height:280px;"></canvas>
    </div>
</div>


<div class="recent-card">
    <div class="recent-header">
        <div class="recent-title">
            🕐 Processos Recentes
        </div>
        <a href="<?php echo e(route('documentos.index')); ?>" class="btn-outline-sced" style="padding: 6px 14px; font-size: 12px;">
            Ver todos →
        </a>
    </div>
    <div class="table-responsive">
        <table class="recent-table">
            <thead>
                <tr>
                    <th>Protocolo</th>
                    <th>Serviço</th>
                    <th>Solicitante</th>
                    <th>Status</th>
                    <th>Abertura</th>
                    <th class="text-center">Ação</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $processosRecentes ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $processo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td>
                        <span class="protocolo-codigo"><?php echo e($processo->numero_protocolo); ?></span>
                    </td>
                    <td><?php echo e($processo->tipoDocumento->nome ?? '-'); ?></td>
                    <td><?php echo e($processo->remetente); ?></td>
                    <td>
                        <span class="badge-status badge-<?php echo e($processo->status); ?>">
                            <?php echo e($processo->label_status); ?>

                        </span>
                    </td>
                    <td><?php echo e($processo->created_at->format('d/m/Y H:i')); ?></td>
                    <td class="text-center">
                        <a href="<?php echo e(route('documentos.show', $processo)); ?>" class="btn-ver">
                            Ver →
                        </a>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="6" class="text-center py-5">
                        <div class="text-muted">
                            <div class="fs-1 mb-2">📭</div>
                            Nenhum processo encontrado
                        </div>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gráfico de Rosca
    const statusCtx = document.getElementById('statusChart').getContext('2d');
    new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: ['Novos', 'Em Análise', 'Pendentes', 'Finalizados', 'Desativados'],
            datasets: [{
                data: [
                    <?php echo e($kpis['novo'] ?? 0); ?>,
                    <?php echo e($kpis['em_analise'] ?? 0); ?>,
                    <?php echo e($kpis['pendente'] ?? 0); ?>,
                    <?php echo e($kpis['finalizado'] ?? 0); ?>,
                    <?php echo e($kpis['desativado'] ?? 0); ?>

                ],
                backgroundColor: ['#2563eb', '#d97706', '#92400e', '#059669', '#64748b'],
                borderWidth: 0,
                borderRadius: 10,
                cutout: '65%'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const total = <?php echo e($kpis['total'] ?? 0); ?>;
                            const value = context.parsed;
                            const percent = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                            return `${context.label}: ${value} (${percent}%)`;
                        }
                    }
                }
            }
        }
    });

    // Gráfico de Barras
    const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
    new Chart(monthlyCtx, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($meses ?? ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez']); ?>,
            datasets: [{
                label: 'Meus Processos',
                data: <?php echo json_encode($processosPorMesArray ?? []); ?>,
                backgroundColor: 'rgba(37,99,235,0.7)',
                borderRadius: 8,
                borderSkipped: false,
                barPercentage: 0.7,
                categoryPercentage: 0.8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return `${context.parsed.y} processo(s)`;
                        }
                    }
                }
            },
            scales: {
                y: { 
                    beginAtZero: true, 
                    grid: { color: '#e2e8f0' },
                    ticks: { precision: 0 }
                },
                x: { grid: { display: false } }
            }
        }
    });
});
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\Controle-de-Entrada-de-Documentos\sced\resources\views/dashboard.blade.php ENDPATH**/ ?>