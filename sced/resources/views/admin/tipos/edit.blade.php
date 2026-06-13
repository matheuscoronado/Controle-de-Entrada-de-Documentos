{{-- ============================================================
     resources/views/admin/tipos/edit.blade.php
     EDITAR SERVIÇO - Com vinculação de documentos
     ============================================================ --}}
@extends('layouts.app')
@section('title', 'Editar Serviço: ' . $tipo->nome)
@section('subtitle', 'Altere os dados, documentos e responsáveis deste serviço')

@section('topbar-actions')
    <a href="{{ route('tipos.index') }}" class="btn-outline-sced">← Voltar</a>
@endsection

@section('content')
<div class="row justify-content-center">
<div class="col-lg-9">

<form action="{{ route('tipos.update', $tipo) }}" method="POST">
    @csrf
    @method('PUT')

    {{-- ═══════════════════════════════════════════════════════
         SEÇÃO 1: Identificação
    ═══════════════════════════════════════════════════════ --}}
    <div class="card-sced mb-4">
        <div class="secao-titulo">📋 Identificação do Serviço</div>

        <div class="row g-3">
            <div class="col-md-8">
                <label class="form-label-sced">Nome do Serviço <span class="obrig">*</span></label>
                <input type="text" name="nome"
                       class="form-input-sced @error('nome') is-invalid @enderror"
                       value="{{ old('nome', $tipo->nome) }}" required>
                @error('nome')<div class="msg-erro">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-4">
                <label class="form-label-sced">Status <span class="obrig">*</span></label>
                <select name="status" class="form-input-sced" required>
                    <option value="ativo"   {{ old('status', $tipo->status) === 'ativo'   ? 'selected' : '' }}>● Ativo</option>
                    <option value="inativo" {{ old('status', $tipo->status) === 'inativo' ? 'selected' : '' }}>● Inativo</option>
                </select>
                @if($tipo->documentos()->count() > 0)
                    <div style="font-size:12px;color:var(--amarelo);margin-top:4px;">
                        ⚠️ Este serviço possui {{ $tipo->documentos()->count() }} processo(s) vinculado(s). Inativar não é possível se houver processos.
                    </div>
                @endif
            </div>

            <div class="col-12">
                <label class="form-label-sced">Descrição</label>
                <textarea name="descricao" class="form-input-sced" rows="3">{{ old('descricao', $tipo->descricao) }}</textarea>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════
         SEÇÃO 2: Documentos Vinculados
    ═══════════════════════════════════════════════════════ --}}
    <div class="card-sced mb-4">
        <div class="secao-titulo">📄 Documentos Vinculados</div>
        <div style="font-size:13px;color:var(--cinza-400);margin-bottom:16px;">
            Selecione os documentos que serão exigidos ao abrir um processo deste serviço.
        </div>

        @if($documentosDisponiveis->isEmpty())
            <div style="background:#fffbeb;border:1px solid #fcd34d;border-radius:var(--radius-sm);padding:14px 16px;font-size:13px;color:#92400e;">
                ⚠️ Nenhum documento cadastrado ainda.
                <a href="{{ route('documentos-tipo.create') }}" style="color:var(--azul-claro);font-weight:600;">Cadastrar documentos</a>
            </div>
        @else
            @php $selecionados = old('documentos_necessarios', $documentosSelecionados); @endphp
            <div class="grid-docs">
                @foreach($documentosDisponiveis as $doc)
                @php $checked = in_array($doc->id, $selecionados); @endphp
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
         SEÇÃO 3: Destino e Cargos Responsáveis
    ═══════════════════════════════════════════════════════ --}}
    <div class="card-sced mb-4">
        <div class="secao-titulo">🏢 Setor Destino e Cargos Responsáveis</div>

        <div class="row g-3">
            {{-- Setor Destino --}}
            <div class="col-md-6">
                <label class="form-label-sced">Setor Destino</label>
                <select name="departamento_destino_id" class="form-input-sced">
                    <option value="">— Selecione —</option>
                    @foreach($departamentos as $dep)
                        <option value="{{ $dep->id }}"
                                {{ old('departamento_destino_id', $tipo->departamento_destino_id) == $dep->id ? 'selected' : '' }}>
                            {{ $dep->nome }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Cargos Responsáveis --}}
            <div class="col-md-6">
                <label class="form-label-sced">Cargos Responsáveis</label>
                @php $cargosAtivos = old('cargos_responsaveis', $tipo->cargos_responsaveis ?? []); @endphp
                <div style="display:flex;flex-direction:column;gap:8px;margin-top:4px;">

                    <label class="cargo-check {{ in_array('N1', $cargosAtivos) ? 'cargo-selecionado' : '' }}" id="cargo-n1">
                        <input type="checkbox" name="cargos_responsaveis[]" value="N1"
                               {{ in_array('N1', $cargosAtivos) ? 'checked' : '' }}
                               onchange="toggleCargo(this)">
                        <span class="cargo-badge {{ in_array('N1', $cargosAtivos) ? 'cargo-badge-on' : '' }}">N1</span>
                        <span style="font-size:13px;">Atendimento</span>
                    </label>

                    <label class="cargo-check {{ in_array('N2', $cargosAtivos) ? 'cargo-selecionado' : '' }}" id="cargo-n2">
                        <input type="checkbox" name="cargos_responsaveis[]" value="N2"
                               {{ in_array('N2', $cargosAtivos) ? 'checked' : '' }}
                               onchange="toggleCargo(this)">
                        <span class="cargo-badge {{ in_array('N2', $cargosAtivos) ? 'cargo-badge-on' : '' }}">N2</span>
                        <span style="font-size:13px;">Analista</span>
                    </label>

                    <label class="cargo-check {{ in_array('N3', $cargosAtivos) ? 'cargo-selecionado' : '' }}" id="cargo-n3">
                        <input type="checkbox" name="cargos_responsaveis[]" value="N3"
                               {{ in_array('N3', $cargosAtivos) ? 'checked' : '' }}
                               onchange="toggleCargo(this)">
                        <span class="cargo-badge {{ in_array('N3', $cargosAtivos) ? 'cargo-badge-on' : '' }}">N3</span>
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
        <button type="submit" class="btn-primary-sced">💾 Salvar Alterações</button>
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
.obrig    { color: var(--vermelho); }
.msg-erro { color: var(--vermelho); font-size: 12px; margin-top: 4px; }

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
.badge-obrig    { background: #fef2f2; color: #dc2626; }
.badge-opcional { background: #f0f9ff; color: #0369a1; }

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
.cargo-selecionado { border-color: var(--azul-claro) !important; background: #eef4ff !important; }
.cargo-check input[type="checkbox"] { accent-color: var(--azul-claro); width: 16px; height: 16px; }
.cargo-badge {
    font-size: 11px;
    font-weight: 800;
    background: var(--cinza-200);
    color: var(--cinza-800);
    padding: 2px 8px;
    border-radius: 6px;
    font-family: 'JetBrains Mono', monospace;
    transition: var(--transicao);
}
.cargo-badge-on { background: var(--azul-claro) !important; color: #fff !important; }
</style>
@endpush

@push('scripts')
<script>
function toggleDocCard(checkbox) {
    checkbox.closest('label').classList.toggle('card-doc-selecionado', checkbox.checked);
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