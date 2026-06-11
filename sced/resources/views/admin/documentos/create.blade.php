{{-- resources/views/admin/documentos/create.blade.php --}}
@extends('layouts.app')
@section('title', 'Novo Documento')
@section('subtitle', 'Cadastre um novo tipo de documento')

@section('topbar-actions')
    <a href="{{ route('documentos-tipo.index') }}" class="btn-outline-sced">← Voltar</a>
@endsection

@section('content')
<div style="max-width:600px;">
    <div class="card-sced" style="padding:28px;">

        <h2 style="font-size:16px;font-weight:700;margin-bottom:24px;color:var(--azul-claro);">📄 Dados do Documento</h2>

        @if($errors->any())
            <div class="alert-sced alert-error mb-4">
                <ul style="margin:0;padding-left:16px;">
                    @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('documentos-tipo.store') }}">
            @csrf

            {{-- Nome --}}
            <div class="form-group-sced">
                <label class="label-sced">Nome do Documento <span style="color:var(--vermelho);">*</span></label>
                <input type="text" name="nome" class="input-sced @error('nome') is-invalid @enderror"
                       value="{{ old('nome') }}" placeholder="Ex: RG, CPF, Certidão de Casamento" required>
                @error('nome')<div class="invalid-feedback-sced">{{ $message }}</div>@enderror
            </div>

            {{-- Descrição --}}
            <div class="form-group-sced">
                <label class="label-sced">Descrição <span style="color:var(--vermelho);">*</span></label>
                <textarea name="descricao" class="input-sced @error('descricao') is-invalid @enderror"
                          rows="3" placeholder="Descreva o documento e quando ele deve ser apresentado" required>{{ old('descricao') }}</textarea>
                @error('descricao')<div class="invalid-feedback-sced">{{ $message }}</div>@enderror
            </div>

            {{-- Tipo --}}
            <div class="form-group-sced">
                <label class="label-sced">Tipo <span style="color:var(--vermelho);">*</span></label>
                <select name="tipo" class="input-sced @error('tipo') is-invalid @enderror" required>
                    <option value="">Selecione...</option>
                    <option value="obrigatorio" {{ old('tipo') === 'obrigatorio' ? 'selected' : '' }}>Obrigatório</option>
                    <option value="opcional"    {{ old('tipo') === 'opcional'    ? 'selected' : '' }}>Opcional</option>
                </select>
                @error('tipo')<div class="invalid-feedback-sced">{{ $message }}</div>@enderror
            </div>

            <div style="display:flex;gap:12px;margin-top:24px;">
                <button type="submit" class="btn-primary-sced">💾 Salvar Documento</button>
                <a href="{{ route('documentos-tipo.index') }}" class="btn-outline-sced">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection