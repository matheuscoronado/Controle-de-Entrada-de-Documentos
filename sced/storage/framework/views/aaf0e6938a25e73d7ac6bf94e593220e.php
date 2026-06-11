<?php $__env->startSection('title', 'Dashboard'); ?>
<?php $__env->startSection('subtitle', 'Visão operacional em tempo real'); ?>

<?php $__env->startSection('topbar-actions'); ?>
    <a href="<?php echo e(route('documentos.create')); ?>" class="btn-primary-sced">
        ➕ Novo Processo
    </a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>


<div class="row g-3 mb-4" id="kpiRow">
    <?php
        $kpiConfig = [
            ['key'=>'total',      'icone'=>'📂', 'cor'=>'azul',    'label'=>'Total de Processos'],
            ['key'=>'novo',       'icone'=>'🆕', 'cor'=>'ciano',   'label'=>'Novos (aguardando)'],
            ['key'=>'em_analise', 'icone'=>'🔍', 'cor'=>'amarelo', 'label'=>'Em Análise'],
            ['key'=>'pendente',   'icone'=>'⏳', 'cor'=>'vermelho','label'=>'Pendentes'],
            ['key'=>'finalizado', 'icone'=>'✅', 'cor'=>'verde',   'label'=>'Finalizados'],
        ];
    ?>
    <?php $__currentLoopData = $kpiConfig; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="col-6 col-md-4 col-lg">
        <div class="kpi-card" id="kpi-<?php echo e($k['key']); ?>">
            <div class="kpi-icon <?php echo e($k['cor']); ?>"><?php echo e($k['icone']); ?></div>
            <div>
                <div class="kpi-valor" id="kv-<?php echo e($k['key']); ?>"><?php echo e($kpis[$k['key']] ?? 0); ?></div>
                <div class="kpi-label"><?php echo e($k['label']); ?></div>
            </div>
        </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>


<div class="row g-3 mb-4">

    
    <div class="col-12 col-lg-8">
        <div class="card-sced card-body-sced" style="height:260px;">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;">
                <strong style="font-size:14px;color:var(--azul-escuro);">📈 Volume Diário (14 dias)</strong>
                <span id="indLive" style="font-size:11px;color:var(--cinza-400);">Atualizando...</span>
            </div>
            <canvas id="chartVolume" style="width:100%;height:190px;"></canvas>
        </div>
    </div>

    
    <div class="col-12 col-lg-4">
        <div class="card-sced card-body-sced" style="height:260px;">
            <strong style="font-size:14px;color:var(--azul-escuro);display:block;margin-bottom:12px;">
                🍩 Por Status
            </strong>
            <div style="display:flex;align-items:center;gap:16px;height:195px;">
                <canvas id="chartStatus" style="max-width:130px;max-height:130px;flex-shrink:0;"></canvas>
                <div id="legendStatus" style="font-size:12px;display:flex;flex-direction:column;gap:7px;min-width:0;"></div>
            </div>
        </div>
    </div>

</div>


<div class="row g-3 mb-4">

    
    <div class="col-12 col-md-6">
        <div class="card-sced card-body-sced">
            <strong style="font-size:14px;color:var(--azul-escuro);display:block;margin-bottom:16px;">
                🏢 Por Setor
            </strong>
            <div id="barrasSetor"></div>
        </div>
    </div>

    
    <div class="col-12 col-md-6">
        <div class="card-sced card-body-sced">
            <strong style="font-size:14px;color:var(--azul-escuro);display:block;margin-bottom:16px;">
                👤 Por Responsável (ativos)
            </strong>
            <div id="barrasResponsavel"></div>
        </div>
    </div>

</div>


