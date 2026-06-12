{{-- resources/views/admin/documentos/edit.blade.php --}}
@extends('layouts.app')
@section('title', 'Editar Documento')
@section('subtitle', 'Atualize os dados do tipo de documento')

@section('topbar-actions')
    <a href="{{ route('documentos-tipo.index') }}" class="btn-outline-sced">← Voltar</a>
@endsection

@section('content')
<div class="row justify-content-center">
<div class="col-lg-7">

    <div class="card-sced">

        <div style="font-size:13px;font-weight:700;text-transform:uppercase;letter-spacing:1.2px;color:var(--cinza-400);margin-bottom:20px;padding-bottom:12px;border-bottom:1px solid var(--cinza-200);">
            ✏️ Editar: {{ $documentoTipo->nome }}
        </div>

        @if($errors->any())
            <div class="alert-sced alert-error mb-4">
                <ul style="margin:0;padding-left:16px;">
                    @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('documentos-tipo.update', $documentoTipo) }}">
            @csrf
            @method('PUT')

            {{-- Nome --}}
            <div class="mb-3">
                <label class="form-label-sced">Nome do Documento <span style="color:var(--vermelho);">*</span></label>
                <input type="text" name="nome"
                       class="form-input-sced @error('nome') is-invalid @enderror"
                       value="{{ old('nome', $documentoTipo->nome) }}"
                       required>
                @error('nome')<div style="color:var(--vermelho);font-size:12px;margin-top:4px;">{{ $message }}</div>@enderror
            </div>

            {{-- Descrição --}}
            <div class="mb-3">
                <label class="form-label-sced">Descrição <span style="color:var(--vermelho);">*</span></label>
                <textarea name="descricao"
                          class="form-input-sced @error('descricao') is-invalid @enderror"
                          rows="3"
                          required>{{ old('descricao', $documentoTipo->descricao) }}</textarea>
                @error('descricao')<div style="color:var(--vermelho);font-size:12px;margin-top:4px;">{{ $message }}</div>@enderror
            </div>

            {{-- Tipo --}}
            <div class="mb-3">
                <label class="form-label-sced">Tipo <span style="color:var(--vermelho);">*</span></label>
                <div style="display:flex;gap:12px;margin-top:8px;">
                    @php $tipoAtual = old('tipo', $documentoTipo->tipo); @endphp
                    <label class="card-tipo-radio {{ $tipoAtual === 'obrigatorio' ? 'selected-obrig' : '' }}" id="label-obrigatorio">
                        <input type="radio" name="tipo" value="obrigatorio"
                               {{ $tipoAtual === 'obrigatorio' ? 'checked' : '' }}
                               onchange="highlightTipo(this)">
                        <div>
                            <div style="font-weight:600;font-size:14px;color:var(--vermelho);">🔴 Obrigatório</div>
                            <div style="font-size:12px;color:var(--cinza-400);margin-top:2px;">Sempre exigido no processo</div>
                        </div>
                    </label>
                    <label class="card-tipo-radio {{ $tipoAtual === 'opcional' ? 'selected-opcional' : '' }}" id="label-opcional">
                        <input type="radio" name="tipo" value="opcional"
                               {{ $tipoAtual === 'opcional' ? 'checked' : '' }}
                               onchange="highlightTipo(this)">
                        <div>
                            <div style="font-weight:600;font-size:14px;color:var(--azul-claro);">🔵 Opcional</div>
                            <div style="font-size:12px;color:var(--cinza-400);margin-top:2px;">Complementar ao processo</div>
                        </div>
                    </label>
                </div>
                @error('tipo')<div style="color:var(--vermelho);font-size:12px;margin-top:4px;">{{ $message }}</div>@enderror
            </div>

            {{-- Status --}}
            <div class="mb-4">
                <label class="form-label-sced">Status</label>
                <select name="status" class="form-input-sced">
                    <option value="ativo"   {{ old('status', $documentoTipo->status) === 'ativo'   ? 'selected' : '' }}>● Ativo</option>
                    <option value="inativo" {{ old('status', $documentoTipo->status) === 'inativo' ? 'selected' : '' }}>● Inativo</option>
                </select>
            </div>

            <div style="display:flex;gap:12px;">
                <button type="submit" class="btn-primary-sced">💾 Salvar Alterações</button>
                <a href="{{ route('documentos-tipo.index') }}" class="btn-outline-sced">Cancelar</a>
            </div>
        </form>
    </div>

</div>
</div>
@endsection

@push('styles')
<style>
.card-tipo-radio {
    display: flex;
    align-items: center;
    gap: 10px;
    cursor: pointer;
    padding: 14px 18px;
    border: 2px solid var(--cinza-200);
    border-radius: var(--radius-sm);
    flex: 1;
    transition: var(--transicao);
}
.card-tipo-radio:hover { border-color: var(--cinza-400); }
.selected-obrig   { border-color: var(--vermelho) !important; background: #fff8f8; }
.selected-opcional { border-color: var(--azul-claro) !important; background: #f0f7ff; }
.card-tipo-radio input[type="radio"] { accent-color: var(--azul-claro); }
</style>
@endpush

@push('scripts')
<script>
function highlightTipo(el) {
    document.getElementById('label-obrigatorio').className = 'card-tipo-radio';
    document.getElementById('label-opcional').className = 'card-tipo-radio';
    const label = el.closest('label');
    label.className = 'card-tipo-radio ' + (el.value === 'obrigatorio' ? 'selected-obrig' : 'selected-opcional');
}
document.addEventListener('DOMContentLoaded', () => {
    const checked = document.querySelector('input[name="tipo"]:checked');
    if (checked) highlightTipo(checked);
});
</script>
@endpush
