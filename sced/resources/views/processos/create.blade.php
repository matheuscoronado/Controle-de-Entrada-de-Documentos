{{-- ============================================================
     resources/views/processos/create.blade.php
     TELA DE NOVO PROCESSO - VERSÃO COMPLETA CORRIGIDA
     - Select com todos os documentos do sistema
     - Removidas mensagens informativas desnecessárias
     ============================================================ --}}
@extends('layouts.app')
@section('title', 'Novo Processo')
@section('subtitle', 'Abertura de solicitação de serviço')

@section('topbar-actions')
<a href="{{ route('documentos.index') }}" class="btn-secondary-sced">← Voltar</a>
@endsection

@section('content')

<style>
    /* Cards principais */
    .processo-card {
        background: var(--branco);
        border-radius: 16px;
        border: 1px solid var(--cinza-200);
        box-shadow: var(--sombra-card);
        padding: 24px;
        margin-bottom: 24px;
        transition: all 0.3s ease;
    }

    .processo-card:hover {
        box-shadow: var(--sombra-hover);
    }

    .processo-bloco-header {
        display: flex;
        align-items: flex-start;
        gap: 14px;
        margin-bottom: 24px;
        padding-bottom: 16px;
        border-bottom: 1px solid var(--cinza-200);
    }

    .processo-step {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: var(--azul-claro);
        color: #fff;
        font-size: 14px;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        box-shadow: 0 2px 8px rgba(37, 99, 235, .3);
    }

    .processo-bloco-titulo {
        font-size: 16px;
        font-weight: 700;
        color: var(--azul-escuro);
    }

    .processo-bloco-sub {
        font-size: 13px;
        color: var(--cinza-400);
        margin-top: 2px;
    }

    /* Labels e inputs */
    .p-label {
        display: block;
        font-size: 12px;
        font-weight: 600;
        color: var(--cinza-600);
        text-transform: uppercase;
        letter-spacing: .6px;
        margin-bottom: 8px;
    }

    .p-req {
        color: #ef4444;
    }

    .p-hint {
        font-size: 11px;
        font-weight: 400;
        color: var(--cinza-400);
        text-transform: none;
        letter-spacing: 0;
    }

    .p-input {
        width: 100%;
        padding: 12px 14px;
        border: 1.5px solid var(--cinza-200);
        border-radius: 10px;
        font-family: 'Sora', sans-serif;
        font-size: 14px;
        color: var(--cinza-800);
        background: var(--branco);
        transition: all 0.22s ease;
        outline: none;
    }

    .p-input:focus {
        border-color: var(--azul-claro);
        box-shadow: 0 0 0 3px rgba(37, 99, 235, .12);
    }

    .p-input--locked {
        background: var(--cinza-100);
        color: var(--cinza-600);
        cursor: not-allowed;
    }

    .p-textarea {
        resize: vertical;
        min-height: 100px;
    }

    .p-date-wrap {
        position: relative;
    }

    .p-lock-badge {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 11px;
        font-weight: 600;
        color: var(--cinza-400);
        background: var(--cinza-200);
        padding: 3px 10px;
        border-radius: 20px;
        pointer-events: none;
    }

    .p-error {
        font-size: 12px;
        color: #ef4444;
        margin-top: 5px;
    }

    /* Autocomplete */
    .ac-container {
        position: relative;
        margin-bottom: 0;
    }

    .ac-field-wrap {
        position: relative;
    }

    .ac-icon {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 16px;
        pointer-events: none;
    }

    .ac-input {
        width: 100%;
        padding: 12px 45px 12px 45px;
        border: 1.5px solid var(--cinza-200);
        border-radius: 10px;
        font-family: 'Sora', sans-serif;
        font-size: 14px;
        color: var(--cinza-800);
        background: var(--branco);
        transition: all 0.22s ease;
        outline: none;
    }

    .ac-input:focus {
        border-color: var(--azul-claro);
        box-shadow: 0 0 0 3px rgba(37, 99, 235, .12);
    }

    .ac-spinner {
        position: absolute;
        right: 45px;
        top: 50%;
        transform: translateY(-50%);
        width: 16px;
        height: 16px;
        border: 2px solid var(--cinza-200);
        border-top-color: var(--azul-claro);
        border-radius: 50%;
        animation: girar 0.7s linear infinite;
        display: none;
    }

    .ac-spinner.--ativo {
        display: block;
    }

    @keyframes girar {
        to {
            transform: translateY(-50%) rotate(360deg);
        }
    }

    .ac-clear {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        cursor: pointer;
        font-size: 16px;
        color: var(--cinza-400);
        padding: 6px;
        border-radius: 20px;
        transition: all 0.2s;
    }

    .ac-clear:hover {
        color: #ef4444;
        background: var(--cinza-100);
    }

    .ac-dropdown {
        position: absolute;
        top: calc(100% + 5px);
        left: 0;
        right: 0;
        z-index: 300;
        background: var(--branco);
        border: 1.5px solid var(--cinza-200);
        border-radius: 12px;
        box-shadow: var(--sombra-hover);
        max-height: 340px;
        overflow-y: auto;
        display: none;
    }

    .ac-dropdown.--aberto {
        display: block;
        animation: fadeSlide 0.2s ease;
    }

    @keyframes fadeSlide {
        from {
            opacity: 0;
            transform: translateY(-8px);
        }

        to {
            opacity: 1;
            transform: none;
        }
    }

    .ac-item {
        padding: 12px 16px;
        cursor: pointer;
        border-bottom: 1px solid var(--cinza-200);
        transition: background 0.15s;
    }

    .ac-item:last-child {
        border-bottom: none;
    }

    .ac-item:hover {
        background: var(--cinza-100);
    }

    .ac-item-nome {
        font-size: 14px;
        font-weight: 600;
        color: var(--cinza-800);
    }

    .ac-item-setor {
        font-size: 12px;
        color: var(--cinza-400);
        margin-top: 2px;
    }

    .ac-vazio {
        padding: 24px;
        text-align: center;
        color: var(--cinza-400);
        font-size: 13px;
    }

    /* Upload */
    .upload-aviso {
        display: flex;
        gap: 10px;
        align-items: flex-start;
        background: #fffbeb;
        border: 1.5px solid #fde68a;
        border-radius: 10px;
        padding: 12px 16px;
        margin-bottom: 18px;
    }

    .upload-aviso span {
        font-size: 18px;
    }

    .upload-aviso p {
        font-size: 13px;
        color: #92400e;
        margin: 0;
        line-height: 1.5;
    }

    .upload-zone {
        border: 2px dashed var(--cinza-200);
        border-radius: 12px;
        padding: 40px 24px;
        text-align: center;
        cursor: pointer;
        background: var(--cinza-100);
        transition: all 0.22s ease;
    }

    .upload-zone:hover {
        border-color: var(--azul-claro);
        background: rgba(37, 99, 235, .04);
        transform: scale(1.01);
    }

    .upload-zone-icone {
        font-size: 42px;
        margin-bottom: 12px;
    }

    .upload-zone-texto {
        font-size: 14px;
        color: var(--cinza-600);
        font-weight: 500;
    }

    .upload-zone-link {
        color: var(--azul-claro);
        font-weight: 700;
        text-decoration: underline;
    }

    .upload-zone-formatos {
        font-size: 11px;
        color: var(--cinza-400);
        margin-top: 8px;
    }

    .upload-item {
        display: flex;
        gap: 12px;
        align-items: flex-start;
        padding: 14px;
        margin-top: 12px;
        background: var(--branco);
        border: 1.5px solid var(--cinza-200);
        border-radius: 10px;
        transition: all 0.2s;
    }

    .upload-item:hover {
        border-color: var(--azul-claro);
    }

    .upload-item-icone {
        font-size: 28px;
        flex-shrink: 0;
    }

    .upload-item-corpo {
        flex: 1;
    }

    .upload-item-nome {
        font-size: 13px;
        font-weight: 600;
        color: var(--cinza-800);
        word-break: break-all;
    }

    .upload-item-meta {
        font-size: 11px;
        color: var(--cinza-400);
        margin-top: 2px;
    }

    .upload-item-select {
        width: 100%;
        padding: 8px 10px;
        margin-top: 10px;
        border: 1.5px solid var(--cinza-200);
        border-radius: 8px;
        font-family: 'Sora', sans-serif;
        font-size: 12px;
        background: var(--cinza-100);
        cursor: pointer;
        transition: all 0.2s;
    }

    .upload-item-select:focus {
        border-color: var(--azul-claro);
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
        color: var(--cinza-400);
        font-size: 20px;
        padding: 4px 8px;
        border-radius: 8px;
        transition: all 0.2s;
    }

    .upload-item-remove:hover {
        color: #ef4444;
        background: var(--cinza-100);
    }

    /* Resumo lateral */
    .resumo-card {
        background: var(--branco);
        border-radius: 16px;
        border: 1px solid var(--cinza-200);
        box-shadow: var(--sombra-card);
        padding: 24px;
        margin-bottom: 20px;
        position: sticky;
        top: 90px;
    }

    .resumo-titulo {
        font-size: 14px;
        font-weight: 700;
        color: var(--azul-escuro);
        margin-bottom: 20px;
        padding-bottom: 14px;
        border-bottom: 1px solid var(--cinza-200);
    }

    .resumo-vazio {
        text-align: center;
        padding: 30px 0 15px;
    }

    .resumo-vazio-icone {
        font-size: 36px;
        margin-bottom: 10px;
        opacity: 0.4;
    }

    .resumo-vazio p {
        font-size: 13px;
        color: var(--cinza-400);
        margin: 0;
    }

    .resumo-linha {
        padding: 10px 0;
        border-bottom: 1px solid var(--cinza-200);
    }

    .resumo-linha--last {
        border-bottom: none;
    }

    .resumo-label {
        font-size: 10px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        color: var(--cinza-400);
        margin-bottom: 4px;
    }

    .resumo-valor {
        font-size: 13px;
        font-weight: 600;
        color: var(--cinza-800);
    }

    /* Documentos no resumo */
    .resumo-docs-obrigatorios,
    .resumo-docs-opcionais {
        margin-top: 14px;
        padding-top: 10px;
        border-top: 1px solid var(--cinza-200);
    }

    .resumo-docs-obrigatorios .resumo-label {
        color: #dc2626;
    }

    .resumo-docs-opcionais .resumo-label {
        color: var(--azul-claro);
    }

    .resumo-docs-list {
        list-style: none;
        padding: 0;
        margin: 10px 0 0;
    }

    .resumo-docs-list li {
        font-size: 12px;
        padding: 5px 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .resumo-docs-list li.pendente {
        color: #f59e0b;
    }

    .resumo-docs-list li.pendente::before {
        content: "⚠️";
    }

    .resumo-docs-list li.ok {
        color: #10b981;
    }

    .resumo-docs-list li.ok::before {
        content: "✅";
    }

    /* Botões */
    .acao-card-principal {
        display: flex;
        gap: 12px;
        justify-content: flex-end;
        margin-top: 24px;
        padding-top: 20px;
        border-top: 1px solid var(--cinza-200);
    }

    .btn-abrir {
        padding: 12px 32px;
        font-size: 15px;
    }

    .btn-cancelar {
        padding: 12px 28px;
        font-size: 14px;
    }

    .mt-2 {
        margin-top: 10px;
    }
</style>

<form method="POST" action="{{ route('documentos.store') }}" enctype="multipart/form-data" id="formProcesso" novalidate>
    @csrf

    <div class="row g-4">
        {{-- COLUNA PRINCIPAL --}}
        <div class="col-lg-8">

            {{-- 1. IDENTIFICAÇÃO DO SERVIÇO --}}
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
                        <input type="text" id="acInput" class="ac-input"
                            placeholder="Ex: Solicitação de Benefício, Abertura de Cadastro..."
                            autocomplete="off">
                        <span class="ac-spinner" id="acSpinner"></span>
                        <button type="button" class="ac-clear" id="acClear" onclick="limparServico()" style="display:none">✕</button>
                    </div>
                    <input type="hidden" name="tipo_documento_id" id="servicoId">
                    <div class="ac-dropdown" id="acDropdown"></div>
                    @error('tipo_documento_id')<div class="p-error">{{ $message }}</div>@enderror
                </div>
            </div>

            {{-- 2. DADOS DO SOLICITANTE --}}
            <div class="processo-card mb-4">
                <div class="processo-bloco-header">
                    <div class="processo-step">2</div>
                    <div>
                        <div class="processo-bloco-titulo">Dados do Solicitante</div>
                        <div class="processo-bloco-sub">Informações sobre quem está abrindo o processo</div>
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-12">
                        <label class="p-label">Solicitante / Remetente <span class="p-req">*</span></label>
                        <input type="text" name="remetente" class="p-input p-input--locked"
                            value="{{ auth()->user()->nome }}" readonly required>
                        @error('remetente')<div class="p-error">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                        <label class="p-label">Data de Abertura <span class="p-req">*</span></label>
                        <div class="p-date-wrap">
                            <input type="date" name="data_recebimento" class="p-input p-input--locked"
                                value="{{ date('Y-m-d') }}" readonly>
                            <span class="p-lock-badge">🔒 Hoje</span>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="p-label">Setor de Destino <span class="p-req">*</span></label>
                        <input type="text" name="setor_destino" id="setorDestino"
                            class="p-input p-input--locked" placeholder="Selecione um serviço primeiro" readonly>
                        <input type="hidden" name="departamento_destino_id" id="depDestinoId">
                        @error('setor_destino')<div class="p-error">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-12">
                        <label class="p-label">Descrição / Observações</label>
                        <textarea name="descricao" class="p-input p-textarea"
                            placeholder="Detalhes adicionais sobre a solicitação (opcional)...">{{ old('descricao') }}</textarea>
                    </div>
                </div>
            </div>

            {{-- 3. DOCUMENTOS ANEXADOS --}}
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
                    <div class="upload-zone-texto">
                        Arraste arquivos aqui ou <span class="upload-zone-link">clique para selecionar</span>
                    </div>
                    <div class="upload-zone-formatos">PDF · DOC · DOCX · JPG · PNG — máx. 10 MB por arquivo</div>
                </div>

                <input type="file" id="fileInput" name="anexos[]" multiple style="display:none"
                    accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" onchange="adicionarArquivos(this.files)">

                <div id="uploadList"></div>
                <div id="hiddenInputs"></div>

                @error('anexos')<div class="p-error mt-2">{{ $message }}</div>@enderror
            </div>

            {{-- BOTÕES --}}
            <div class="acao-card-principal">
                <button type="button" class="btn-primary-sced btn-abrir" id="btnAbrir" onclick="validarEEnviar()">🚀 Abrir Processo</button>
                <a href="{{ route('documentos.index') }}" class="btn-secondary-sced btn-cancelar">Cancelar</a>
            </div>

        </div>

        {{-- COLUNA LATERAL - RESUMO --}}
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
                        <span class="resumo-label">Setor Destino</span>
                        <span class="resumo-valor" id="rDestino">—</span>
                    </div>
                    <div class="resumo-linha">
                        <span class="resumo-label">Cargos Responsáveis</span>
                        <span class="resumo-valor" id="rCargos">—</span>
                    </div>

                    {{-- Documentos Obrigatórios --}}
                    <div class="resumo-docs-obrigatorios" id="resumoDocsObrigatorios" style="display:none">
                        <span class="resumo-label">📋 Documentos Obrigatórios</span>
                        <ul class="resumo-docs-list" id="resumoDocsObrigatoriosList"></ul>
                    </div>

                    {{-- Documentos Opcionais --}}
                    <div class="resumo-docs-opcionais" id="resumoDocsOpcionais" style="display:none">
                        <span class="resumo-label">📄 Documentos Opcionais</span>
                        <ul class="resumo-docs-list" id="resumoDocsOpcionaisList"></ul>
                    </div>

                    <div class="resumo-linha">
                        <span class="resumo-label">Arquivos Anexados</span>
                        <span class="resumo-valor"><span id="rAnexos">0</span> arquivo(s)</span>
                    </div>
                    <div class="resumo-linha resumo-linha--last">
                        <span class="resumo-label">Data de Abertura</span>
                        <span class="resumo-valor">{{ now()->format('d/m/Y') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

@push('scripts')
<script>
    // Estado do formulário
    const estado = {
        servicoId: null,
        servicoNome: null,
        servicoSetor: null,
        servicoCargos: [],
        documentosObrigatorios: [],
        documentosOpcionais: [],
        todosDocumentos: [], // Todos os documentos cadastrados no sistema
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
        if (!q) {
            fecharDropdown();
            return;
        }
        acSpinner.classList.add('--ativo');
        timer = setTimeout(() => buscar(q), 500);
    });

    acInput.addEventListener('keydown', e => {
        const itens = acDropdown.querySelectorAll('.ac-item');
        if (e.key === 'ArrowDown') {
            e.preventDefault();
            focoIdx = Math.min(focoIdx + 1, itens.length - 1);
            atualizarFoco(itens);
        }
        if (e.key === 'ArrowUp') {
            e.preventDefault();
            focoIdx = Math.max(focoIdx - 1, 0);
            atualizarFoco(itens);
        }
        if (e.key === 'Enter') {
            e.preventDefault();
            if (focoIdx >= 0 && resultados[focoIdx]) selecionar(resultados[focoIdx]);
        }
        if (e.key === 'Escape') {
            fecharDropdown();
            acInput.blur();
        }
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
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                },
                credentials: 'same-origin'
            });
            if (!response.ok) throw new Error(`HTTP ${response.status}`);
            resultados = await response.json();
            renderDropdown(resultados);
        } catch (e) {
            console.error('Erro na busca:', e);
            resultados = [];
        } finally {
            acSpinner.classList.remove('--ativo');
        }
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
            div.innerHTML = `<div class="ac-item-nome">${esc(item.nome)}</div>
                         ${item.descricao ? `<div class="ac-item-desc">${esc(item.descricao)}</div>` : ''}`;
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
            document.getElementById('depDestinoId').value = item.setor_id || '';
        }

        await buscarDocumentos(item.id);
    }

    async function buscarDocumentos(id) {
        try {
            // Busca os documentos vinculados ao serviço (obrigatórios e opcionais)
            const response = await fetch(`/documentos/${id}/requisitos`, {
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                }
            });
            const data = await response.json();

            // Converte para array
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

            // 🔧 BUSCA TODOS OS DOCUMENTOS CADASTRADOS NO SISTEMA PARA O SELECT
            const docsResponse = await fetch(`/api/documentos/todos`, {
                headers: {
                    'Accept': 'application/json'
                }
            });
            const todosDocs = await docsResponse.json();
            estado.todosDocumentos = todosDocs || [];

            console.log('Documentos carregados:', estado.todosDocumentos);

            atualizarResumo();

            // Atualiza os selects dos arquivos já anexados
            const selects = document.querySelectorAll('.upload-item-select');
            selects.forEach(select => {
                const uid = parseInt(select.getAttribute('data-uid'));
                preencherSelect(select, uid);
            });

        } catch (e) {
            console.error('Erro ao buscar dados:', e);
        }
    }

    function preencherSelect(select, uidIgnorar) {
        let html = '<option value="">Selecione o tipo de documento</option>';

        // 🔧 USA TODOS OS DOCUMENTOS CADASTRADOS NO SISTEMA
        if (estado.todosDocumentos && estado.todosDocumentos.length > 0) {
            html += '<optgroup label="📋 Documentos Disponíveis">';
            estado.todosDocumentos.forEach(doc => {
                const jaEnviado = estado.arquivos.some(a => a.documentoId == doc.id && a.uid !== uidIgnorar);
                html += `<option value="${doc.id}" data-nome="${esc(doc.nome)}" ${jaEnviado ? 'disabled' : ''}>${esc(doc.nome)}</option>`;
            });
            html += '</optgroup>';
        } else {
            html += '<option value="" disabled>Nenhum documento cadastrado</option>';
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
        document.getElementById('rCargos').textContent = estado.servicoCargos.length ? estado.servicoCargos.join(', ') : '—';

        // Documentos Obrigatórios no Resumo
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

        // Documentos Opcionais no Resumo
        const docsOpcDiv = document.getElementById('resumoDocsOpcionais');
        const docsOpcList = document.getElementById('resumoDocsOpcionaisList');
        if (estado.documentosOpcionais && estado.documentosOpcionais.length > 0) {
            docsOpcDiv.style.display = 'block';
            docsOpcList.innerHTML = '';
            estado.documentosOpcionais.forEach(doc => {
                const enviado = estado.arquivos.some(a => a.documentoId == doc.id);
                const li = document.createElement('li');
                li.className = enviado ? 'ok' : '';
                li.innerHTML = `${esc(doc.nome)} ${enviado ? '✅' : '📄'}`;
                docsOpcList.appendChild(li);
            });
        } else {
            docsOpcDiv.style.display = 'none';
        }

        document.getElementById('rAnexos').textContent = estado.arquivos.length;
        validarDocumentosObrigatorios();
    }

    function validarDocumentosObrigatorios() {
        const documentosEnviadosIds = estado.arquivos.map(a => a.documentoId).filter(id => id);
        const obrigatoriosIds = (estado.documentosObrigatorios || []).map(d => d.id);
        const todosEnviados = obrigatoriosIds.length === 0 || obrigatoriosIds.every(id => documentosEnviadosIds.includes(id));

        const aviso = document.getElementById('uploadAviso');
        const btnAbrir = document.getElementById('btnAbrir');

        if (obrigatoriosIds.length > 0 && !todosEnviados) {
            const faltando = obrigatoriosIds.filter(id => !documentosEnviadosIds.includes(id));
            const nomesFaltando = faltando.map(id => {
                const doc = estado.documentosObrigatorios.find(d => d.id == id);
                return doc?.nome || id;
            }).join(', ');
            aviso.style.display = 'flex';
            aviso.querySelector('p').innerHTML = `<strong>Atenção!</strong> Documentos obrigatórios faltando: ${nomesFaltando}`;
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

    // Upload de arquivos
    function adicionarArquivos(files) {
        Array.from(files).forEach(file => {
            const uid = estado.uid++;
            estado.arquivos.push({
                file,
                uid,
                documentoId: null,
                documentoNome: null
            });
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
                <option value="">Carregando documentos...</option>
            </select>
        </div>
        <button type="button" class="upload-item-remove" onclick="removerItem(${uid})">✕</button>
    `;

        document.getElementById('uploadList').appendChild(div);

        const select = div.querySelector('.upload-item-select');
        preencherSelect(select, uid);
    }

    function mudarDocumento(select) {
        const uid = parseInt(select.getAttribute('data-uid'));
        const documentoId = select.value;
        const option = select.options[select.selectedIndex];
        const documentoNome = option ? option.getAttribute('data-nome') : null;

        const item = estado.arquivos.find(a => a.uid === uid);
        if (item && documentoId) {
            item.documentoId = documentoId;
            item.documentoNome = documentoNome;
            select.classList.add('--selecionado');
        } else if (item) {
            item.documentoId = null;
            item.documentoNome = null;
            select.classList.remove('--selecionado');
        }

        atualizarResumo();
        sincronizar();

        document.querySelectorAll('.upload-item-select').forEach(select => {
            const uid = parseInt(select.getAttribute('data-uid'));
            preencherSelect(select, uid);
        });
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
            const tipoMap = {
                'RG': 'rg',
                'CPF': 'cpf',
                'CNH': 'cnh',
                'Contrato': 'contrato',
                'Comprovante de Residência': 'comprovante_residencia',
                'Comprovante de Renda': 'comprovante_renda',
                'Certidão': 'certidao',
                'Laudo': 'laudo',
                'Outros': 'outros'
            };
            tipoAnexo = tipoMap[a.documentoNome] || 'outros';

            const inp = document.createElement('input');
            inp.type = 'hidden';
            inp.name = `tipos_anexo[${i}]`;
            inp.value = tipoAnexo;
            cont.appendChild(inp);
        });
    }

    function validarEEnviar() {
        console.log('🚀 Validando envio...');

        if (!document.getElementById('servicoId').value) {
            acInput.classList.add('--erro');
            acInput.focus();
            alert('❌ Selecione um serviço antes de continuar.');
            return;
        }

        if (estado.arquivos.length === 0) {
            alert('❌ Anexe pelo menos um arquivo antes de enviar o processo.');
            return;
        }

        const documentosEnviadosIds = estado.arquivos.map(a => a.documentoId).filter(id => id);
        const obrigatoriosIds = (estado.documentosObrigatorios || []).map(d => d.id);
        const faltando = obrigatoriosIds.filter(id => !documentosEnviadosIds.includes(id));

        if (faltando.length > 0) {
            const nomesFaltando = faltando.map(id => {
                const doc = estado.documentosObrigatorios.find(d => d.id == id);
                return doc?.nome || id;
            }).join('\n• ');
            alert(`❌ Documentos obrigatórios faltando:\n\n• ${nomesFaltando}\n\nPor favor, anexe os documentos obrigatórios antes de continuar.`);
            return;
        }

        const semDocumento = estado.arquivos.filter(a => !a.documentoId);
        if (semDocumento.length > 0) {
            alert(`⚠️ Selecione o tipo de documento para ${semDocumento.length} arquivo(s) antes de enviar.`);
            return;
        }

        console.log('✅ Todas as validações passaram! Enviando...');

        const btn = document.getElementById('btnAbrir');
        btn.disabled = true;
        btn.textContent = '⏳ Enviando...';

        document.getElementById('formProcesso').submit();
    }

    function esc(s) {
        return String(s ?? '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
    }

    // Dropzone
    const uploadZone = document.getElementById('uploadZone');
    uploadZone.addEventListener('dragover', (e) => {
        e.preventDefault();
        uploadZone.classList.add('--over');
    });
    uploadZone.addEventListener('dragleave', () => {
        uploadZone.classList.remove('--over');
    });
    uploadZone.addEventListener('drop', (e) => {
        e.preventDefault();
        uploadZone.classList.remove('--over');
        if (e.dataTransfer.files.length) adicionarArquivos(e.dataTransfer.files);
    });
</script>
@endpush
@endsection