<div class="row g-3">

    
    <div class="col-12 col-lg-7">
        <div class="card-sced" style="display:flex;flex-direction:column;height:100%;">
            <div class="card-header-sced" style="padding-bottom:14px;">
                <div>
                    <strong style="font-size:14px;color:var(--azul-escuro);">🎯 Fila de Atribuição</strong>
                    <div style="font-size:11px;color:var(--cinza-400);margin-top:2px;">
                        Processos novos aguardando um responsável
                    </div>
                </div>
                <span id="countFila" style="font-size:12px;font-weight:700;color:var(--azul-claro);background:#eff6ff;padding:3px 10px;border-radius:20px;">
                    <?php echo e($filaAtribuicao->count()); ?>

                </span>
            </div>

            <div style="overflow-x:auto;">
                <table class="tabela-sced" id="tabelaFila">
                    <thead>
                        <tr>
                            <th>Protocolo</th>
                            <th>Serviço</th>
                            <th>Solicitante</th>
                            <th>Aguardando</th>
                            <th style="text-align:center;">Atribuir</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $filaAtribuicao; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $doc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr id="fila-row-<?php echo e($doc->id); ?>">
                            <td><span class="protocolo-codigo"><?php echo e($doc->numero_protocolo); ?></span></td>
                            <td style="font-size:12px;font-weight:600;max-width:120px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                                <?php echo e($doc->tipoDocumento->nome); ?>

                            </td>
                            <td style="font-size:12px;max-width:110px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                                <?php echo e($doc->remetente); ?>

                            </td>
                            <td style="font-size:11px;color:var(--cinza-400);white-space:nowrap;">
                                <?php echo e($doc->created_at->diffForHumans()); ?>

                            </td>
                            <td style="text-align:center;">
                                <button type="button"
                                        class="btn-primary-sced"
                                        style="padding:5px 11px;font-size:11px;"
                                        onclick="abrirModalAtribuir(<?php echo e($doc->id); ?>, '<?php echo e($doc->numero_protocolo); ?>')">
                                    Atribuir →
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="5" style="text-align:center;padding:32px;color:var(--cinza-400);">
                                <div style="font-size:24px;margin-bottom:6px;">🎉</div>
                                Nenhum processo na fila!
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <?php if($filaAtribuicao->count() > 0): ?>
            <div style="padding:12px 22px;border-top:1px solid var(--cinza-200);text-align:right;">
                <a href="<?php echo e(route('documentos.index')); ?>?status=novo" class="btn-outline-sced" style="font-size:12px;">
                    Ver todos →
                </a>
            </div>
            <?php endif; ?>
        </div>
    </div>

    
    <div class="col-12 col-lg-5">
        <div class="card-sced" style="height:100%;">
            <div class="card-header-sced" style="padding-bottom:14px;">
                <strong style="font-size:14px;color:var(--azul-escuro);">📌 Meus Processos</strong>
                <a href="<?php echo e(route('documentos.index')); ?>" class="btn-outline-sced" style="font-size:11px;padding:4px 10px;">
                    Ver todos
                </a>
            </div>

            <div style="padding:0 0 4px;">
                <?php $__empty_1 = true; $__currentLoopData = $meusProcessos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $doc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <a href="<?php echo e(route('documentos.show', $doc)); ?>"
                   style="display:flex;align-items:center;gap:12px;padding:12px 22px;border-bottom:1px solid var(--cinza-200);text-decoration:none;transition:background .15s;"
                   onmouseover="this.style.background='var(--cinza-100)'"
                   onmouseout="this.style.background=''">
                    <?php $cores = \App\Models\Documento::STATUS_CORES[$doc->status] ?? []; ?>
                    <div style="width:6px;height:36px;border-radius:3px;flex-shrink:0;
                                background:<?php echo e($cores['color'] ?? '#94a3b8'); ?>;opacity:.8;"></div>
                    <div style="flex:1;min-width:0;">
                        <div style="font-size:12px;font-weight:600;color:var(--cinza-800);overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                            <?php echo e($doc->tipoDocumento->nome); ?>

                        </div>
                        <div style="font-size:11px;color:var(--cinza-400);margin-top:2px;">
                            <span class="protocolo-codigo"><?php echo e($doc->numero_protocolo); ?></span>
                            · <?php echo e($doc->atribuido_em ? $doc->atribuido_em->diffForHumans() : '—'); ?>

                        </div>
                    </div>
                    <span style="font-size:11px;font-weight:700;padding:2px 8px;border-radius:10px;flex-shrink:0;
                                 background:<?php echo e($cores['bg'] ?? '#f1f5f9'); ?>;color:<?php echo e($cores['color'] ?? '#64748b'); ?>;">
                        <?php echo e(\App\Models\Documento::STATUS[$doc->status] ?? $doc->status); ?>

                    </span>
                </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div style="padding:32px;text-align:center;color:var(--cinza-400);">
                    <div style="font-size:24px;margin-bottom:6px;">📭</div>
                    <div style="font-size:13px;">Nenhum processo atribuído a você.</div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

</div>


