{{-- ============================================================
     resources/views/processos/create.blade.php
     VERSÃO COM SUBMIT CORRIGIDO
     ============================================================ --}}
@extends('layouts.app')
@section('title', 'Novo Processo')
@section('subtitle', 'Abertura de solicitação de serviço')

@section('topbar-actions')
    <a href="{{ route('documentos.index') }}" class="btn-secondary-sced">← Voltar</a>
@endsection

@section('content')

<style>
/* ... manter todos os estilos anteriores ... */
.processo-card {
    background: #ffffff;
    border-radius: 12px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 1px 3px rgba(15,39,68,.08);
    padding: 24px;
    margin-bottom: 24px;
}

.processo-bloco-header {
    display: flex;
    align-items: flex-start;
    gap: 14px;
    margin-bottom: 22px;
    padding-bottom: 18px;
    border-bottom: 1px solid #e2e8f0;
}

.processo-step {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    background: #2563eb;
    color: #fff;
    font-size: 13px;
    font-weight: 700;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.processo-bloco-titulo {
    font-size: 15px;
    font-weight: 700;
    color: #0f2744;
}

.processo-bloco-sub {
    font-size: 12px;
    color: #94a3b8;
    margin-top: 2px;
}

.p-label {
    display: block;
    font-size: 12px;
    font-weight: 600;
    color: #475569;
    text-transform: uppercase;
    letter-spacing: .6px;
    margin-bottom: 7px;
}

.p-req { color: #ef4444; }
.p-hint { font-size: 11px; font-weight: 400; color: #94a3b8; text-transform: none; }

.p-input {
    width: 100%;
    padding: 11px 14px;
    border: 1.5px solid #e2e8f0;
    border-radius: 8px;
    font-family: 'Sora', sans-serif;
    font-size: 14px;
    color: #1e293b;
    background: #ffffff;
    transition: all 0.22s ease;
    outline: none;
}

.p-input:focus {
    border-color: #2563eb;
    box-shadow: 0 0 0 3px rgba(37,99,235,.12);
}

.p-input--locked {
    background: #f1f5f9;
    color: #475569;
    cursor: not-allowed;
}

.p-textarea { resize: vertical; min-height: 90px; }
.p-date-wrap { position: relative; }

.p-lock-badge {
    position: absolute;
    right: 10px;
    top: 50%;
    transform: translateY(-50%);
    font-size: 11px;
    font-weight: 600;
    color: #94a3b8;
    background: #e2e8f0;
    padding: 2px 8px;
    border-radius: 6px;
    pointer-events: none;
}

.p-hint-text { font-size: 11px; color: #94a3b8; margin-top: 5px; }
.p-error { font-size: 12px; color: #ef4444; margin-top: 5px; }

/* Autocomplete */
.ac-container { position: relative; margin-bottom: 0; }
.ac-field-wrap { position: relative; }
.ac-icon {
    position: absolute;
    left: 13px;
    top: 50%;
    transform: translateY(-50%);
    font-size: 15px;
    pointer-events: none;
}

.ac-input {
    width: 100%;
    padding: 11px 40px 11px 40px;
    border: 1.5px solid #e2e8f0;
    border-radius: 8px;
    font-family: 'Sora', sans-serif;
    font-size: 14px;
    color: #1e293b;
    background: #ffffff;
    transition: all 0.22s ease;
    outline: none;
}

.ac-input:focus {
    border-color: #2563eb;
    box-shadow: 0 0 0 3px rgba(37,99,235,.12);
}

.ac-spinner {
    position: absolute;
    right: 40px;
    top: 50%;
    transform: translateY(-50%);
    width: 16px;
    height: 16px;
    border: 2px solid #e2e8f0;
    border-top-color: #2563eb;
    border-radius: 50%;
    animation: girar 0.7s linear infinite;
    display: none;
}

.ac-spinner.--ativo { display: block; }
@keyframes girar { to { transform: translateY(-50%) rotate(360deg); } }

.ac-clear {
    position: absolute;
    right: 10px;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    cursor: pointer;
    font-size: 14px;
    color: #94a3b8;
    padding: 4px 8px;
}

.ac-clear:hover { color: #ef4444; }

.ac-dropdown {
    position: absolute;
    top: calc(100% + 5px);
    left: 0;
    right: 0;
    z-index: 300;
    background: #ffffff;
    border: 1.5px solid #e2e8f0;
    border-radius: 8px;
    box-shadow: 0 4px 24px rgba(15,39,68,.14);
    max-height: 340px;
    overflow-y: auto;
    display: none;
}

.ac-dropdown.--aberto { display: block; }

.ac-item {
    padding: 12px 16px;
    cursor: pointer;
    border-bottom: 1px solid #e2e8f0;
    transition: background .12s;
}

.ac-item:last-child { border-bottom: none; }
.ac-item:hover { background: #f1f5f9; }
.ac-item-nome { font-size: 14px; font-weight: 600; color: #1e293b; }
.ac-item-desc { font-size: 12px; color: #94a3b8; margin-top: 2px; }
.ac-item-setor { font-size: 11px; font-weight: 600; color: #2563eb; margin-top: 4px; }
.ac-vazio { padding: 24px; text-align: center; color: #94a3b8; font-size: 13px; }

/* Upload */
.upload-aviso {
    display: flex;
    gap: 10px;
    align-items: flex-start;
    background: #fffbeb;
    border: 1.5px solid #fde68a;
    border-radius: 8px;
    padding: 12px 16px;
    margin-bottom: 18px;
}

.upload-aviso span { font-size: 16px; }
.upload-aviso p { font-size: 13px; color: #92400e; margin: 0; line-height: 1.5; }

.upload-zone {
    border: 2px dashed #e2e8f0;
    border-radius: 8px;
    padding: 40px 24px;
    text-align: center;
    cursor: pointer;
    background: #f1f5f9;
    transition: all 0.22s ease;
}

.upload-zone:hover {
    border-color: #2563eb;
    background: rgba(37,99,235,.04);
}

.upload-zone-icone { font-size: 38px; margin-bottom: 10px; }
.upload-zone-texto { font-size: 14px; color: #475569; font-weight: 500; }
.upload-zone-link { color: #2563eb; font-weight: 700; text-decoration: underline; }
.upload-zone-formatos { font-size: 11px; color: #94a3b8; margin-top: 6px; }

.upload-item {
    display: flex;
    gap: 12px;
    align-items: flex-start;
    padding: 14px;
    margin-top: 10px;
    background: #ffffff;
    border: 1.5px solid #e2e8f0;
    border-radius: 8px;
}

.upload-item-icone { font-size: 24px; flex-shrink: 0; }
.upload-item-corpo { flex: 1; }
.upload-item-nome { font-size: 13px; font-weight: 600; color: #1e293b; }
.upload-item-meta { font-size: 11px; color: #94a3b8; margin-top: 2px; }

.upload-item-select {
    width: 100%;
    padding: 8px 10px;
    margin-top: 8px;
    border: 1.5px solid #e2e8f0;
    border-radius: 6px;
    font-family: 'Sora', sans-serif;
    font-size: 12px;
    background: #f8fafc;
    cursor: pointer;
}

.upload-item-select:focus {
    border-color: #2563eb;
    outline: none;
}

.upload-item-select.--selecionado {
    background: #d1fae5;
    border-color: #10b981;
    color: #065f46;
    font-weight: 600;
}

.upload-item-remove {
    background: none;
    border: none;
    cursor: pointer;
    color: #94a3b8;
    font-size: 18px;
    padding: 2px 4px;
    flex-shrink: 0;
}

.upload-item-remove:hover { color: #ef4444; }

/* Resumo lateral */
.resumo-card {
    background: #ffffff;
    border-radius: 12px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 1px 3px rgba(15,39,68,.08);
    padding: 22px;
    margin-bottom: 16px;
    position: sticky;
    top: 82px;
}

.resumo-titulo {
    font-size: 13px;
    font-weight: 700;
    color: #0f2744;
    margin-bottom: 18px;
    padding-bottom: 14px;
    border-bottom: 1px solid #e2e8f0;
}

.resumo-vazio { text-align: center; padding: 20px 0 8px; }
.resumo-vazio-icone { font-size: 32px; margin-bottom: 8px; opacity: .35; }
.resumo-vazio p { font-size: 13px; color: #94a3b8; margin: 0; }

.resumo-linha {
    padding: 9px 0;
    border-bottom: 1px solid #e2e8f0;
}

.resumo-linha--last { border-bottom: none; }
.resumo-label { font-size: 10px; font-weight: 600; text-transform: uppercase; letter-spacing: .8px; color: #94a3b8; }
.resumo-valor { font-size: 13px; font-weight: 600; color: #1e293b; }

/* Documentos no resumo */
.resumo-docs-obrigatorios, .resumo-docs-opcionais {
    margin-top: 12px;
    padding-top: 8px;
    border-top: 1px solid #e2e8f0;
}

.resumo-docs-obrigatorios .resumo-label { color: #dc2626; }
.resumo-docs-opcionais .resumo-label { color: #2563eb; }

.resumo-docs-list {
    list-style: none;
    padding: 0;
    margin: 8px 0 0;
}

.resumo-docs-list li {
    font-size: 12px;
    padding: 4px 0;
    display: flex;
    align-items: center;
    gap: 8px;
}

.resumo-docs-list li.pendente { color: #f59e0b; }
.resumo-docs-list li.pendente::before { content: "⚠️"; }
.resumo-docs-list li.ok { color: #10b981; }
.resumo-docs-list li.ok::before { content: "✅"; }

/* Botões */
.acao-card-principal {
    display: flex;
    gap: 12px;
    justify-content: flex-end;
    margin-top: 24px;
    padding-top: 20px;
    border-top: 1px solid #e2e8f0;
}

.btn-abrir { padding: 12px 28px; font-size: 15px; }
.btn-cancelar { padding: 12px 24px; font-size: 14px; }
.bloco-badge-validacao {
    margin-left: auto;
    flex-shrink: 0;
    font-size: 11px;
    font-weight: 600;
    color: #92400e;
    background: #fef3c7;
    padding: 3px 10px;
    border-radius: 20px;
}

.mt-2 { margin-top: 8px; }
</style>

<form method="POST" action="{{ route('documentos.store') }}" enctype="multipart/form-data" id="formProcesso" novalidate>
@csrf

<div class="row g-4">
    <div class="col-lg-8">
        {{-- BLOCO 1: Serviço --}}
        <div class="processo-card mb-4">
            <div class="processo-bloco-header">
                <div class="processo-step">1</div>
                <div>
                    <div class="processo-bloco-titulo">Identificação do Serviço</div>
                    <div class="processo-bloco-sub">Busque e selecione o serviço solicitado</div>
                </div>
            </div>

            <div class="ac-container" id="acContainer">
                <label class="p-label">Serviço <span class="p-req">*</span> <span class="p-hint">— comece a digitar para buscar</span></label>
                <div class="ac-field-wrap" id="acWrap">
                    <span class="ac-icon">🔍</span>
                    <input type="text" id="acInput" class="ac-input" placeholder="Ex: Solicitação de Benefício..." autocomplete="off">
                    <span class="ac-spinner" id="acSpinner"></span>
                    <button type="button" class="ac-clear" id="acClear" onclick="limparServico()" style="display:none">✕</button>
                </div>
                <input type="hidden" name="tipo_documento_id" id="servicoId">
                <div class="ac-dropdown" id="acDropdown"></div>
                @error('tipo_documento_id')<div class="p-error">{{ $message }}</div>@enderror
            </div>
        </div>

        {{-- BLOCO 2: Dados do Solicitante --}}
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
                    <input type="text" name="remetente" class="p-input" placeholder="Nome completo ou razão social" value="{{ old('remetente') }}" required>
                    @error('remetente')<div class="p-error">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-4">
                    <label class="p-label">Data de Abertura</label>
                    <div class="p-date-wrap">
                        <input type="date" name="data_recebimento" class="p-input p-input--locked" value="{{ date('Y-m-d') }}" readonly>
                        <span class="p-lock-badge">🔒 Hoje</span>
                    </div>
                </div>

                <div class="col-12">
                    <label class="p-label">Setor de Destino <span class="p-req">*</span></label>
                    <input type="text" name="setor_destino" id="setorDestino" class="p-input" placeholder="Selecione o serviço para preencher automaticamente" readonly>
                    <input type="hidden" name="departamento_destino_id" id="depDestinoId">
                    <div class="p-hint-text" id="hintSetor">Será preenchido ao selecionar o serviço</div>
                    @error('setor_destino')<div class="p-error">{{ $message }}</div>@enderror
                </div>

                <div class="col-12">
                    <label class="p-label">Descrição / Observações</label>
                    <textarea name="descricao" class="p-input p-textarea" rows="3" placeholder="Detalhes adicionais sobre a solicitação (opcional)...">{{ old('descricao') }}</textarea>
                </div>
            </div>
        </div>

        {{-- BLOCO 3: Upload --}}
        <div class="processo-card mb-4">
            <div class="processo-bloco-header">
                <div class="processo-step">3</div>
                <div>
                    <div class="processo-bloco-titulo">Documentos Anexos</div>
                    <div class="processo-bloco-sub">Adicione os arquivos necessários para o processo</div>
                </div>
                <div class="bloco-badge-validacao">⚠️ Validação manual posterior</div>
            </div>

            <div class="upload-aviso" id="uploadAviso" style="display:none">
                <span>⚠️</span>
                <p><strong>Atenção!</strong> Você precisa anexar todos os documentos obrigatórios antes de abrir o processo.</p>
            </div>

            <div class="upload-zone" id="uploadZone" onclick="document.getElementById('fileInput').click()">
                <div class="upload-zone-icone">📎</div>
                <div class="upload-zone-texto">Arraste arquivos aqui ou <span class="upload-zone-link">clique para selecionar</span></div>
                <div class="upload-zone-formatos">PDF · DOC · DOCX · JPG · PNG — máx. 10 MB por arquivo</div>
            </div>

            <input type="file" id="fileInput" name="anexos[]" multiple style="display:none" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" onchange="adicionarArquivos(this.files)">
            <div id="uploadList"></div>
            <div id="hiddenInputs"></div>
            @error('anexos')<div class="p-error mt-2">{{ $message }}</div>@enderror
        </div>

        <div class="acao-card-principal">
            <button type="button" class="btn-primary-sced btn-abrir" id="btnAbrir" onclick="validarEEnviar()">🚀 Abrir Processo</button>
            <a href="{{ route('documentos.index') }}" class="btn-secondary-sced btn-cancelar">Cancelar</a>
        </div>
    </div>

    {{-- COLUNA LATERAL --}}
    <div class="col-lg-4">
        <div class="resumo-card" id="resumoCard">
            <div class="resumo-titulo">📋 Resumo do Processo</div>
            <div class="resumo-vazio" id="resumoVazio">
                <div class="resumo-vazio-icone">🔍</div>
                <p>Selecione um serviço para ver o resumo</p>
            </div>
            <div id="resumoConteudo" style="display:none">
                <div class="resumo-linha"><span class="resumo-label">Serviço</span><span class="resumo-valor" id="rServico">—</span></div>
                <div class="resumo-linha"><span class="resumo-label">Destino</span><span class="resumo-valor" id="rDestino">—</span></div>
                <div class="resumo-linha"><span class="resumo-label">Responsáveis</span><span class="resumo-valor" id="rCargo">—</span></div>

                <div class="resumo-docs-obrigatorios" id="resumoDocsObrigatorios" style="display:none">
                    <span class="resumo-label">📋 Documentos Obrigatórios</span>
                    <ul class="resumo-docs-list" id="resumoDocsObrigatoriosList"></ul>
                </div>

                <div class="resumo-docs-opcionais" id="resumoDocsOpcionais" style="display:none">
                    <span class="resumo-label">📄 Documentos Opcionais</span>
                    <ul class="resumo-docs-list" id="resumoDocsOpcionaisList"></ul>
                </div>

                <div class="resumo-linha"><span class="resumo-label">Arquivos</span><span class="resumo-valor"><span id="rAnexos">0</span> anexado(s)</span></div>
                <div class="resumo-linha resumo-linha--last"><span class="resumo-label">Abertura</span><span class="resumo-valor">{{ now()->format('d/m/Y') }}</span></div>
            </div>
        </div>
    </div>
</div>
</form>

@push('scripts')
<script>
// Estado
const estado = {
    servicoId: null,
    servicoNome: null,
    servicoSetor: null,
    servicoCargos: [],
    documentosObrigatorios: [],
    documentosOpcionais: [],
    arquivos: [],
    uid: 0,
};

// Autocomplete
const acInput = document.getElementById('acInput');
const acDropdown = document.getElementById('acDropdown');
const acSpinner = document.getElementById('acSpinner');
const acClear = document.getElementById('acClear');
let timer = null;
let resultados = [];
let focoIdx = -1;

acInput.addEventListener('input', () => {
    clearTimeout(timer);
    const q = acInput.value.trim();
    if (!q) { fecharDropdown(); return; }
    acSpinner.classList.add('--ativo');
    timer = setTimeout(() => buscar(q), 500);
});

acInput.addEventListener('keydown', e => {
    const itens = acDropdown.querySelectorAll('.ac-item');
    if (e.key === 'ArrowDown') { e.preventDefault(); focoIdx = Math.min(focoIdx + 1, itens.length - 1); atualizarFoco(itens); }
    if (e.key === 'ArrowUp') { e.preventDefault(); focoIdx = Math.max(focoIdx - 1, 0); atualizarFoco(itens); }
    if (e.key === 'Enter') { e.preventDefault(); if (focoIdx >= 0 && resultados[focoIdx]) selecionar(resultados[focoIdx]); }
    if (e.key === 'Escape') { fecharDropdown(); acInput.blur(); }
});

document.addEventListener('click', e => {
    if (!e.target.closest('#acContainer')) fecharDropdown();
});

async function buscar(q) {
    if (!q || q.length < 2) {
        fecharDropdown();
        return;
    }
    acSpinner.classList.add('--ativo');
    try {
        const response = await fetch(`/documentos/tipos-json?q=${encodeURIComponent(q)}`, {
            headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '' },
            credentials: 'same-origin'
        });
        if (!response.ok) throw new Error(`HTTP ${response.status}`);
        resultados = await response.json();
        renderDropdown(resultados);
    } catch (e) { console.error('Erro:', e); resultados = []; }
    finally { acSpinner.classList.remove('--ativo'); }
}

function renderDropdown(items) {
    focoIdx = -1;
    acDropdown.innerHTML = '';
    if (!items.length) {
        acDropdown.innerHTML = '<div class="ac-vazio">Nenhum serviço encontrado</div>';
        acDropdown.classList.add('--aberto');
        return;
    }
    items.forEach((item, i) => {
        const div = document.createElement('div');
        div.className = 'ac-item';
        div.innerHTML = `<div class="ac-item-nome">${esc(item.nome)}</div>${item.descricao ? `<div class="ac-item-desc">${esc(item.descricao)}</div>` : ''}${item.setor_nome ? `<div class="ac-item-setor">🏢 ${esc(item.setor_nome)}</div>` : ''}`;
        div.addEventListener('click', () => selecionar(item));
        acDropdown.appendChild(div);
    });
    acDropdown.classList.add('--aberto');
}

function atualizarFoco(itens) {
    itens.forEach(el => el.classList.remove('--foco'));
    if (focoIdx >= 0 && itens[focoIdx]) itens[focoIdx].classList.add('--foco');
}

function fecharDropdown() {
    acDropdown.classList.remove('--aberto');
    acDropdown.innerHTML = '';
    focoIdx = -1;
}

async function selecionar(item) {
    estado.servicoId = item.id;
    estado.servicoNome = item.nome;
    estado.servicoSetor = item.setor_nome;
    estado.servicoCargos = item.cargos_responsaveis || [];
    
    acInput.value = item.nome;
    acInput.classList.add('--selected');
    document.getElementById('servicoId').value = item.id;
    acClear.style.display = 'inline';
    fecharDropdown();

    if (item.setor_nome) {
        document.getElementById('setorDestino').value = item.setor_nome;
        document.getElementById('hintSetor').innerHTML = '✅ Preenchido automaticamente';
        document.getElementById('depDestinoId').value = item.setor_id || '';
    }

    await buscarRequisitos(item.id);
}

async function buscarRequisitos(id) {
    try {
        const response = await fetch(`/documentos/${id}/requisitos`, {
            headers: { 
                'Accept': 'application/json', 
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '' 
            }
        });
        const data = await response.json();
        
        let obrigatorios = data.documentos_obrigatorios || [];
        if (obrigatorios && !Array.isArray(obrigatorios)) {
            obrigatorios = Object.values(obrigatorios);
        }
        
        let opcionais = data.documentos_opcionais || [];
        if (opcionais && !Array.isArray(opcionais)) {
            opcionais = Object.values(opcionais);
        }
        
        estado.documentosObrigatorios = obrigatorios;
        estado.documentosOpcionais = opcionais;
        
        atualizarResumo();
        
        const selects = document.querySelectorAll('.upload-item-select');
        selects.forEach(select => {
            const uid = parseInt(select.getAttribute('data-uid'));
            preencherSelect(select, uid);
        });
        
    } catch (e) { 
        console.error('Erro ao buscar requisitos:', e); 
    }
}

function preencherSelect(select, uidIgnorar) {
    let html = '<option value="">Selecione o tipo de documento</option>';
    
    if (estado.documentosObrigatorios && estado.documentosObrigatorios.length > 0) {
        html += '<optgroup label="📋 Documentos Obrigatórios">';
        estado.documentosObrigatorios.forEach(doc => {
            const jaEnviado = estado.arquivos.some(a => a.documentoId == doc.id && a.uid !== uidIgnorar);
            html += `<option value="${doc.id}" data-nome="${esc(doc.nome)}" ${jaEnviado ? 'disabled' : ''}>${esc(doc.nome)} ⚠️</option>`;
        });
        html += '</optgroup>';
    }
    
    if (estado.documentosOpcionais && estado.documentosOpcionais.length > 0) {
        html += '<optgroup label="📄 Documentos Opcionais">';
        estado.documentosOpcionais.forEach(doc => {
            const jaEnviado = estado.arquivos.some(a => a.documentoId == doc.id && a.uid !== uidIgnorar);
            html += `<option value="${doc.id}" data-nome="${esc(doc.nome)}" ${jaEnviado ? 'disabled' : ''}>${esc(doc.nome)}</option>`;
        });
        html += '</optgroup>';
    }
    
    select.innerHTML = html;
    
    const item = estado.arquivos.find(a => a.uid === uidIgnorar);
    if (item && item.documentoId) {
        select.value = item.documentoId;
        select.classList.add('--selecionado');
    } else {
        select.classList.remove('--selecionado');
    }
}

function atualizarResumo() {
    document.getElementById('resumoVazio').style.display = 'none';
    document.getElementById('resumoConteudo').style.display = 'block';
    document.getElementById('rServico').textContent = estado.servicoNome || '—';
    document.getElementById('rDestino').textContent = estado.servicoSetor || '—';
    document.getElementById('rCargo').textContent = estado.servicoCargos.length ? estado.servicoCargos.join(', ') : '—';
    
    const docsObrigDiv = document.getElementById('resumoDocsObrigatorios');
    const docsObrigList = document.getElementById('resumoDocsObrigatoriosList');
    if (estado.documentosObrigatorios && estado.documentosObrigatorios.length > 0) {
        docsObrigDiv.style.display = 'block';
        docsObrigList.innerHTML = '';
        estado.documentosObrigatorios.forEach(doc => {
            const enviado = estado.arquivos.some(a => a.documentoId == doc.id);
            const li = document.createElement('li');
            li.className = enviado ? 'ok' : 'pendente';
            li.innerHTML = `${esc(doc.nome)} ${enviado ? '✅' : '⚠️'}`;
            docsObrigList.appendChild(li);
        });
    } else {
        docsObrigDiv.style.display = 'none';
    }
    
    const docsOpcDiv = document.getElementById('resumoDocsOpcionais');
    const docsOpcList = document.getElementById('resumoDocsOpcionaisList');
    if (estado.documentosOpcionais && estado.documentosOpcionais.length > 0) {
        docsOpcDiv.style.display = 'block';
        docsOpcList.innerHTML = '';
        estado.documentosOpcionais.forEach(doc => {
            const enviado = estado.arquivos.some(a => a.documentoId == doc.id);
            const li = document.createElement('li');
            li.innerHTML = `${esc(doc.nome)} ${enviado ? '✅' : '📄'}`;
            docsOpcList.appendChild(li);
        });
    } else {
        docsOpcDiv.style.display = 'none';
    }
    
    validarDocumentosObrigatorios();
}

function validarDocumentosObrigatorios() {
    const documentosEnviadosIds = estado.arquivos.map(a => a.documentoId).filter(id => id);
    const obrigatoriosIds = (estado.documentosObrigatorios || []).map(d => d.id);
    const todosEnviados = obrigatoriosIds.length === 0 || obrigatoriosIds.every(id => documentosEnviadosIds.includes(id));
    
    const aviso = document.getElementById('uploadAviso');
    const btnAbrir = document.getElementById('btnAbrir');
    
    if (obrigatoriosIds.length > 0 && !todosEnviados) {
        aviso.style.display = 'flex';
        btnAbrir.disabled = true;
        btnAbrir.style.opacity = '0.5';
    } else {
        aviso.style.display = 'none';
        btnAbrir.disabled = false;
        btnAbrir.style.opacity = '1';
    }
}

function limparServico() {
    estado.servicoId = null;
    estado.servicoNome = null;
    estado.servicoSetor = null;
    estado.servicoCargos = [];
    estado.documentosObrigatorios = [];
    estado.documentosOpcionais = [];
    
    acInput.value = '';
    acInput.classList.remove('--selected');
    document.getElementById('servicoId').value = '';
    acClear.style.display = 'none';
    document.getElementById('resumoVazio').style.display = 'block';
    document.getElementById('resumoConteudo').style.display = 'none';
    document.getElementById('setorDestino').value = '';
    document.getElementById('uploadAviso').style.display = 'none';
    document.getElementById('btnAbrir').disabled = false;
    document.getElementById('btnAbrir').style.opacity = '1';
    acInput.focus();
}

// Upload
function adicionarArquivos(files) {
    Array.from(files).forEach(file => {
        const uid = estado.uid++;
        estado.arquivos.push({ file, uid, documentoId: null, documentoNome: null });
        renderItem(file, uid);
    });
    sincronizar();
    atualizarResumo();
}

function renderItem(file, uid) {
    const kb = file.size / 1024;
    const tam = kb < 1024 ? kb.toFixed(1) + ' KB' : (kb / 1024).toFixed(2) + ' MB';
    const icone = file.type.includes('image') ? '🖼️' : file.name.endsWith('.pdf') ? '📕' : '📄';

    const div = document.createElement('div');
    div.className = 'upload-item';
    div.id = `uitem-${uid}`;
    div.innerHTML = `
        <div class="upload-item-icone">${icone}</div>
        <div class="upload-item-corpo">
            <div class="upload-item-nome">${esc(file.name)}</div>
            <div class="upload-item-meta">${tam}</div>
            <select class="upload-item-select" data-uid="${uid}" onchange="mudarDocumento(this)">
                <option value="">Carregando...</option>
            </select>
        </div>
        <button type="button" class="upload-item-remove" onclick="removerItem(${uid})">✕</button>
    `;
    
    document.getElementById('uploadList').appendChild(div);
    
    const select = div.querySelector('.upload-item-select');
    preencherSelect(select, uid);
}

function mudarDocumento(select) {
    console.log('🔄 mudarDocumento chamado');
    
    const uid = parseInt(select.getAttribute('data-uid'));
    const documentoId = select.value;
    const option = select.options[select.selectedIndex];
    const documentoNome = option ? option.getAttribute('data-nome') : null;
    
    console.log('UID:', uid, 'Documento ID:', documentoId, 'Nome:', documentoNome);
    
    const item = estado.arquivos.find(a => a.uid === uid);
    if (item && documentoId) {
        item.documentoId = documentoId;
        item.documentoNome = documentoNome;
        select.classList.add('--selecionado');
        console.log('✅ Documento vinculado ao arquivo:', item);
    } else if (item) {
        item.documentoId = null;
        item.documentoNome = null;
        select.classList.remove('--selecionado');
        console.log('⚠️ Documento removido do arquivo');
    }
    
    // Força a atualização do resumo e validação
    atualizarResumo();
    sincronizar();
    
    // Força a reabilitação de opções em outros selects
    document.querySelectorAll('.upload-item-select').forEach(select => {
        const uid = parseInt(select.getAttribute('data-uid'));
        preencherSelect(select, uid);
    });
    
    // Verificação imediata
    console.log('Estado atual dos arquivos:', estado.arquivos.map(a => ({
        uid: a.uid,
        nome: a.file.name,
        docId: a.documentoId,
        docNome: a.documentoNome
    })));
}

function removerItem(uid) {
    estado.arquivos = estado.arquivos.filter(a => a.uid !== uid);
    const el = document.getElementById(`uitem-${uid}`);
    if (el) el.remove();
    sincronizar();
    atualizarResumo();
    
    document.querySelectorAll('.upload-item-select').forEach(select => {
        const uid = parseInt(select.getAttribute('data-uid'));
        preencherSelect(select, uid);
    });
}

function sincronizar() {
    const dt = new DataTransfer();
    estado.arquivos.forEach(a => dt.items.add(a.file));
    document.getElementById('fileInput').files = dt.files;
    
    const cont = document.getElementById('hiddenInputs');
    cont.innerHTML = '';
    estado.arquivos.forEach((a, i) => {
        let tipoAnexo = 'outros';
        const tipoMap = { 'RG': 'rg', 'CPF': 'cpf', 'CNH': 'cnh', 'Contrato': 'contrato', 'Comprovante de Residência': 'comprovante_residencia', 'Comprovante de Renda': 'comprovante_renda', 'Certidão': 'certidao', 'Laudo': 'laudo', 'Outros': 'outros' };
        tipoAnexo = tipoMap[a.documentoNome] || 'outros';
        
        const inp = document.createElement('input');
        inp.type = 'hidden';
        inp.name = `tipos_anexo[${i}]`;
        inp.value = tipoAnexo;
        cont.appendChild(inp);
    });
    
    document.getElementById('rAnexos').textContent = estado.arquivos.length;
}

// ⭐ FUNÇÃO PRINCIPAL DE VALIDAÇÃO E ENVIO ⭐

function validarDocumentosObrigatorios() {
    console.log('🔍 Validando documentos obrigatórios...');
    
    // Converte para array se necessário
    let obrigatorios = estado.documentosObrigatorios;
    if (obrigatorios && !Array.isArray(obrigatorios)) {
        obrigatorios = Object.values(obrigatorios);
    }
    
    // IDs dos obrigatórios como string
    const obrigatoriosIds = (obrigatorios || []).map(d => String(d.id));
    console.log('📋 IDs obrigatórios:', obrigatoriosIds);
    
    // IDs dos documentos já anexados
    const documentosEnviadosIds = estado.arquivos
        .map(a => a.documentoId ? String(a.documentoId) : null)
        .filter(id => id);
    console.log('📎 IDs anexados:', documentosEnviadosIds);
    
    // Verifica se todos foram enviados
    const todosEnviados = obrigatoriosIds.length === 0 || 
        obrigatoriosIds.every(id => documentosEnviadosIds.includes(id));
    
    console.log('✅ Todos enviados?', todosEnviados);
    
    const aviso = document.getElementById('uploadAviso');
    const btnAbrir = document.getElementById('btnAbrir');
    
    if (obrigatoriosIds.length > 0 && !todosEnviados) {
        const faltando = obrigatoriosIds.filter(id => !documentosEnviadosIds.includes(id));
        console.log('❌ Faltando IDs:', faltando);
        aviso.style.display = 'flex';
        btnAbrir.disabled = true;
        btnAbrir.style.opacity = '0.5';
    } else {
        console.log('✅✅✅ TODOS OS DOCUMENTOS OBRIGATÓRIOS FORAM ANEXADOS! ✅✅✅');
        aviso.style.display = 'none';
        btnAbrir.disabled = false;
        btnAbrir.style.opacity = '1';
    }
}

function validarEEnviar() {
    console.log('🚀 Botão clicado - Iniciando validação');
    
    // Log do estado atual
    console.log('📊 Estado atual:');
    console.log('  - Documentos obrigatórios:', estado.documentosObrigatorios);
    console.log('  - Arquivos:', estado.arquivos.map(a => ({
        nome: a.file.name,
        documentoId: a.documentoId,
        documentoNome: a.documentoNome
    })));
    
    // Verifica serviço
    if (!document.getElementById('servicoId').value) {
        acInput.classList.add('--erro');
        acInput.focus();
        alert('❌ Selecione um serviço antes de continuar.');
        return;
    }
    
    // Verifica remetente
    const remetente = document.querySelector('input[name="remetente"]').value.trim();
    if (!remetente) {
        alert('❌ Preencha o nome do solicitante/remetente.');
        return;
    }
    
    // Verifica setor destino
    const setorDestino = document.getElementById('setorDestino').value.trim();
    if (!setorDestino) {
        alert('❌ O setor de destino não foi preenchido. Selecione um serviço válido.');
        return;
    }
    
    // Verifica arquivos
    if (estado.arquivos.length === 0) {
        alert('❌ Anexe pelo menos um arquivo antes de enviar o processo.');
        return;
    }
    
    // 🔧 CORREÇÃO: Verifica documentos obrigatórios (convertendo IDs para string)
    const documentosEnviadosIds = estado.arquivos
        .map(a => a.documentoId ? String(a.documentoId) : null)
        .filter(id => id);
    
    let obrigatorios = estado.documentosObrigatorios;
    if (obrigatorios && !Array.isArray(obrigatorios)) {
        obrigatorios = Object.values(obrigatorios);
    }
    
    const obrigatoriosIds = (obrigatorios || []).map(d => String(d.id));
    const faltando = obrigatoriosIds.filter(id => !documentosEnviadosIds.includes(id));
    
    console.log('📋 IDs obrigatórios:', obrigatoriosIds);
    console.log('📎 IDs anexados:', documentosEnviadosIds);
    console.log('❌ Faltando:', faltando);
    
    if (faltando.length > 0) {
        alert(`❌ Documentos obrigatórios faltando:\n\n${faltando.map(id => {
            const doc = (obrigatorios || []).find(d => String(d.id) === id);
            return `• ${doc?.nome || id}`;
        }).join('\n')}\n\nPor favor, anexe os documentos obrigatórios antes de continuar.`);
        return;
    }
    
    // Verifica se cada arquivo tem um documento selecionado
    const semDocumento = estado.arquivos.filter(a => !a.documentoId);
    if (semDocumento.length > 0) {
        alert(`⚠️ Selecione o tipo de documento para ${semDocumento.length} arquivo(s) antes de enviar.`);
        return;
    }
    
    console.log('✅ Todas as validações passaram! Enviando formulário...');
    
    // Desabilita o botão e envia
    const btn = document.getElementById('btnAbrir');
    btn.disabled = true;
    btn.textContent = '⏳ Enviando...';
    
    // Envia o formulário
    document.getElementById('formProcesso').submit();
}

function esc(s) {
    return String(s ?? '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
}

// Dropzone
const uploadZone = document.getElementById('uploadZone');
uploadZone.addEventListener('dragover', (e) => { e.preventDefault(); uploadZone.classList.add('--over'); });
uploadZone.addEventListener('dragleave', () => { uploadZone.classList.remove('--over'); });
uploadZone.addEventListener('drop', (e) => {
    e.preventDefault();
    uploadZone.classList.remove('--over');
    if (e.dataTransfer.files.length) adicionarArquivos(e.dataTransfer.files);
});
</script>
@endpush
@endsection