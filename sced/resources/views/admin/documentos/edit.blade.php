{{-- resources/views/admin/documentos/edit.blade.php --}}
@extends('layouts.app')
@section('title', 'Editar Documento')
@section('subtitle', 'Atualize os dados do tipo de documento')

@section('topbar-actions')
    <a href="{{ route('documentos-tipo.index') }}" class="btn-outline-sced">← Voltar</a>
@endsection

@section('content')
<div style="max-width:600px;">
    <div class="card-sced" style="padding:28px;">

        <h2 style="font-size:16px;font-weight:700;margin-bottom:24px;color:var(--azul-claro);">✏️ Editar: {{ $documentoTipo->nome }}</h2>

        @if($errors->any())
            <div class="alert-sced alert-error mb-4">
                <ul style="margin:0;padding-left:16px;">
                    @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                </ul>
            </div>
        @endif

        @if(session('success'))
            <div class="alert-sced alert-success mb-4">✅ {{ session('success') }}</div>
        @endif

        <form method="POST" action="{{ route('documentos-tipo.update', $documentoTipo) }}">
            @csrf
            @method('PUT')

            {{-- Nome --}}
            <div class="form-group-sced">
                <label class="label-sced">Nome do Documento <span style="color:var(--vermelho);">*</span></label>
                <input type="text" name="nome" class="input-sced @error('nome') is-invalid @enderror"
                       value="{{ old('nome', $documentoTipo->nome) }}" required>
                @error('nome')<div class="invalid-feedback-sced">{{ $message }}</div>@enderror
            </div>

            {{-- Descrição --}}
            <div class="form-group-sced">
                <label class="label-sced">Descrição <span style="color:var(--vermelho);">*</span></label>
                <textarea name="descricao" class="input-sced @error('descricao') is-invalid @enderror"
                          rows="3" required>{{ old('descricao', $documentoTipo->descricao) }}</textarea>
                @error('descricao')<div class="invalid-feedback-sced">{{ $message }}</div>@enderror
            </div>

            {{-- Tipo --}}
            <div class="form-group-sced">
                <label class="label-sced">Tipo <span style="color:var(--vermelho);">*</span></label>
                <select name="tipo" class="input-sced @error('tipo') is-invalid @enderror" required>
                    <option value="obrigatorio" {{ old('tipo', $documentoTipo->tipo) === 'obrigatorio' ? 'selected' : '' }}>Obrigatório</option>
                    <option value="opcional"    {{ old('tipo', $documentoTipo->tipo) === 'opcional'    ? 'selected' : '' }}>Opcional</option>
                </select>
                @error('tipo')<div class="invalid-feedback-sced">{{ $message }}</div>@enderror
            </div>

            {{-- Status --}}
            <div class="form-group-sced">
                <label class="label-sced">Status</label>
                <select name="status" class="input-sced">
                    <option value="ativo"   {{ old('status', $documentoTipo->status) === 'ativo'   ? 'selected' : '' }}>Ativo</option>
                    <option value="inativo" {{ old('status', $documentoTipo->status) === 'inativo' ? 'selected' : '' }}>Inativo</option>
                </select>
            </div>

            <div style="display:flex;gap:12px;margin-top:24px;">
                <button type="submit" class="btn-primary-sced">💾 Salvar Alterações</button>
                <a href="{{ route('documentos-tipo.index') }}" class="btn-outline-sced">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection