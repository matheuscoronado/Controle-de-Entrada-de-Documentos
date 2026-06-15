{{-- ============================================================
     resources/views/processos/create.blade.php
     NOVO PROCESSO - VERSÃO CORRIGIDA COM SELECT DE DOCUMENTOS
     ============================================================ --}}
@extends('layouts.app')
@section('title', 'Novo Processo')
@section('subtitle', 'Abertura de solicitação de serviço')

@section('topbar-actions')
<a href="{{ route('documentos.index') }}" class="btn-secondary-sced">← Voltar</a>
@endsection

@section('content')

<style>
    /* Cards */
    .processo-card {
        background: var(--branco);
        border-radius: 16px;
        border: 1px solid var(--cinza-200);
        box-shadow: var(--sombra-card);
        padding: 24px;
        margin-bottom: 24px;
        transition: var(--transicao);
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

    /* Formulários */
    .p-label {
        display: block;
        font-size: 12px;
        font-weight: 600;
        color: var(--cinza-600);
        text-transform: uppercase;
        letter-spacing: .6px;
        margin-bottom: 8px;
    }
    .p-req { color: #ef4444; }
    .p-input {
        width: 100%;
        padding: 12px 14px;
        border: 1.5px solid var(--cinza-200);
        border-radius: 10px;
        font-size: 14px;
        transition: var(--transicao);
        outline: none;
        font-family: 'Sora', sans-serif;
    }
    .p-input:focus {
        border-color: var(--azul-claro);
        box-shadow: 0 0 0 3px rgba(37,99,235,.12);
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
    .p-error {
        font-size: 12px;
        color: #ef4444;
        margin-top: 5px;
    }

    /* Select */
    select.p-input {
        cursor: pointer;
        appearance: none;
        background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%23475569' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right 12px center;
        background-size: 16px;
    }

    /* Upload */
    .upload-zone {
        border: 2px dashed var(--cinza-200);
        border-radius: 12px;
        padding: 40px 24px;
        text-align: center;
        cursor: pointer;
        background: var(--cinza-100);
        transition: var(--transicao);
    }
    .upload-zone:hover {
        border-color: var(--azul-claro);
        background: rgba(37,99,235,.04);
        transform: scale(1.01);
    }
    .upload-zone-icone { font-size: 42px; margin-bottom: 12px; }
    .upload-zone-texto { font-size: 14px; color: var(--cinza-600); font-weight: 500; }
    .upload-zone-formatos { font-size: 11px; color: var(--cinza-400); margin-top: 8px; }

    /* Itens de upload */
    .upload-item {
        display: flex;
        gap: 12px;
        align-items: flex-start;
        padding: 14px;
        margin-top: 12px;
        background: var(--branco);
        border: 1.5px solid var(--cinza-200);
        border-radius: 10px;
        transition: var(--transicao);
    }
    .upload-item:hover {
        border-color: var(--azul-claro);
        background: var(--cinza-100);
    }
    .upload-item-icone { font-size: 28px; flex-shrink: 0; }
    .upload-item-corpo { flex: 1; }
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
        padding: 8px 12px;
        margin-top: 10px;
        border: 1.5px solid var(--cinza-200);
        border-radius: 8px;
        font-size: 12px;
        cursor: pointer;
        transition: var(--transicao);
        font-family: 'Sora', sans-serif;
        background: white;
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
        transition: var(--transicao);
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
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .resumo-vazio {
        text-align: center;
        padding: 30px 0;
    }
    .resumo-vazio-icone { font-size: 36px; margin-bottom: 10px; opacity: 0.4; }
    .resumo-vazio p { font-size: 13px; color: var(--cinza-400); margin: 0; }
    .resumo-linha {
        padding: 12px 0;
        border-bottom: 1px solid var(--cinza-200);
    }
    .resumo-linha:last-child { border-bottom: none; }
    .resumo-label {
        font-size: 10px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        color: var(--cinza-400);
        margin-bottom: 6px;
    }
    .resumo-valor {
        font-size: 13px;
        font-weight: 600;
        color: var(--cinza-800);
        word-break: break-word;
    }
    .resumo-docs-list {
        list-style: none;
        padding: 0;
        margin: 8px 0 0;
    }
    .resumo-docs-list li {
        font-size: 12px;
        padding: 8px 0;
        display: flex;
        align-items: center;
        gap: 10px;
        border-bottom: 1px solid var(--cinza-100);
    }
    .resumo-docs-list li:last-child { border-bottom: none; }
    .resumo-docs-list li.anexado { color: #059669; }
    .resumo-docs-list li.pendente { color: #d97706; }
    .resumo-docs-list li.anexado::before { content: "✅"; margin-right: 8px; }
    .resumo-docs-list li.pendente::before { content: "⏳"; margin-right: 8px; }
    .resumo-docs-sub {
        font-size: 11px;
        color: var(--cinza-400);
        margin-top: 4px;
    }
    .resumo-counter {
        font-size: 18px;
        font-weight: 700;
        color: var(--azul-claro);
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
    .btn-abrir { padding: 12px 32px; font-size: 15px; }
    .btn-cancelar { padding: 12px 28px; font-size: 14px; }
    .btn-disabled { opacity: 0.5; cursor: not-allowed; }
</style>

<form method="POST" action="{{ route('documentos.store') }}" enctype="multipart/form-data" id="formProcesso">
    @csrf

    <div class="row g-4">
        <div class="col-lg-8">
            {{-- 1. IDENTIFICAÇÃO DO SERVIÇO --}}
            <div class="processo-card">
                <div class="processo-bloco-header">
                    <div class="processo-step">1</div>
                    <div>
                        <div class="processo-bloco-titulo">Identificação do Serviço</div>
                        <div class="processo-bloco-sub">Selecione o serviço desejado</div>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="p-label">Serviço <span class="p-req">*</span></label>
                    <select name="tipo_documento_id" id="servicoSelect" class="p-input" required>
                        <option value="">Selecione um serviço</option>
                        @foreach(\App\Models\TipoDocumento::where('status', 'ativo')->get() as $servico)
                        <option value="{{ $servico->id }}"
                            data-setor="{{ $servico->departamentoDestino?->nome }}"
                            data-setor-id="{{ $servico->departamento_destino_id }}"
                            data-cargos="{{ json_encode($servico->cargos_responsaveis ?? []) }}">
                            {{ $servico->nome }}
                        </option>
                        @endforeach
                    </select>
                    @error('tipo_documento_id')<div class="p-error">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label class="p-label">Setor de Destino <span class="p-req">*</span></label>
                    <input type="text" name="setor_destino" id="setorDestino" class="p-input p-input--locked" readonly required>
                    <input type="hidden" name="departamento_destino_id" id="depDestinoId">
                </div>

                <div class="mb-3">
                    <label class="p-label">Solicitante <span class="p-req">*</span></label>
                    <input type="text" name="remetente" class="p-input p-input--locked" value="{{ auth()->user()->nome }}" readonly required>
                </div>

                <div class="mb-3">
                    <label class="p-label">Descrição</label>
                    <textarea name="descricao" class="p-input p-textarea" placeholder="Detalhes adicionais sobre a solicitação...">{{ old('descricao') }}</textarea>
                </div>

                <div class="mb-3">
                    <label class="p-label">Data de Recebimento <span class="p-req">*</span></label>
                    <input type="date" name="data_recebimento" id="dataRecebimento" class="p-input p-input--locked" value="{{ date('Y-m-d') }}" readonly required>
                </div>
            </div>

            {{-- 2. DOCUMENTOS ANEXADOS --}}
            <div class="processo-card">
                <div class="processo-bloco-header">
                    <div class="processo-step">2</div>
                    <div>
                        <div class="processo-bloco-titulo">Documentos Anexos</div>
                        <div class="processo-bloco-sub">Anexe os arquivos necessários para o processo</div>
                    </div>
                </div>

                <div class="upload-zone" id="uploadZone" onclick="document.getElementById('fileInput').click()">
                    <div class="upload-zone-icone">📎</div>
                    <div class="upload-zone-texto">Arraste arquivos aqui ou <span class="upload-zone-link">clique para selecionar</span></div>
                    <div class="upload-zone-formatos">PDF · DOC · DOCX · JPG · PNG — máx. 10 MB por arquivo</div>
                </div>

                <input type="file" id="fileInput" multiple style="display:none" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" onchange="adicionarArquivos(this.files)">
                <div id="uploadList" class="mt-3"></div>
                <div id="hiddenInputs"></div>
            </div>

            <div class="acao-card-principal">
                <button type="button" class="btn-primary-sced btn-abrir" id="btnAbrir" onclick="validarEEnviar()" disabled>Abrir Processo</button>
                <a href="{{ route('documentos.index') }}" class="btn-secondary-sced btn-cancelar">Cancelar</a>
            </div>
        </div>

        {{-- COLUNA LATERAL - RESUMO --}}
        <div class="col-lg-4">
            <div class="resumo-card" id="resumoCard">
                <div class="resumo-titulo">
                    <span>📋</span> Resumo do Processo
                </div>

                <div id="resumoVazio" class="resumo-vazio">
                    <div class="resumo-vazio-icone">🔍</div>
                    <p>Selecione um serviço para ver o resumo</p>
                </div>

                <div id="resumoConteudo" style="display: none;">
                    <div class="resumo-linha">
                        <div class="resumo-label">Serviço</div>
                        <div class="resumo-valor" id="resumoServico">—</div>
                    </div>
                    <div class="resumo-linha">
                        <div class="resumo-label">Setor Destino</div>
                        <div class="resumo-valor" id="resumoSetor">—</div>
                    </div>
                    <div class="resumo-linha" id="resumoDocsContainer">
                        <div class="resumo-label">Documentos Obrigatórios</div>
                        <ul class="resumo-docs-list" id="resumoDocsList"></ul>
                        <div class="resumo-docs-sub" id="resumoDocsSub"></div>
                    </div>
                    <div class="resumo-linha">
                        <div class="resumo-label">Arquivos Anexados</div>
                        <div class="resumo-valor">
                            <span class="resumo-counter" id="resumoContadorArquivos">0</span> arquivo(s)
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

@push('scripts')
<script>
    // ============================================================
    // ESTADO GLOBAL
    // ============================================================
    let documentosObrigatorios = [];
    let todosDocumentos = [];
    let arquivos = [];
    let uid = 0;
    let servicoAtual = null;
    let obrigatoriosIds = [];

    // ============================================================
    // CARREGAR DOCUMENTOS DO BANCO (ROTA CORRETA)
    // ============================================================
    async function carregarTodosDocumentos() {
        console.log('🔄 Carregando documentos cadastrados...');

        try {
            const response = await fetch('/documentos/cadastrados-json', {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                credentials: 'same-origin'
            });

            if (response.ok) {
                todosDocumentos = await response.json();
                console.log('✅ Documentos carregados com sucesso:', todosDocumentos);
                console.log('📋 Quantidade:', todosDocumentos.length);
                atualizarTodosSelects();
                return;
            } else {
                console.error('Erro ao carregar documentos. Status:', response.status);
                const text = await response.text();
                console.error('Resposta:', text);
            }
        } catch (e) {
            console.error('Erro na requisição:', e);
        }

        // Fallback: dados mockados para teste
        console.warn('⚠️ Usando dados mockados - nenhum documento encontrado no banco');
        todosDocumentos = [];
        atualizarTodosSelects();

        const uploadList = document.getElementById('uploadList');
        if (uploadList && uploadList.children.length === 0) {
            const msgDiv = document.createElement('div');
            msgDiv.className = 'alert alert-warning mt-3';
            msgDiv.innerHTML = '⚠️ Nenhum documento cadastrado. <a href="/admin/documentos-tipo">Cadastre documentos</a> para aparecerem aqui.';
            uploadList.parentNode.insertBefore(msgDiv, uploadList);
        }
    }

    function atualizarTodosSelects() {
        console.log('🔄 Atualizando selects com', todosDocumentos.length, 'documentos');
        document.querySelectorAll('.upload-item-select').forEach(select => {
            const uid = parseInt(select.getAttribute('data-uid'));
            preencherSelect(select, uid);
        });
    }

    function preencherSelect(select, uidIgnorar) {
        if (!select) return;

        let html = '<option value="">📄 Selecione o tipo de documento</option>';

        if (todosDocumentos && todosDocumentos.length > 0) {
            const obrigatorios = todosDocumentos.filter(d => d.tipo === 'obrigatorio');
            const opcionais = todosDocumentos.filter(d => d.tipo === 'opcional');

            if (obrigatorios.length > 0) {
                html += '<optgroup label="📋 Documentos Obrigatórios">';
                obrigatorios.forEach(doc => {
                    html += `<option value="${doc.id}" data-nome="${escapeHtml(doc.nome)}">${escapeHtml(doc.nome)}</option>`;
                });
                html += '</optgroup>';
            }

            if (opcionais.length > 0) {
                html += '<optgroup label="📄 Documentos Opcionais">';
                opcionais.forEach(doc => {
                    html += `<option value="${doc.id}" data-nome="${escapeHtml(doc.nome)}">${escapeHtml(doc.nome)}</option>`;
                });
                html += '</optgroup>';
            }

            if (obrigatorios.length === 0 && opcionais.length === 0) {
                todosDocumentos.forEach(doc => {
                    html += `<option value="${doc.id}" data-nome="${escapeHtml(doc.nome)}">${escapeHtml(doc.nome)}</option>`;
                });
            }
        } else {
            html += '<option value="" disabled>⚠️ Nenhum documento cadastrado</option>';
        }

        select.innerHTML = html;

        const item = arquivos.find(a => a.uid === uidIgnorar);
        if (item && item.documentoId) {
            select.value = item.documentoId;
            select.classList.add('--selecionado');
        } else {
            select.classList.remove('--selecionado');
        }
    }

    // ============================================================
    // INICIALIZAÇÃO
    // ============================================================
    document.addEventListener('DOMContentLoaded', function() {
        console.log('🚀 Inicializando página...');
        carregarTodosDocumentos();
    });

    // ============================================================
    // SERVIÇO
    // ============================================================
    document.getElementById('servicoSelect').addEventListener('change', async function() {
        const servicoId = this.value;
        if (!servicoId) {
            limparResumo();
            return;
        }

        const option = this.options[this.selectedIndex];
        const setorNome = option.dataset.setor || '';
        const setorId = option.dataset.setorId || '';

        document.getElementById('setorDestino').value = setorNome;
        document.getElementById('depDestinoId').value = setorId;
        document.getElementById('resumoServico').innerText = option.text;
        document.getElementById('resumoSetor').innerText = setorNome || '—';

        await buscarDocumentosObrigatorios(servicoId);
    });

    async function buscarDocumentosObrigatorios(servicoId) {
        try {
            const response = await fetch(`/documentos/${servicoId}/requisitos`, {
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                },
                credentials: 'same-origin'
            });

            if (!response.ok) throw new Error(`HTTP ${response.status}`);

            const data = await response.json();
            documentosObrigatorios = data.documentos_obrigatorios || [];
            obrigatoriosIds = documentosObrigatorios.map(d => String(d.id));

            document.getElementById('resumoVazio').style.display = 'none';
            document.getElementById('resumoConteudo').style.display = 'block';

            atualizarListaDocumentosResumo();
        } catch (e) {
            console.error('Erro ao buscar documentos obrigatórios:', e);
        }
    }

    function atualizarListaDocumentosResumo() {
        const lista = document.getElementById('resumoDocsList');
        const container = document.getElementById('resumoDocsContainer');

        if (documentosObrigatorios.length === 0) {
            container.style.display = 'none';
            return;
        }

        container.style.display = 'block';
        lista.innerHTML = '';

        const documentosEnviadosIds = arquivos.map(a => a.documentoId ? String(a.documentoId) : null).filter(id => id);

        documentosObrigatorios.forEach(doc => {
            const enviado = documentosEnviadosIds.includes(String(doc.id));
            const li = document.createElement('li');
            li.className = enviado ? 'anexado' : 'pendente';
            li.innerHTML = doc.nome;
            lista.appendChild(li);
        });

        const pendentes = obrigatoriosIds.filter(id => !documentosEnviadosIds.includes(id)).length;
        const anexados = obrigatoriosIds.length - pendentes;
        const subElement = document.getElementById('resumoDocsSub');
        if (subElement) {
            subElement.innerHTML = `${anexados} de ${obrigatoriosIds.length} anexado(s)`;
        }
    }

    function limparResumo() {
        document.getElementById('resumoVazio').style.display = 'block';
        document.getElementById('resumoConteudo').style.display = 'none';
        document.getElementById('resumoServico').innerText = '—';
        document.getElementById('resumoSetor').innerText = '—';
        document.getElementById('resumoDocsList').innerHTML = '';
        documentosObrigatorios = [];
        obrigatoriosIds = [];
    }

    // ============================================================
    // UPLOAD DE ARQUIVOS
    // ============================================================
    function adicionarArquivos(files) {
        console.log('📎 Adicionando arquivos:', files.length);

        Array.from(files).forEach(file => {
            if (file.size > 10 * 1024 * 1024) {
                alert(`O arquivo ${file.name} excede o limite de 10 MB.`);
                return;
            }
            const novoArquivo = {
                file: file,
                uid: uid++,
                documentoId: null,
                documentoNome: null
            };
            arquivos.push(novoArquivo);
            renderizarItemArquivo(novoArquivo);
        });
        sincronizarFormulario();
        atualizarResumo();
    }

    function renderizarItemArquivo(item) {
        const kb = item.file.size / 1024;
        const tamanho = kb < 1024 ? kb.toFixed(1) + ' KB' : (kb / 1024).toFixed(2) + ' MB';
        const icone = item.file.type.includes('image') ? '🖼️' : item.file.name.endsWith('.pdf') ? '📕' : '📄';

        const div = document.createElement('div');
        div.className = 'upload-item';
        div.id = `uitem-${item.uid}`;

        let selectHtml = `<select class="upload-item-select" data-uid="${item.uid}" onchange="mudarTipoDocumento(this)">`;
        selectHtml += '<option value="">📄 Carregando documentos...</option>';
        selectHtml += '</select>';

        div.innerHTML = `
            <div class="upload-item-icone">${icone}</div>
            <div class="upload-item-corpo">
                <div class="upload-item-nome">${escapeHtml(item.file.name)}</div>
                <div class="upload-item-meta">${tamanho}</div>
                ${selectHtml}
            </div>
            <button type="button" class="upload-item-remove" onclick="removerArquivo(${item.uid})" title="Remover">✕</button>
        `;

        document.getElementById('uploadList').appendChild(div);

        const select = div.querySelector('.upload-item-select');
        preencherSelect(select, item.uid);

        if (item.documentoId) {
            select.value = item.documentoId;
            select.classList.add('--selecionado');
        }
    }

    function mudarTipoDocumento(select) {
        const uid = parseInt(select.getAttribute('data-uid'));
        const documentoId = select.value;
        const option = select.options[select.selectedIndex];
        const documentoNome = option ? option.getAttribute('data-nome') : '';

        const item = arquivos.find(a => a.uid === uid);
        if (item && documentoId) {
            item.documentoId = documentoId;
            item.documentoNome = documentoNome;
            select.classList.add('--selecionado');
        }

        atualizarResumo();
        sincronizarFormulario();
    }

    function removerArquivo(uid) {
        arquivos = arquivos.filter(a => a.uid !== uid);
        const elemento = document.getElementById(`uitem-${uid}`);
        if (elemento) elemento.remove();
        sincronizarFormulario();
        atualizarResumo();
    }

    function sincronizarFormulario() {
        const dataTransfer = new DataTransfer();
        arquivos.forEach(a => dataTransfer.items.add(a.file));
        document.getElementById('fileInput').files = dataTransfer.files;

        const container = document.getElementById('hiddenInputs');
        container.innerHTML = '';
        arquivos.forEach((a, index) => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = `tipos_anexo[${index}]`;
            input.value = a.documentoId || '';
            container.appendChild(input);
        });
    }

    // ============================================================
    // ATUALIZAÇÃO DO RESUMO
    // ============================================================
    function atualizarResumo() {
        const totalArquivos = arquivos.length;
        document.getElementById('resumoContadorArquivos').innerText = totalArquivos;

        const documentosEnviadosIds = arquivos.map(a => a.documentoId ? String(a.documentoId) : null).filter(id => id);

        const lista = document.getElementById('resumoDocsList');
        if (lista) {
            const itens = lista.querySelectorAll('li');
            itens.forEach(li => {
                const texto = li.innerText;
                const doc = documentosObrigatorios.find(d => d.nome === texto);
                if (doc && documentosEnviadosIds.includes(String(doc.id))) {
                    li.className = 'anexado';
                } else if (doc) {
                    li.className = 'pendente';
                }
            });
        }

        const pendentes = obrigatoriosIds.filter(id => !documentosEnviadosIds.includes(id)).length;
        const anexados = obrigatoriosIds.length - pendentes;
        const subElement = document.getElementById('resumoDocsSub');
        if (subElement) {
            subElement.innerHTML = `${anexados} de ${obrigatoriosIds.length} anexado(s)`;
        }

        validarBotaoEnviar();
    }

    // ============================================================
    // VALIDAÇÃO E ENVIO
    // ============================================================
    function validarBotaoEnviar() {
        const btn = document.getElementById('btnAbrir');
        const servicoSelecionado = document.getElementById('servicoSelect').value;

        if (!servicoSelecionado) {
            btn.disabled = true;
            btn.classList.add('btn-disabled');
            return;
        }

        const documentosEnviadosIds = arquivos.map(a => a.documentoId ? String(a.documentoId) : null).filter(id => id);
        const todosObrigatoriosEnviados = obrigatoriosIds.length === 0 || obrigatoriosIds.every(id => documentosEnviadosIds.includes(id));
        const todosComTipo = arquivos.length > 0 && arquivos.every(a => a.documentoId);

        if (arquivos.length > 0 && todosComTipo && todosObrigatoriosEnviados) {
            btn.disabled = false;
            btn.classList.remove('btn-disabled');
        } else {
            btn.disabled = true;
            btn.classList.add('btn-disabled');
        }
    }

    function validarEEnviar() {
        const btn = document.getElementById('btnAbrir');
        if (btn.disabled) {
            alert('❌ Preencha todos os campos obrigatórios e anexe os documentos necessários.');
            return;
        }

        if (!document.getElementById('servicoSelect').value) {
            alert('❌ Selecione um serviço antes de continuar.');
            return;
        }

        if (arquivos.length === 0) {
            alert('❌ Anexe pelo menos um arquivo antes de enviar.');
            return;
        }

        const semTipo = arquivos.filter(a => !a.documentoId);
        if (semTipo.length > 0) {
            alert(`⚠️ Selecione o tipo de documento para ${semTipo.length} arquivo(s) antes de enviar.`);
            return;
        }

        const documentosEnviadosIds = arquivos.map(a => a.documentoId ? String(a.documentoId) : null).filter(id => id);
        const faltando = obrigatoriosIds.filter(id => !documentosEnviadosIds.includes(id));

        if (faltando.length > 0) {
            alert('❌ Documentos obrigatórios faltando. Anexe todos os documentos obrigatórios antes de continuar.');
            return;
        }

        btn.disabled = true;
        btn.textContent = '⏳ Enviando...';
        document.getElementById('formProcesso').submit();
    }

    function escapeHtml(texto) {
        if (!texto) return '';
        return texto.replace(/[&<>]/g, function(m) {
            if (m === '&') return '&amp;';
            if (m === '<') return '&lt;';
            if (m === '>') return '&gt;';
            return m;
        });
    }

    // Dropzone
    const uploadZone = document.getElementById('uploadZone');
    if (uploadZone) {
        uploadZone.addEventListener('dragover', (e) => {
            e.preventDefault();
            uploadZone.style.borderColor = 'var(--azul-claro)';
            uploadZone.style.background = 'rgba(37,99,235,.04)';
        });
        uploadZone.addEventListener('dragleave', () => {
            uploadZone.style.borderColor = 'var(--cinza-200)';
            uploadZone.style.background = 'var(--cinza-100)';
        });
        uploadZone.addEventListener('drop', (e) => {
            e.preventDefault();
            uploadZone.style.borderColor = 'var(--cinza-200)';
            uploadZone.style.background = 'var(--cinza-100)';
            if (e.dataTransfer.files.length) {
                adicionarArquivos(e.dataTransfer.files);
            }
        });
    }
</script>
@endpush
@endsection