{{-- ============================================================
     resources/views/processos/create.blade.php
     Formulário "Novo Processo" — Reformulação Visual Fase 2
     • Autocomplete de Serviços via API (sem SLA)
     • Steps visuais modernos
     • Setor de destino automático
     • Lista dinâmica de documentos obrigatórios
     • Upload múltiplo com Select de tipo por arquivo
     ============================================================ --}}
@extends('layouts.app')
@section('title', 'Novo Processo')
@section('subtitle', 'Abertura de solicitação de serviço')

@section('topbar-actions')
    <a href="{{ route('documentos.index') }}" class="btn-secondary-sced">← Voltar</a>
@endsection

@section('content')

{{-- Indicador de steps --}}
<div class="create-steps">
    <div class="create-step create-step--ativo" id="stepInd1">
        <div class="create-step-num">1</div>
        <div class="create-step-label">Serviço</div>
    </div>
    <div class="create-step-linha"></div>
    <div class="create-step create-step--inativo" id="stepInd2">
        <div class="create-step-num">2</div>
        <div class="create-step-label">Solicitante</div>
    </div>
    <div class="create-step-linha"></div>
    <div class="create-step create-step--inativo" id="stepInd3">
        <div class="create-step-num">3</div>
        <div class="create-step-label">Documentos</div>
    </div>
</div>

<form method="POST" action="{{ route('documentos.store') }}"
      enctype="multipart/form-data" id="formProcesso" novalidate>
@csrf

<div class="row g-4">

{{-- ══ COLUNA PRINCIPAL ══════════════════════════════════ --}}
<div class="col-lg-8">

    {{-- ── BLOCO 1: Serviço ─────────────────────────── --}}
    <div class="create-card mb-4" id="bloco1">
        <div class="create-bloco-header">
            <div class="create-step-badge --ativo">1</div>
            <div>
                <div class="create-bloco-titulo">Identificação do Serviço</div>
                <div class="create-bloco-sub">Busque e selecione o serviço solicitado</div>
            </div>
        </div>

        {{-- Autocomplete --}}
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

            @error('tipo_documento_id')
                <div class="p-error">{{ $message }}</div>
            @enderror
        </div>

        {{-- Alerta documentos obrigatórios — aparece ao selecionar --}}
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

        {{-- Info compacta do serviço selecionado (sem SLA) --}}
        <div class="servico-info-strip" id="servicoInfoStrip" style="display:none">
            <div class="servico-info-item">
                <span class="servico-info-label">Destino</span>
                <span class="servico-info-valor" id="siSetor">—</span>
            </div>
            <div class="servico-info-item">
                <span class="servico-info-label">Responsável</span>
                <span class="servico-info-valor" id="siCargo">—</span>
            </div>
        </div>
    </div>

    {{-- ── BLOCO 2: Dados do Solicitante ───────────── --}}
    <div class="create-card mb-4" id="bloco2">
        <div class="create-bloco-header">
            <div class="create-step-badge">2</div>
            <div>
                <div class="create-bloco-titulo">Dados do Solicitante</div>
                <div class="create-bloco-sub">Identifique quem está abrindo este processo</div>
            </div>
        </div>

        <div class="row g-3">
            <div class="col-md-8">
                <label class="p-label">Solicitante / Remetente <span class="p-req">*</span></label>
                <input type="text" name="remetente"
                       class="p-input @error('remetente') p-input--erro @enderror"
                       placeholder="Nome completo ou razão social"
                       value="{{ old('remetente') }}" required>
                @error('remetente')<div class="p-error">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-4">
                <label class="p-label">Data de Abertura</label>
                <div class="p-date-wrap">
                    <input type="date" name="data_recebimento"
                           class="p-input p-input--locked"
                           value="{{ date('Y-m-d') }}"
                           readonly tabindex="-1">
                    <span class="p-lock-badge">🔒 Hoje</span>
                </div>
                <div class="p-hint-text">Fixada automaticamente na data atual</div>
            </div>

            {{-- Setor de destino — preenchido automaticamente pelo serviço --}}
            <div class="col-12">
                <label class="p-label">
                    Setor de Destino <span class="p-req">*</span>
                    <span class="p-badge-auto" id="badgeAutoSetor" style="display:none">✨ automático</span>
                </label>
                <input type="text" name="setor_destino" id="setorDestino"
                       class="p-input @error('setor_destino') p-input--erro @enderror"
                       placeholder="Selecione o serviço para preencher automaticamente"
                       value="{{ old('setor_destino') }}" readonly>
                <input type="hidden" name="departamento_destino_id" id="depDestinoId">
                <div class="p-hint-text" id="hintSetor">
                    Será preenchido ao selecionar o serviço
                </div>
                @error('setor_destino')<div class="p-error">{{ $message }}</div>@enderror
            </div>

            <div class="col-12">
                <label class="p-label">Descrição / Observações</label>
                <textarea name="descricao" class="p-input p-textarea" rows="3"
                          placeholder="Detalhes adicionais sobre a solicitação (opcional)...">{{ old('descricao') }}</textarea>
            </div>
        </div>
    </div>

    {{-- ── BLOCO 3: Upload de Documentos ───────────── --}}
    <div class="create-card mb-4" id="bloco3">
        <div class="create-bloco-header">
            <div class="create-step-badge">3</div>
            <div>
                <div class="create-bloco-titulo">Documentos Anexos</div>
                <div class="create-bloco-sub">Adicione os arquivos necessários para o processo</div>
            </div>
            <div class="bloco-badge-validacao">⚠️ Validação manual posterior</div>
        </div>

        {{-- Aviso de validação --}}
        <div class="upload-aviso">
            <span>ℹ️</span>
            <p>Os documentos enviados passarão por <strong>validação manual</strong> pela equipe responsável.
               O processo é iniciado mesmo com arquivos pendentes de aprovação.</p>
        </div>

        {{-- Drop zone --}}
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

        {{-- Lista de arquivos adicionados --}}
        <div id="uploadList"></div>

        {{-- Inputs hidden gerados por JS (tipos_anexo[n]) --}}
        <div id="hiddenInputs"></div>

        @error('anexos')
            <div class="p-error mt-2">{{ $message }}</div>
        @enderror
    </div>

