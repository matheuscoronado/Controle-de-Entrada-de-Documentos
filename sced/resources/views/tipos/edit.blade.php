{{-- ============================================================
     Arquivo: resources/views/tipos/edit.blade.php
     ============================================================ --}}
@extends('layouts.app')
@section('title', 'Editar Tipo')
@section('subtitle', $tipo->nome)

@section('topbar-actions')
    <a href="{{ route('tipos.index') }}" class="btn-secondary-sced">← Voltar</a>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-lg-6">
        <div class="card-sced card-body-sced">
            <strong style="font-size:16px; color:var(--azul-escuro); display:block; margin-bottom:24px;">
                ✏️ Editar Tipo de Documento
            </strong>
            <form method="POST" action="{{ route('tipos.update', $tipo) }}">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label class="form-label-sced">Nome *</label>
                    <input type="text" name="nome"
                           class="form-input-sced {{ $errors->has('nome') ? 'is-invalid' : '' }}"
                           value="{{ old('nome', $tipo->nome) }}"
                           required>
                    @error('nome') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label-sced">Descrição</label>
                    <textarea name="descricao" class="form-input-sced" rows="3">{{ old('descricao', $tipo->descricao) }}</textarea>
                </div>

                <div class="form-group">
                    <label class="form-label-sced">Status *</label>
                    <select name="status" class="form-input-sced" required>
                        <option value="ativo"   {{ old('status', $tipo->status)=='ativo'   ? 'selected' : '' }}>● Ativo</option>
                        <option value="inativo" {{ old('status', $tipo->status)=='inativo' ? 'selected' : '' }}>● Inativo</option>
                    </select>
                    @if($tipo->documentos()->exists())
                    <div style="font-size:11px; color:var(--amarelo); margin-top:4px;">
                        ⚠️ Este tipo possui documentos vinculados — não pode ser desativado.
                    </div>
                    @endif
                </div>

                <div style="display:flex; gap:12px; justify-content:flex-end; padding-top:20px; border-top:1px solid var(--cinza-200);">
                    <a href="{{ route('tipos.index') }}" class="btn-secondary-sced">Cancelar</a>
                    <button type="submit" class="btn-primary-sced">💾 Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
