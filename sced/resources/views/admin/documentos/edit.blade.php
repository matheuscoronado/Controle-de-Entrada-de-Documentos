{{-- ============================================================
     resources/views/admin/documentos/edit.blade.php
     EDITAR DOCUMENTO - VERSÃO FUNCIONAL
     ============================================================ --}}
@extends('layouts.app')
@section('title', 'Editar Documento')
@section('subtitle', $documentoTipo->nome)

@section('topbar-actions')
    <a href="{{ route('documentos-tipo.index') }}" class="btn-outline-sced">← Voltar</a>
@endsection

@section('content')

<div class="row justify-content-center">
    <div class="col-lg-7">

        <div class="card-sced card-body-sced">
            <div class="mb-4">
                <h4 class="mb-0" style="font-size: 16px; font-weight: 700; color: var(--azul-escuro);">
                    ✏️ Editar Documento: {{ $documentoTipo->nome }}
                </h4>
                <hr class="my-3">
            </div>

            @if($errors->any())
                <div class="alert alert-danger mb-4">
                    <ul class="mb-0">
                        @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ url('documentos-tipo/' . $documentoTipo->id) }}">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label-sced">Nome do Documento <span class="text-danger">*</span></label>
                    <input type="text" name="nome" class="form-input-sced @error('nome') is-invalid @enderror"
                           value="{{ old('nome', $documentoTipo->nome) }}" required>
                    @error('nome')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label-sced">Descrição <span class="text-danger">*</span></label>
                    <textarea name="descricao" class="form-input-sced @error('descricao') is-invalid @enderror"
                              rows="3" required>{{ old('descricao', $documentoTipo->descricao) }}</textarea>
                    @error('descricao')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label-sced">Tipo <span class="text-danger">*</span></label>
                    <div class="d-flex gap-3">
                        <label class="d-flex align-items-center gap-2">
                            <input type="radio" name="tipo" value="obrigatorio" 
                                   {{ old('tipo', $documentoTipo->tipo) == 'obrigatorio' ? 'checked' : '' }}>
                            <span class="text-danger">🔴 Obrigatório</span>
                        </label>
                        <label class="d-flex align-items-center gap-2">
                            <input type="radio" name="tipo" value="opcional" 
                                   {{ old('tipo', $documentoTipo->tipo) == 'opcional' ? 'checked' : '' }}>
                            <span class="text-primary">🔵 Opcional</span>
                        </label>
                    </div>
                    @error('tipo')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="mb-4">
                    <label class="form-label-sced">Status</label>
                    <select name="status" class="form-input-sced">
                        <option value="ativo" {{ old('status', $documentoTipo->status) == 'ativo' ? 'selected' : '' }}>🟢 Ativo</option>
                        <option value="inativo" {{ old('status', $documentoTipo->status) == 'inativo' ? 'selected' : '' }}>🔴 Inativo</option>
                    </select>
                </div>

                <div class="d-flex justify-content-end gap-3 mt-4 pt-3 border-top">
                    <a href="{{ route('documentos-tipo.index') }}" class="btn-secondary-sced">Cancelar</a>
                    <button type="submit" class="btn-primary-sced">💾 Salvar Alterações</button>
                </div>

            </form>
        </div>

    </div>
</div>

<style>
    .card-sced {
        background: var(--branco);
        border-radius: 16px;
        border: 1px solid var(--cinza-200);
        box-shadow: var(--sombra-card);
    }
    .card-body-sced {
        padding: 28px;
    }
    .form-label-sced {
        display: block;
        font-size: 12px;
        font-weight: 600;
        color: var(--cinza-600);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 8px;
    }
    .form-input-sced {
        width: 100%;
        padding: 12px 14px;
        border: 1.5px solid var(--cinza-200);
        border-radius: 10px;
        font-family: 'Sora', sans-serif;
        font-size: 14px;
        transition: all 0.2s;
    }
    .form-input-sced:focus {
        border-color: var(--azul-claro);
        outline: none;
        box-shadow: 0 0 0 3px rgba(37,99,235,.1);
    }
    .form-error {
        font-size: 12px;
        color: var(--vermelho);
        margin-top: 5px;
    }
    .btn-primary-sced {
        background: var(--azul-claro);
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 10px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
    }
    .btn-primary-sced:hover {
        background: var(--azul-hover);
        transform: translateY(-1px);
    }
    .btn-secondary-sced {
        background: var(--cinza-200);
        color: var(--cinza-600);
        border: none;
        padding: 10px 20px;
        border-radius: 10px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
    }
    .btn-secondary-sced:hover {
        background: var(--cinza-300);
    }
    .btn-outline-sced {
        background: transparent;
        border: 1.5px solid var(--azul-claro);
        color: var(--azul-claro);
        padding: 8px 16px;
        border-radius: 10px;
        font-weight: 500;
        transition: all 0.2s;
    }
    .btn-outline-sced:hover {
        background: var(--azul-claro);
        color: white;
        text-decoration: none;
    }
    .alert {
        padding: 12px 16px;
        border-radius: 10px;
        margin-bottom: 20px;
    }
    .alert-danger {
        background: #fef2f2;
        color: #991b1b;
        border: 1px solid #fecaca;
    }
    .text-danger { color: #dc2626; }
    .text-primary { color: var(--azul-claro); }
    .border-top { border-top: 1px solid var(--cinza-200); }
    .mt-4 { margin-top: 20px; }
    .mb-3 { margin-bottom: 16px; }
    .mb-4 { margin-bottom: 20px; }
    .pt-3 { padding-top: 16px; }
    .gap-3 { gap: 16px; }
    .gap-2 { gap: 8px; }
    .d-flex { display: flex; }
    .align-items-center { align-items: center; }
    .justify-content-end { justify-content: flex-end; }
</style>

@endsection