</div>{{-- /col-lg-8 --}}

{{-- ══ COLUNA LATERAL (resumo + ação) ═══════════════════ --}}
<div class="col-lg-4">

    {{-- Card de resumo --}}
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
                <span class="resumo-label">Arquivos</span>
                <span class="resumo-valor">
                    <span id="rAnexos">0</span> anexado(s)
                </span>
            </div>
            <div class="resumo-linha resumo-linha--last">
                <span class="resumo-label">Abertura</span>
                <span class="resumo-valor">{{ now()->format('d/m/Y') }}</span>
            </div>
        </div>
    </div>

    {{-- Botões de ação --}}
    <div class="acao-card">
        <button type="submit" class="btn-primary-sced btn-abrir" id="btnAbrir">
            🚀 Abrir Processo
        </button>
        <a href="{{ route('documentos.index') }}"
           class="btn-secondary-sced btn-cancelar">
            Cancelar
        </a>
    </div>

</div>{{-- /col-lg-4 --}}

</div>{{-- /row --}}
</form>
@endsection

{{-- ══ ESTILOS ════════════════════════════════════════════════ --}}
@push('styles')
<style>
/* ── Variáveis locais (herdam do sistema) ───────────────── */
:root {
    --p-radius: var(--radius, 12px);
    --p-radius-sm: var(--radius-sm, 8px);
    --p-trans: var(--transicao, all .22s cubic-bezier(.4,0,.2,1));
}

/* ── Card de processo ───────────────────────────────────── */
.create-card {
    background: var(--branco);
    border-radius: var(--p-radius);
    border: 1px solid var(--cinza-200);
    box-shadow: var(--sombra-card);
    padding: 24px;
    transition: var(--p-trans);
}
.create-card:hover { box-shadow: var(--sombra-hover); }