<div class="modal-overlay" id="modalOverlay" style="display:none;">
    <div class="modal-sced">
        <div class="modal-header">
            <div>
                <div style="font-size:16px;font-weight:700;color:var(--azul-escuro);">👤 Atribuir Processo</div>
                <div style="font-size:12px;color:var(--cinza-400);margin-top:2px;" id="modalProtocolo">—</div>
            </div>
            <button onclick="fecharModal()" style="background:none;border:none;font-size:20px;cursor:pointer;color:var(--cinza-400);">✕</button>
        </div>

        <div style="padding:20px;">
            <div class="mb-3">
                <label class="form-label-sced">Responsável <span style="color:var(--vermelho)">*</span></label>
                <select id="selectAnalista" class="form-input-sced">
                    <option value="">Carregando analistas...</option>
                </select>
                <div style="font-size:11px;color:var(--cinza-400);margin-top:4px;" id="cargaAnalista"></div>
            </div>

            <div id="modalError" class="alert-sced alert-error" style="display:none;"></div>

            <div style="display:flex;gap:10px;justify-content:flex-end;margin-top:20px;">
                <button onclick="fecharModal()" class="btn-secondary-sced">Cancelar</button>
                <button onclick="confirmarAtribuicao()" class="btn-primary-sced" id="btnConfirmar">
                    ✅ Confirmar Atribuição
                </button>
            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>


<?php $__env->startPush('styles'); ?>
<style>
/* Modal */
.modal-overlay {
    position: fixed; inset: 0; z-index: 500;
    background: rgba(15,39,68,.45);
    display: flex; align-items: center; justify-content: center;
    animation: fadeIn .18s ease;
}
.modal-sced {
    background: var(--branco); border-radius: var(--radius);
    width: 100%; max-width: 440px;
    box-shadow: 0 24px 64px rgba(0,0,0,.2);
    animation: scaleUp .2s ease;
}
.modal-header {
    display: flex; align-items: flex-start; justify-content: space-between;
    padding: 20px 20px 0;
}
@keyframes fadeIn  { from { opacity:0; } to { opacity:1; } }
@keyframes scaleUp { from { opacity:0; transform:scale(.96); } to { opacity:1; transform:none; } }

/* Barras horizontais customizadas */
.barra-item { display:flex;align-items:center;gap:10px;margin-bottom:12px; }
.barra-nome { font-size:12px;color:var(--cinza-600);width:130px;flex-shrink:0;overflow:hidden;text-overflow:ellipsis;white-space:nowrap; }
.barra-track { flex:1;height:8px;background:var(--cinza-200);border-radius:10px;overflow:hidden; }
.barra-fill  { height:100%;border-radius:10px;transition:width .6s ease; }
.barra-qtd   { font-size:12px;font-weight:700;color:var(--cinza-800);width:24px;text-align:right;flex-shrink:0; }

.mb-3 { margin-bottom: 12px; }
</style>
<?php $__env->stopPush(); ?>


<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
// ─────────────────────────────────────────────────────────────
// ESTADO
// ─────────────────────────────────────────────────────────────
const CSRF     = document.querySelector('meta[name="csrf-token"]').content;
let chartVol   = null;
let chartStat  = null;
let docIdAtual = null;

// Paleta por status
const STATUS_CORES = {
    novo:       { bg: '#eff6ff', cor: '#2563eb' },
    em_analise: { bg: '#fffbeb', cor: '#d97706' },
    pendente:   { bg: '#fef3c7', cor: '#92400e' },
    finalizado: { bg: '#f0fdf4', cor: '#059669' },
    desativado: { bg: '#f1f5f9', cor: '#64748b' },
};
const STATUS_LABELS = {
    novo: 'Novo', em_analise: 'Em Análise',
    pendente: 'Pendente', finalizado: 'Finalizado', desativado: 'Desativado',
};

// ─────────────────────────────────────────────────────────────
// INICIALIZAÇÃO DOS GRÁFICOS (com dados do Blade)
// ─────────────────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
    buscarMetricas(); // busca inicial
    setInterval(buscarMetricas, 30000); // atualiza a cada 30s
});

async function buscarMetricas() {
    try {
        const r    = await fetch('/api/dashboard/metricas');
        const data = await r.json();
        atualizarKpis(data.kpis);
        renderChartVolume(data.volume_diario);
        renderChartStatus(data.por_status);
        renderBarras('barrasSetor',       data.por_setor,       '#2563eb');
        renderBarras('barrasResponsavel', data.por_responsavel, '#10b981');
        document.getElementById('indLive').textContent = 'Atualizado ' + new Date().toLocaleTimeString('pt-BR');
    } catch (e) {
        console.error('Erro ao buscar métricas:', e);
    }
}

