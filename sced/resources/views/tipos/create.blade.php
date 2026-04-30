{{-- ============================================================
     Arquivo: resources/views/tipos/create.blade.php
     ============================================================ --}}
@extends('layouts.app')
@section('title', 'Novo Tipo de Documento')

@section('topbar-actions')
    <a href="{{ route('tipos.index') }}" class="btn-secondary-sced">← Voltar</a>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-lg-6">
        <div class="card-sced card-body-sced">
            <strong style="font-size:16px; color:var(--azul-escuro); display:block; margin-bottom:24px;">
                🏷️ Novo Tipo de Documento
            </strong>
            <form method="POST" action="{{ route('tipos.store') }}">
                @csrf
                <div class="form-group">
                    <label class="form-label-sced">Nome *</label>
                    <input type="text" name="nome"
                           class="form-input-sced {{ $errors->has('nome') ? 'is-invalid' : '' }}"
                           value="{{ old('nome') }}"
                           placeholder="Ex: Ofício, Memorando, Contrato..."
                           required>
                    @error('nome') <div class="form-error">{{ $message }}</div> @enderror
                </div>
                <div class="form-group">
                    <label class="form-label-sced">Descrição</label>
                    <textarea name="descricao" class="form-input-sced" rows="3"
                              placeholder="Descreva o tipo de documento (opcional)...">{{ old('descricao') }}</textarea>
                </div>
                <div style="display:flex; gap:12px; justify-content:flex-end; padding-top:20px; border-top:1px solid var(--cinza-200);">
                    <a href="{{ route('tipos.index') }}" class="btn-secondary-sced">Cancelar</a>
                    <button type="submit" class="btn-primary-sced">💾 Cadastrar Tipo</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