.create-bloco-header {
    display: flex; align-items: flex-start; gap: 14px;
    margin-bottom: 22px; padding-bottom: 18px;
    border-bottom: 1px solid var(--cinza-200);
}
.create-step-badge {
    width: 30px; height: 30px; border-radius: 50%;
    background: var(--cinza-200); color: var(--cinza-600);
    font-size: 13px; font-weight: 700;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0; margin-top: 2px;
    transition: var(--p-trans);
}
.create-step-badge.--ativo {
    background: var(--azul-claro); color: #fff;
    box-shadow: 0 2px 8px rgba(37,99,235,.3);
}
.create-bloco-titulo {
    font-size: 15px; font-weight: 700; color: var(--azul-escuro);
}
.create-bloco-sub {
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

/* ── Info strip do serviço (sem SLA) ────────────────────── */
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
@endpush

{{-- ══ SCRIPTS ════════════════════════════════════════════════ --}}
@push('scripts')
<script>
// ─────────────────────────────────────────────────────────────
// ESTADO LOCAL
// ─────────────────────────────────────────────────────────────
const estado = {
    servicoId:    null,
    arquivos:     [],   // [{ file, tipo, uid }]
    uid:          0,
};

const TIPOS = {
    rg:                     'RG — Documento de Identidade',
    cpf:                    'CPF',
    contrato:               'Contrato',
    comprovante_residencia:  'Comprovante de Residência',
    comprovante_renda:       'Comprovante de Renda',
    certidao:               'Certidão',
    laudo:                  'Laudo / Parecer Técnico',
    outros:                 'Outros',
};

// ─────────────────────────────────────────────────────────────
// INDICADOR DE STEPS
// ─────────────────────────────────────────────────────────────
function atualizarSteps(servicoSelecionado) {
    const badges = [
        document.querySelector('#bloco1 .create-step-badge'),
        document.querySelector('#bloco2 .create-step-badge'),
        document.querySelector('#bloco3 .create-step-badge'),
    ];
    const inds = [
        document.getElementById('stepInd1'),
        document.getElementById('stepInd2'),
        document.getElementById('stepInd3'),
    ];
    badges.forEach((b, i) => {
        if (servicoSelecionado) {
            b.classList.add('--ativo');
            inds[i].classList.remove('create-step--inativo');
            inds[i].classList.add('create-step--ativo');
        } else if (i > 0) {
            b.classList.remove('--ativo');
            inds[i].classList.add('create-step--inativo');
            inds[i].classList.remove('create-step--ativo');
        }
    });
}

// ─────────────────────────────────────────────────────────────
// AUTOCOMPLETE
// ─────────────────────────────────────────────────────────────
const acInput    = document.getElementById('acInput');
const acDropdown = document.getElementById('acDropdown');
const acSpinner  = document.getElementById('acSpinner');
const acClear    = document.getElementById('acClear');
let   timer      = null;
let   resultados = [];
let   focoIdx    = -1;

acInput.addEventListener('input', () => {
    clearTimeout(timer);
    const q = acInput.value.trim();
    if (!q) { fecharDropdown(); return; }
    acSpinner.classList.add('--ativo');
    timer = setTimeout(() => buscar(q), 300);
});

acInput.addEventListener('keydown', e => {
    const itens = acDropdown.querySelectorAll('.ac-item');
    if (e.key === 'ArrowDown') { e.preventDefault(); focoIdx = Math.min(focoIdx+1, itens.length-1); atualizarFoco(itens); }
    if (e.key === 'ArrowUp')   { e.preventDefault(); focoIdx = Math.max(focoIdx-1, 0); atualizarFoco(itens); }
    if (e.key === 'Enter')     { e.preventDefault(); if (focoIdx >= 0 && resultados[focoIdx]) selecionar(resultados[focoIdx]); }
    if (e.key === 'Escape')    { fecharDropdown(); acInput.blur(); }
});

document.addEventListener('click', e => {
    if (!e.target.closest('#acContainer')) fecharDropdown();
});

async function buscar(q) {
    try {
        const r = await fetch(`/api/servicos/buscar?q=${encodeURIComponent(q)}`);
        resultados = await r.json();
        renderDropdown(resultados);
    } catch { /* silencia erro de rede */ }
    finally { acSpinner.classList.remove('--ativo'); }
}

function renderDropdown(items) {
    focoIdx = -1;
    acDropdown.innerHTML = '';

    if (!items.length) {
        acDropdown.innerHTML = '<div class="ac-vazio">Nenhum serviço encontrado para esta busca</div>';
        acDropdown.classList.add('--aberto');
        return;
    }

    items.forEach((item, i) => {
        const div = document.createElement('div');
        div.className = 'ac-item';
        div.dataset.i = i;
        div.innerHTML = `
            <div class="ac-item-nome">
                ${esc(item.nome)}
                ${item.obrigatorio ? '<span class="ac-badge">Obrigatório</span>' : ''}
            </div>
            ${item.descricao ? `<div class="ac-item-desc">${esc(item.descricao)}</div>` : ''}
            ${item.setor_nome ? `<div class="ac-item-setor">🏢 ${esc(item.setor_nome)}</div>` : ''}
        `;
        div.addEventListener('mousedown', e => { e.preventDefault(); selecionar(item); });
        acDropdown.appendChild(div);
    });

    acDropdown.classList.add('--aberto');
}

function atualizarFoco(itens) {
    itens.forEach(el => el.classList.remove('--foco'));
    if (focoIdx >= 0 && itens[focoIdx]) {
        itens[focoIdx].classList.add('--foco');
        itens[focoIdx].scrollIntoView({ block: 'nearest' });
    }
}

function fecharDropdown() {
    acDropdown.classList.remove('--aberto');
    acDropdown.innerHTML = '';
    focoIdx = -1;
}

async function selecionar(item) {
    estado.servicoId = item.id;
    acInput.value = item.nome;
    acInput.classList.add('--selected');
    acInput.classList.remove('--erro');
    document.getElementById('servicoId').value = item.id;
    acClear.style.display = 'inline';
    fecharDropdown();

    // Preenche setor
    preencherSetor(item);

    // Info strip (sem SLA)
    document.getElementById('siSetor').textContent = item.setor_nome || '—';
    document.getElementById('siCargo').textContent = item.cargo_responsavel || '—';
    document.getElementById('servicoInfoStrip').style.display = 'flex';

    // Resumo lateral (sem SLA)
    document.getElementById('resumoVazio').style.display    = 'none';
    document.getElementById('resumoConteudo').style.display = 'block';
    document.getElementById('rServico').textContent = item.nome;
    document.getElementById('rDestino').textContent = item.setor_nome || '—';
    document.getElementById('rCargo').textContent   = item.cargo_responsavel || '—';

    // Atualiza steps
    atualizarSteps(true);

    // Busca documentos obrigatórios
    await buscarRequisitos(item.id);
}

function preencherSetor(item) {
    const campo = document.getElementById('setorDestino');
    const badge = document.getElementById('badgeAutoSetor');
    const hint  = document.getElementById('hintSetor');
    const depId = document.getElementById('depDestinoId');

    if (item.setor_nome) {
        campo.value = item.setor_nome;
        depId.value = item.setor_id || '';
        campo.setAttribute('readonly', true);
        badge.style.display = 'inline';
        hint.textContent = '✅ Preenchido automaticamente pelo serviço selecionado';
        hint.style.color = 'var(--verde)';
    } else {
        campo.value = '';
        depId.value = '';
        campo.removeAttribute('readonly');
        badge.style.display = 'none';
        hint.textContent = 'Setor não configurado — preencha manualmente';
        hint.style.color = 'var(--amarelo)';
    }
}

async function buscarRequisitos(id) {
    try {
        const r    = await fetch(`/api/servicos/${id}/requisitos`);
        const data = await r.json();

        const lista = document.getElementById('docsAlertList');
        const vazio = document.getElementById('docsAlertEmpty');
        const alert = document.getElementById('docsAlert');

        lista.innerHTML = '';

        if (data.documentos_obrigatorios?.length) {
            data.documentos_obrigatorios.forEach(doc => {
                const li = document.createElement('li');
                li.textContent = doc;
                lista.appendChild(li);
            });
            lista.style.display = '';
            vazio.style.display = 'none';
        } else {
            lista.style.display = 'none';
            vazio.style.display = 'block';
        }

        alert.style.display = 'block';
    } catch { /* silencia */ }
}

function limparServico() {
    estado.servicoId = null;
    acInput.value = '';
    acInput.classList.remove('--selected');
    document.getElementById('servicoId').value = '';
    acClear.style.display = 'none';
    document.getElementById('servicoInfoStrip').style.display = 'none';
    document.getElementById('docsAlert').style.display = 'none';
    document.getElementById('resumoVazio').style.display    = 'block';
    document.getElementById('resumoConteudo').style.display = 'none';

    // Reseta setor
    const campo = document.getElementById('setorDestino');
    campo.value = '';
    campo.setAttribute('readonly', true);
    document.getElementById('depDestinoId').value = '';
    document.getElementById('badgeAutoSetor').style.display = 'none';
    const hint = document.getElementById('hintSetor');
    hint.textContent = 'Será preenchido ao selecionar o serviço';
    hint.style.color = '';

    atualizarSteps(false);
    acInput.focus();
}

// ─────────────────────────────────────────────────────────────
// UPLOAD
// ─────────────────────────────────────────────────────────────
function handleDrop(e) {
    e.preventDefault();
    document.getElementById('uploadZone').classList.remove('--over');
    adicionarArquivos(e.dataTransfer.files);
}

function adicionarArquivos(files) {
    Array.from(files).forEach(file => {
        const uid = estado.uid++;
        estado.arquivos.push({ file, tipo: 'outros', uid });
        renderItem({ file, tipo: 'outros', uid });
    });
    sincronizar();
}

function renderItem({ file, tipo, uid }) {
    const kb    = file.size / 1024;
    const tam   = kb < 1024 ? kb.toFixed(1) + ' KB' : (kb/1024).toFixed(2) + ' MB';
    const icone = file.type.includes('image') ? '🖼️' : file.name.endsWith('.pdf') ? '📕' : '📄';

    const optsHtml = Object.entries(TIPOS).map(([v, l]) =>
        `<option value="${v}" ${v === tipo ? 'selected' : ''}>${l}</option>`
    ).join('');

    const div = document.createElement('div');
    div.className = 'upload-item';
    div.id = `uitem-${uid}`;
    div.innerHTML = `
        <div class="upload-item-icone">${icone}</div>
        <div class="upload-item-corpo">
            <div class="upload-item-nome" title="${esc(file.name)}">${esc(file.name)}</div>
            <div class="upload-item-meta">${tam}</div>
            <div class="upload-item-pendente">⏳ Pendente de validação</div>
            <select class="upload-item-select"
                    onchange="mudarTipo(${uid}, this.value)"
                    aria-label="Tipo do documento">
                ${optsHtml}
            </select>
        </div>
        <button type="button" class="upload-item-remove"
                onclick="removerItem(${uid})" title="Remover arquivo">✕</button>
    `;

    document.getElementById('uploadList').appendChild(div);
}

function mudarTipo(uid, tipo) {
    const item = estado.arquivos.find(a => a.uid === uid);
    if (item) { item.tipo = tipo; sincronizar(); }
}

function removerItem(uid) {
    estado.arquivos = estado.arquivos.filter(a => a.uid !== uid);
    const el = document.getElementById(`uitem-${uid}`);
    if (el) el.remove();
    sincronizar();
}

function sincronizar() {
    // Reconstrói o input file com DataTransfer
    const dt = new DataTransfer();
    estado.arquivos.forEach(a => dt.items.add(a.file));
    document.getElementById('fileInput').files = dt.files;

    // Reconstrói os inputs hidden de tipo
    const cont = document.getElementById('hiddenInputs');
    cont.innerHTML = '';
    estado.arquivos.forEach((a, i) => {
        const inp = document.createElement('input');
        inp.type  = 'hidden';
        inp.name  = `tipos_anexo[${i}]`;
        inp.value = a.tipo;
        cont.appendChild(inp);
    });

    // Atualiza contador no resumo
    const el = document.getElementById('rAnexos');
    if (el) el.textContent = estado.arquivos.length;
}

// ─────────────────────────────────────────────────────────────
// VALIDAÇÃO NO SUBMIT
// ─────────────────────────────────────────────────────────────
document.getElementById('formProcesso').addEventListener('submit', function(e) {
    if (!document.getElementById('servicoId').value) {
        e.preventDefault();
        acInput.classList.add('--erro');
        acInput.focus();

        // Insere mensagem de erro inline se não houver
        let err = document.querySelector('.ac-container .p-error');
        if (!err) {
            err = document.createElement('div');
            err.className = 'p-error';
            err.textContent = 'Selecione um serviço antes de continuar.';
            document.getElementById('acContainer').appendChild(err);
        }
        return;
    }

    // Feedback visual no botão
    const btn = document.getElementById('btnAbrir');
    btn.disabled    = true;
    btn.textContent = '⏳ Enviando...';
});

// ─────────────────────────────────────────────────────────────
// UTILIDADE
// ─────────────────────────────────────────────────────────────
function esc(s) {
    return String(s ?? '')
        .replace(/&/g,'&amp;').replace(/</g,'&lt;')
        .replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}
</script>
@endpush