// ── KPIs ─────────────────────────────────────────────────────
function atualizarKpis(kpis) {
    Object.entries(kpis).forEach(([key, val]) => {
        const el = document.getElementById('kv-' + key);
        if (el) animarContador(el, parseInt(el.textContent) || 0, val);
    });
}

function animarContador(el, de, para) {
    if (de === para) return;
    const dur = 500, steps = 20, inc = (para - de) / steps;
    let cur = de, i = 0;
    const t = setInterval(() => {
        cur += inc; i++;
        el.textContent = Math.round(cur);
        if (i >= steps) { el.textContent = para; clearInterval(t); }
    }, dur / steps);
}

// ── Gráfico de Volume Diário ──────────────────────────────────
function renderChartVolume(dados) {
    const ctx = document.getElementById('chartVolume').getContext('2d');
    const labels = dados.map(d => d.dia);
    const vals   = dados.map(d => d.total);

    if (chartVol) {
        chartVol.data.labels   = labels;
        chartVol.data.datasets[0].data = vals;
        chartVol.update('active');
        return;
    }

    chartVol = new Chart(ctx, {
        type: 'bar',
        data: {
            labels,
            datasets: [{
                label: 'Processos abertos',
                data: vals,
                backgroundColor: 'rgba(37,99,235,.15)',
                borderColor:     '#2563eb',
                borderWidth:     2,
                borderRadius:    6,
                borderSkipped:   false,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: ctx => ` ${ctx.parsed.y} processo(s)`
                    }
                }
            },
            scales: {
                x: { grid: { display: false }, ticks: { font: { family: 'Sora', size: 11 }, color: '#94a3b8' } },
                y: { grid: { color: '#f1f5f9' }, ticks: { font: { family: 'Sora', size: 11 }, color: '#94a3b8', precision: 0 }, beginAtZero: true }
            }
        }
    });
}

// ── Gráfico de Rosca (status) ─────────────────────────────────
function renderChartStatus(porStatus) {
    const ctx    = document.getElementById('chartStatus').getContext('2d');
    const chaves = Object.keys(STATUS_LABELS).filter(k => porStatus[k]);
    const labels = chaves.map(k => STATUS_LABELS[k]);
    const vals   = chaves.map(k => porStatus[k] || 0);
    const cores  = chaves.map(k => STATUS_CORES[k]?.cor || '#94a3b8');
    const total  = vals.reduce((a,b) => a+b, 0) || 1;

    if (chartStat) {
        chartStat.data.labels = labels;
        chartStat.data.datasets[0].data = vals;
        chartStat.data.datasets[0].backgroundColor = cores;
        chartStat.update('active');
    } else {
        chartStat = new Chart(ctx, {
            type: 'doughnut',
            data: { labels, datasets: [{ data: vals, backgroundColor: cores, borderWidth: 2, borderColor: '#fff', hoverOffset: 4 }] },
            options: {
                responsive: true, maintainAspectRatio: true, cutout: '70%',
                plugins: { legend: { display: false }, tooltip: { callbacks: { label: c => ` ${c.label}: ${c.parsed}` } } }
            }
        });
    }

    // Legenda manual
    const leg = document.getElementById('legendStatus');
    leg.innerHTML = chaves.map((k,i) => `
        <div style="display:flex;align-items:center;gap:6px;">
            <span style="width:10px;height:10px;border-radius:2px;background:${cores[i]};flex-shrink:0;"></span>
            <span style="font-size:11px;color:var(--cinza-600);">${labels[i]}</span>
            <span style="margin-left:auto;font-size:11px;font-weight:700;color:var(--cinza-800);">${vals[i]}</span>
        </div>
    `).join('');
}

// ── Barras horizontais customizadas ──────────────────────────
function renderBarras(containerId, dados, cor) {
    const el  = document.getElementById(containerId);
    if (!dados || !Object.keys(dados).length) {
        el.innerHTML = '<div style="text-align:center;padding:24px;color:var(--cinza-400);font-size:13px;">Sem dados</div>';
        return;
    }
    const max  = Math.max(...Object.values(dados));
    const html = Object.entries(dados).map(([nome, qtd]) => `
        <div class="barra-item">
            <div class="barra-nome" title="${esc(nome)}">${esc(nome)}</div>
            <div class="barra-track">
                <div class="barra-fill" style="width:${Math.round((qtd/max)*100)}%;background:${cor};"></div>
            </div>
            <div class="barra-qtd">${qtd}</div>
        </div>
    `).join('');
    el.innerHTML = html || '<div style="color:var(--cinza-400);font-size:13px;padding:12px 0;">Sem dados</div>';
}

