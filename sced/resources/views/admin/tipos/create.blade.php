{{-- ============================================================
     resources/views/admin/tipos/create.blade.php
     Formulário de Cadastro de Tipo de Documento (Parte 1)
     ============================================================ --}}
@extends('layouts.app')
@section('title', 'Novo Tipo de Documento')
@section('subtitle', 'Parametrize o tipo, destino e responsável')

@section('topbar-actions')
    <a href="{{ route('tipos.index') }}" class="btn-outline-sced">← Voltar</a>
@endsection

@section('content')
<div class="row justify-content-center">
<div class="col-lg-8">

<form action="{{ route('tipos.store') }}" method="POST">
    @csrf

    {{-- Seção 1: Identificação --}}
    <div class="card-sced mb-4">
        <div style="font-size:13px;font-weight:700;text-transform:uppercase;letter-spacing:1.2px;color:var(--cinza-400);margin-bottom:20px;padding-bottom:12px;border-bottom:1px solid var(--cinza-200);">
            📋 Identificação do Documento
        </div>

        <div class="mb-3">
            <label class="form-label-sced">Nome do Documento <span style="color:var(--vermelho)">*</span></label>
            <input type="text" name="nome" class="form-control-sced @error('nome') is-invalid @enderror"
                   placeholder="Ex: Memorando, Ofício, Requerimento..."
                   value="{{ old('nome') }}" required>
            @error('nome')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
            <label class="form-label-sced">Descrição</label>
            <textarea name="descricao" class="form-control-sced @error('descricao') is-invalid @enderror"
                      rows="3" placeholder="Descreva a finalidade deste tipo de documento...">{{ old('descricao') }}</textarea>
            @error('descricao')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="mb-0">
            <label class="form-label-sced">Indicador de Exigência <span style="color:var(--vermelho)">*</span></label>
            <div style="display:flex;gap:12px;margin-top:8px;">
                <label style="display:flex;align-items:center;gap:8px;cursor:pointer;padding:12px 20px;border:2px solid var(--cinza-200);border-radius:var(--radius-sm);flex:1;transition:var(--transicao);"
                       id="label-obrigatorio">
                    <input type="radio" name="obrigatoriedade" value="obrigatorio"
                           {{ old('obrigatoriedade') === 'obrigatorio' ? 'checked' : '' }}
                           onchange="highlightObrig(this)">
                    <div>
                        <div style="font-weight:600;font-size:14px;color:var(--vermelho);">🔴 Obrigatório</div>
                        <div style="font-size:12px;color:var(--cinza-400);">Sempre exigido no processo</div>
                    </div>
                </label>
                <label style="display:flex;align-items:center;gap:8px;cursor:pointer;padding:12px 20px;border:2px solid var(--cinza-200);border-radius:var(--radius-sm);flex:1;transition:var(--transicao);"
                       id="label-opcional">
                    <input type="radio" name="obrigatoriedade" value="opcional"
                           {{ old('obrigatoriedade', 'opcional') === 'opcional' ? 'checked' : '' }}
                           onchange="highlightObrig(this)">
                    <div>
                        <div style="font-weight:600;font-size:14px;color:var(--azul-claro);">🔵 Opcional</div>
                        <div style="font-size:12px;color:var(--cinza-400);">Complementar ao processo</div>
                    </div>
                </label>
            </div>
            @error('obrigatoriedade')<div class="text-danger" style="font-size:13px;margin-top:4px;">{{ $message }}</div>@enderror
        </div>
    </div>

    {{-- Seção 2: Configuração de Destino e Responsável --}}
    <div class="card-sced mb-4">
        <div style="font-size:13px;font-weight:700;text-transform:uppercase;letter-spacing:1.2px;color:var(--cinza-400);margin-bottom:20px;padding-bottom:12px;border-bottom:1px solid var(--cinza-200);">
            🏢 Destino e Responsável
        </div>

        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label-sced">Setor de Destino</label>
                <select name="departamento_destino_id" class="form-control-sced @error('departamento_destino_id') is-invalid @enderror">
                    <option value="">— Selecione um departamento —</option>
                    @foreach($departamentos as $dep)
                        <option value="{{ $dep->id }}" {{ old('departamento_destino_id') == $dep->id ? 'selected' : '' }}>
                            {{ $dep->nome }}
                        </option>
                    @endforeach
                </select>
                @error('departamento_destino_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-6">
                <label class="form-label-sced">Cargo Responsável</label>
                <select name="cargo_responsavel" class="form-control-sced @error('cargo_responsavel') is-invalid @enderror">
                    <option value="">— Selecione o nível —</option>
                    <option value="N1" {{ old('cargo_responsavel') === 'N1' ? 'selected' : '' }}>N1 — Atendimento</option>
                    <option value="N2" {{ old('cargo_responsavel') === 'N2' ? 'selected' : '' }}>N2 — Analista</option>
                    <option value="N3" {{ old('cargo_responsavel') === 'N3' ? 'selected' : '' }}>N3 — Supervisor</option>
                </select>
                @error('cargo_responsavel')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-6">
                <label class="form-label-sced">SLA — Prazo Máximo (em horas)</label>
                <input type="number" name="sla_horas" class="form-control-sced @error('sla_horas') is-invalid @enderror"
                       placeholder="Ex: 24 = 1 dia / 72 = 3 dias"
                       min="1" max="8760" value="{{ old('sla_horas') }}">
                <div style="font-size:12px;color:var(--cinza-400);margin-top:4px;">Deixe vazio se não houver prazo definido.</div>
                @error('sla_horas')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>
    </div>

    {{-- Ações --}}
    <div style="display:flex;gap:12px;justify-content:flex-end;">
        <a href="{{ route('tipos.index') }}" class="btn-outline-sced">Cancelar</a>
        <button type="submit" class="btn-primary-sced">💾 Salvar Tipo</button>
    </div>
</form>

</div>
</div>
@endsection

@push('styles')
<style>
.form-label-sced {
    font-size: 13px;
    font-weight: 600;
    color: var(--cinza-600);
    margin-bottom: 6px;
    display: block;
}
.form-control-sced {
    width: 100%;
    padding: 10px 14px;
    border: 1.5px solid var(--cinza-200);
    border-radius: var(--radius-sm);
    font-size: 14px;
    font-family: 'Sora', sans-serif;
    background: var(--branco);
    color: var(--cinza-800);
    transition: var(--transicao);
    outline: none;
}
.form-control-sced:focus {
    border-color: var(--azul-claro);
    box-shadow: 0 0 0 3px rgba(37,99,235,0.12);
}
.form-control-sced.is-invalid { border-color: var(--vermelho); }
.invalid-feedback { color: var(--vermelho); font-size: 12px; margin-top: 4px; }
</style>
@endpush

@push('scripts')
<script>
function highlightObrig(el) {
    document.getElementById('label-obrigatorio').style.borderColor = 'var(--cinza-200)';
    document.getElementById('label-opcional').style.borderColor = 'var(--cinza-200)';
    const label = el.closest('label');
    label.style.borderColor = el.value === 'obrigatorio' ? 'var(--vermelho)' : 'var(--azul-claro)';
}
// Inicializa estado dos botões ao carregar
document.addEventListener('DOMContentLoaded', () => {
    const checked = document.querySelector('input[name="obrigatoriedade"]:checked');
    if (checked) highlightObrig(checked);
});
</script>
@endpush
