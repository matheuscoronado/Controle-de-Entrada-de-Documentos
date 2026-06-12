{{-- resources/views/admin/tipos/create.blade.php — Novo Serviço --}}
@extends('layouts.app')
@section('title', 'Novo Serviço')
@section('subtitle', 'Cadastre um serviço com seus documentos e responsáveis')

@section('topbar-actions')
    <a href="{{ route('tipos.index') }}" class="btn-outline-sced">← Voltar</a>
@endsection

@section('content')
<div class="row justify-content-center">
<div class="col-lg-9">

<form action="{{ route('tipos.store') }}" method="POST">
    @csrf

    {{-- ═══════════════════════════════════════════════════════
         SEÇÃO 1: Identificação
    ═══════════════════════════════════════════════════════ --}}
    <div class="card-sced mb-4">
        <div class="secao-titulo">📋 Identificação do Serviço</div>

        <div class="mb-3">
            <label class="form-label-sced">Nome do Serviço <span class="obrig">*</span></label>
            <input type="text" name="nome"
                   class="form-input-sced @error('nome') is-invalid @enderror"
                   placeholder="Ex: Solicitação de Benefício, Abertura de Cadastro..."
                   value="{{ old('nome') }}" required>
            @error('nome')<div class="msg-erro">{{ $message }}</div>@enderror
        </div>

        <div class="mb-0">
            <label class="form-label-sced">Descrição</label>
            <textarea name="descricao"
                      class="form-input-sced @error('descricao') is-invalid @enderror"
                      rows="3"
                      placeholder="Descreva a finalidade deste serviço...">{{ old('descricao') }}</textarea>
            @error('descricao')<div class="msg-erro">{{ $message }}</div>@enderror
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════
         SEÇÃO 2: Documentos Necessários
    ═══════════════════════════════════════════════════════ --}}
    <div class="card-sced mb-4">
        <div class="secao-titulo">📄 Tipos de Documentos Necessários</div>
        <div style="font-size:13px;color:var(--cinza-400);margin-bottom:16px;">
            Selecione os documentos que serão exibidos ao solicitante ao abrir um processo deste serviço.
        </div>

        @if($documentosDisponiveis->isEmpty())
            <div style="background:#fffbeb;border:1px solid #fcd34d;border-radius:var(--radius-sm);padding:14px 16px;font-size:13px;color:#92400e;">
                ⚠️ Nenhum documento cadastrado ainda.
                <a href="{{ route('documentos-tipo.create') }}" style="color:var(--azul-claro);font-weight:600;">Cadastrar documentos</a>
                antes de criar um serviço.
            </div>
        @else
            <div class="grid-docs">
                @foreach($documentosDisponiveis as $doc)
                @php $checked = in_array($doc->id, old('documentos_necessarios', [])); @endphp
                <label class="card-doc {{ $checked ? 'card-doc-selecionado' : '' }}" id="doc-label-{{ $doc->id }}">
                    <input type="checkbox"
                           name="documentos_necessarios[]"
                           value="{{ $doc->id }}"
                           {{ $checked ? 'checked' : '' }}
                           onchange="toggleDocCard(this)">
                    <div style="flex:1;">
                        <div style="font-weight:600;font-size:13px;">{{ $doc->nome }}</div>
                        @if($doc->descricao)
                            <div style="font-size:11px;color:var(--cinza-400);margin-top:2px;">{{ Str::limit($doc->descricao, 60) }}</div>
                        @endif
                    </div>
                    <span class="badge-tipo-doc {{ $doc->tipo === 'obrigatorio' ? 'badge-obrig' : 'badge-opcional' }}">
                        {{ $doc->tipo === 'obrigatorio' ? 'Obrigatório' : 'Opcional' }}
                    </span>
                </label>
                @endforeach
            </div>
        @endif

        @error('documentos_necessarios')
            <div class="msg-erro mt-2">{{ $message }}</div>
        @enderror
    </div>

    {{-- ═══════════════════════════════════════════════════════
         SEÇÃO 3: Destino e Responsáveis
    ═══════════════════════════════════════════════════════ --}}
    <div class="card-sced mb-4">
        <div class="secao-titulo">🏢 Setor Destino e Cargos Responsáveis</div>

        <div class="row g-3">
            {{-- Setor Destino --}}
            <div class="col-md-6">
                <label class="form-label-sced">Setor Destino</label>
                <select name="departamento_destino_id"
                        class="form-input-sced @error('departamento_destino_id') is-invalid @enderror">
                    <option value="">— Selecione o setor —</option>
                    @foreach($departamentos as $dep)
                        <option value="{{ $dep->id }}" {{ old('departamento_destino_id') == $dep->id ? 'selected' : '' }}>
                            {{ $dep->nome }}
                        </option>
                    @endforeach
                </select>
                @error('departamento_destino_id')<div class="msg-erro">{{ $message }}</div>@enderror
            </div>

            {{-- Cargos Responsáveis --}}
            <div class="col-md-6">
                <label class="form-label-sced">Cargos Responsáveis</label>
                <div style="display:flex;flex-direction:column;gap:8px;margin-top:4px;">
                    @php $cargosOld = old('cargos_responsaveis', []); @endphp

                    <label class="cargo-check {{ in_array('N1', $cargosOld) ? 'cargo-selecionado' : '' }}" id="cargo-n1">
                        <input type="checkbox" name="cargos_responsaveis[]" value="N1"
                               {{ in_array('N1', $cargosOld) ? 'checked' : '' }}
                               onchange="toggleCargo(this)">
                        <span class="cargo-badge">N1</span>
                        <span style="font-size:13px;">Atendimento</span>
                    </label>

                    <label class="cargo-check {{ in_array('N2', $cargosOld) ? 'cargo-selecionado' : '' }}" id="cargo-n2">
                        <input type="checkbox" name="cargos_responsaveis[]" value="N2"
                               {{ in_array('N2', $cargosOld) ? 'checked' : '' }}
                               onchange="toggleCargo(this)">
                        <span class="cargo-badge">N2</span>
                        <span style="font-size:13px;">Analista</span>
                    </label>

                    <label class="cargo-check {{ in_array('N3', $cargosOld) ? 'cargo-selecionado' : '' }}" id="cargo-n3">
                        <input type="checkbox" name="cargos_responsaveis[]" value="N3"
                               {{ in_array('N3', $cargosOld) ? 'checked' : '' }}
                               onchange="toggleCargo(this)">
                        <span class="cargo-badge">N3</span>
                        <span style="font-size:13px;">Supervisor</span>
                    </label>
                </div>
                @error('cargos_responsaveis')<div class="msg-erro">{{ $message }}</div>@enderror
            </div>
        </div>
    </div>

    {{-- Ações --}}
    <div style="display:flex;gap:12px;justify-content:flex-end;">
        <a href="{{ route('tipos.index') }}" class="btn-outline-sced">Cancelar</a>
        <button type="submit" class="btn-primary-sced">💾 Salvar Serviço</button>
    </div>

