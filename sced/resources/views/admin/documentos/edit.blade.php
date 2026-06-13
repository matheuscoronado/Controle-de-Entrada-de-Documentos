{{-- ============================================================
     resources/views/admin/documentos/edit.blade.php
     EDITAR DOCUMENTO - VERSÃO SIMPLIFICADA
     ============================================================ --}}
@extends('layouts.app')
@section('title', 'Editar Documento')
@section('subtitle', $documentoTipo->nome)

@section('topbar-actions')
    <a href="{{ route('documentos-tipo.index') }}" class="btn-outline-sced">← Voltar</a>
@endsection

@section('content')

<style>
    .form-card {
        background: var(--branco);
        border-radius: 16px;
        border: 1px solid var(--cinza-200);
        padding: 28px;
        margin-bottom: 24px;
    }
    .form-section-title {
        font-size: 14px;
        font-weight: 700;
        color: var(--azul-escuro);
        margin-bottom: 24px;
        padding-bottom: 12px;
        border-bottom: 1px solid var(--cinza-200);
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .form-error {
        font-size: 12px;
        color: var(--vermelho);
        margin-top: 4px;
    }
    .helper-text {
        font-size: 11px;
        color: var(--cinza-400);
        margin-top: 4px;
    }
    
    .radio-group {
        display: flex;
        gap: 20px;
        margin-top: 8px;
    }
    .radio-option {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 12px 20px;
        border: 2px solid var(--cinza-200);
        border-radius: 12px;
        cursor: pointer;
        transition: all 0.2s;
        flex: 1;
    }
    .radio-option:hover {
        border-color: var(--azul-claro);
    }
    .radio-option.selected-obrigatorio {
        border-color: #dc2626;
        background: #fff8f8;
    }
    .radio-option.selected-opcional {
        border-color: var(--azul-claro);
        background: #f0f7ff;
    }
    .radio-option input[type="radio"] {
        width: 18px;
        height: 18px;
        accent-color: var(--azul-claro);
        cursor: pointer;
    }
    
    .status-select {
        padding: 10px 14px;
        border-radius: 8px;
        border: 1px solid var(--cinza-200);
        width: 100%;
        font-family: 'Sora', sans-serif;
        font-size: 14px;
    }
</style>

<div class="row justify-content-center">
    <div class="col-lg-7">

        <div class="form-card">
            <div class="form-section-title">
                ✏️ Editar Documento: {{ $documentoTipo->nome }}
            </div>

            @if($errors->any())
                <div class="alert alert-danger mb-4">
                    <ul style="margin: 0; padding-left: 16px;">
                        @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                    </ul>
                </div>
            @endif

            {{-- 🔧 CORREÇÃO: Usando URL direta em vez de route() --}}
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
                    <div class="radio-group">
                        @php $tipoAtual = old('tipo', $documentoTipo->tipo); @endphp
                        <label class="radio-option {{ $tipoAtual == 'obrigatorio' ? 'selected-obrigatorio' : '' }}" id="label-obrigatorio">
                            <input type="radio" name="tipo" value="obrigatorio" {{ $tipoAtual == 'obrigatorio' ? 'checked' : '' }} onchange="highlightTipo(this)">
                            <div>
                                <div style="font-weight: 600; color: #dc2626;">🔴 Obrigatório</div>
                                <div style="font-size: 11px; color: var(--cinza-400);">Sempre exigido no processo</div>
                            </div>
                        </label>
                        <label class="radio-option {{ $tipoAtual == 'opcional' ? 'selected-opcional' : '' }}" id="label-opcional">
                            <input type="radio" name="tipo" value="opcional" {{ $tipoAtual == 'opcional' ? 'checked' : '' }} onchange="highlightTipo(this)">
                            <div>
                                <div style="font-weight: 600; color: var(--azul-claro);">🔵 Opcional</div>
                                <div style="font-size: 11px; color: var(--cinza-400);">Complementar ao processo</div>
                            </div>
                        </label>
                    </div>
                    @error('tipo')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="mb-4">
                    <label class="form-label-sced">Status</label>
                    <select name="status" class="status-select">
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

<script>
    function highlightTipo(el) {
        const labelObrig = document.getElementById('label-obrigatorio');
        const labelOpc = document.getElementById('label-opcional');
        labelObrig.classList.remove('selected-obrigatorio', 'selected-opcional');
        labelOpc.classList.remove('selected-obrigatorio', 'selected-opcional');
        
        if (el.value === 'obrigatorio') {
            labelObrig.classList.add('selected-obrigatorio');
        } else {
            labelOpc.classList.add('selected-opcional');
        }
    }
    
    document.addEventListener('DOMContentLoaded', () => {
        const checked = document.querySelector('input[name="tipo"]:checked');
        if (checked) highlightTipo(checked);
    });
</script>

@endsection