// ─────────────────────────────────────────────────────────────
// MODAL DE ATRIBUIÇÃO
// ─────────────────────────────────────────────────────────────
async function abrirModalAtribuir(docId, protocolo) {
    docIdAtual = docId;
    document.getElementById('modalProtocolo').textContent = 'Processo ' + protocolo;
    document.getElementById('modalError').style.display = 'none';
    document.getElementById('modalOverlay').style.display = 'flex';

    // Carrega analistas via API
    const select = document.getElementById('selectAnalista');
    select.innerHTML = '<option value="">Carregando...</option>';
    select.disabled = true;

    try {
        const r    = await fetch('/api/dashboard/analistas');
        const lista = await r.json();
        select.innerHTML = '<option value="">— Selecione um responsável —</option>';
        lista.forEach(a => {
            const opt = document.createElement('option');
            opt.value = a.id;
            opt.textContent = `${a.nome}${a.cargo ? ' ('+a.cargo+')' : ''}`;
            opt.dataset.carga = a.carga;
            select.appendChild(opt);
        });
        select.disabled = false;
    } catch {
        select.innerHTML = '<option value="">Erro ao carregar analistas</option>';
    }
}

document.addEventListener('DOMContentLoaded', () => {
    document.getElementById('selectAnalista').addEventListener('change', function() {
        const opt  = this.options[this.selectedIndex];
        const hint = document.getElementById('cargaAnalista');
        if (opt.dataset.carga !== undefined && this.value) {
            hint.textContent = opt.dataset.carga + ' processo(s) ativo(s)';
            hint.style.color = opt.dataset.carga > 5 ? 'var(--amarelo)' : 'var(--verde)';
        } else {
            hint.textContent = '';
        }
    });
});

function fecharModal() {
    document.getElementById('modalOverlay').style.display = 'none';
    docIdAtual = null;
}

async function confirmarAtribuicao() {
    const usuarioId = document.getElementById('selectAnalista').value;
    if (!usuarioId) {
        mostrarErroModal('Selecione um responsável.');
        return;
    }

    const btn = document.getElementById('btnConfirmar');
    btn.disabled = true;
    btn.textContent = '⏳ Atribuindo...';

    try {
        const r = await fetch(`/api/processos/${docIdAtual}/atribuir`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
            body: JSON.stringify({ usuario_id: usuarioId }),
        });
        const data = await r.json();

        if (data.error) { mostrarErroModal(data.error); return; }

        // Remove a linha da fila sem reload
        const row = document.getElementById('fila-row-' + docIdAtual);
        if (row) { row.style.opacity = '0'; row.style.transition = 'opacity .3s'; setTimeout(() => row.remove(), 300); }

        fecharModal();
        buscarMetricas(); // atualiza KPIs

        // Toast de sucesso
        mostrarToast(`Processo ${data.protocolo} atribuído a ${data.analista}.`);
    } catch {
        mostrarErroModal('Erro de comunicação. Tente novamente.');
    } finally {
        btn.disabled = false;
        btn.textContent = '✅ Confirmar Atribuição';
    }
}

function mostrarErroModal(msg) {
    const el = document.getElementById('modalError');
    el.textContent = msg;
    el.style.display = 'flex';
}

// ─────────────────────────────────────────────────────────────
// TOAST
// ─────────────────────────────────────────────────────────────
function mostrarToast(msg) {
    const t = document.createElement('div');
    t.style.cssText = `position:fixed;bottom:24px;right:24px;z-index:999;
        background:var(--cinza-800);color:#fff;padding:12px 20px;
        border-radius:var(--radius-sm);font-size:13px;font-weight:500;
        box-shadow:0 8px 24px rgba(0,0,0,.25);animation:slideUp .25s ease;
        max-width:320px;`;
    t.textContent = '✅ ' + msg;
    document.body.appendChild(t);
    setTimeout(() => { t.style.opacity = '0'; t.style.transition = 'opacity .3s'; setTimeout(() => t.remove(), 300); }, 3500);
}

// ─────────────────────────────────────────────────────────────
// UTILITÁRIOS
// ─────────────────────────────────────────────────────────────
function esc(s) {
    return String(s ?? '').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
}

// Fecha modal ao clicar no overlay
document.getElementById('modalOverlay')?.addEventListener('click', function(e) {
    if (e.target === this) fecharModal();
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\Controle-de-Entrada-de-Documentos-main\sced\resources\views/dashboard.blade.php ENDPATH**/ ?>