</form>
</div>
</div>
@endsection

@push('styles')
<style>
.secao-titulo {
    font-size: 13px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1.2px;
    color: var(--cinza-400);
    margin-bottom: 20px;
    padding-bottom: 12px;
    border-bottom: 1px solid var(--cinza-200);
}
.obrig { color: var(--vermelho); }
.msg-erro { color: var(--vermelho); font-size: 12px; margin-top: 4px; }

/* Grid de documentos */
.grid-docs {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
    gap: 10px;
}
.card-doc {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 12px 14px;
    border: 2px solid var(--cinza-200);
    border-radius: var(--radius-sm);
    cursor: pointer;
    transition: var(--transicao);
    background: var(--branco);
}
.card-doc:hover { border-color: var(--azul-claro); background: #f8faff; }
.card-doc-selecionado {
    border-color: var(--azul-claro) !important;
    background: #eef4ff !important;
}
.card-doc input[type="checkbox"] { accent-color: var(--azul-claro); width: 16px; height: 16px; flex-shrink: 0; }
.badge-tipo-doc {
    padding: 2px 8px;
    border-radius: 12px;
    font-size: 10px;
    font-weight: 700;
    white-space: nowrap;
    flex-shrink: 0;
}
.badge-obrig   { background: #fef2f2; color: #dc2626; }
.badge-opcional { background: #f0f9ff; color: #0369a1; }

/* Cargos */
.cargo-check {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 14px;
    border: 2px solid var(--cinza-200);
    border-radius: var(--radius-sm);
    cursor: pointer;
    transition: var(--transicao);
    background: var(--branco);
}
.cargo-check:hover { border-color: var(--azul-claro); }
.cargo-selecionado {
    border-color: var(--azul-claro) !important;
    background: #eef4ff !important;
}
.cargo-check input[type="checkbox"] { accent-color: var(--azul-claro); width: 16px; height: 16px; }
.cargo-badge {
    font-size: 11px;
    font-weight: 800;
    background: var(--cinza-200);
    color: var(--cinza-800);
    padding: 2px 8px;
    border-radius: 6px;
    font-family: 'JetBrains Mono', monospace;
}
.cargo-selecionado .cargo-badge {
    background: var(--azul-claro);
    color: #fff;
}
</style>
@endpush

@push('scripts')
<script>
function toggleDocCard(checkbox) {
    const label = checkbox.closest('label');
    label.classList.toggle('card-doc-selecionado', checkbox.checked);
}

function toggleCargo(checkbox) {
    const label = checkbox.closest('label');
    label.classList.toggle('cargo-selecionado', checkbox.checked);
    const badge = label.querySelector('.cargo-badge');
    if (checkbox.checked) {
        badge.style.background = 'var(--azul-claro)';
        badge.style.color = '#fff';
    } else {
        badge.style.background = '';
        badge.style.color = '';
    }
}
</script>
@endpush
