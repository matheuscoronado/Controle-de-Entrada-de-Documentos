{{-- ============================================================
     resources/views/admin/tipos/edit.blade.php
     Edição de Tipo de Documento
     ============================================================ --}}
@extends('layouts.app')
@section('title', 'Editar Tipo: ' . $tipo->nome)
@section('subtitle', 'Altere a parametrização deste tipo de documento')

@section('topbar-actions')
    <a href="{{ route('tipos.index') }}" class="btn-outline-sced">← Voltar</a>
@endsection

@section('content')
<div class="row justify-content-center">
<div class="col-lg-8">

<form action="{{ route('tipos.update', $tipo) }}" method="POST">
    @csrf
    @method('PUT')

    {{-- Seção 1: Identificação --}}
    <div class="card-sced mb-4">
        <div style="font-size:13px;font-weight:700;text-transform:uppercase;letter-spacing:1.2px;color:var(--cinza-400);margin-bottom:20px;padding-bottom:12px;border-bottom:1px solid var(--cinza-200);">
            📋 Identificação do Documento
        </div>

        <div class="mb-3">
            <label class="form-label-sced">Nome do Documento <span style="color:var(--vermelho)">*</span></label>
            <input type="text" name="nome" class="form-control-sced @error('nome') is-invalid @enderror"
                   value="{{ old('nome', $tipo->nome) }}" required>
            @error('nome')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
            <label class="form-label-sced">Descrição</label>
            <textarea name="descricao" class="form-control-sced" rows="3">{{ old('descricao', $tipo->descricao) }}</textarea>
        </div>

        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label-sced">Indicador de Exigência <span style="color:var(--vermelho)">*</span></label>
                <select name="obrigatoriedade" class="form-control-sced" required>
                    <option value="obrigatorio" {{ old('obrigatoriedade', $tipo->obrigatoriedade) === 'obrigatorio' ? 'selected' : '' }}>🔴 Obrigatório</option>
                    <option value="opcional"    {{ old('obrigatoriedade', $tipo->obrigatoriedade) === 'opcional'    ? 'selected' : '' }}>🔵 Opcional</option>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label-sced">Status <span style="color:var(--vermelho)">*</span></label>
                <select name="status" class="form-control-sced" required>
                    <option value="ativo"   {{ old('status', $tipo->status) === 'ativo'   ? 'selected' : '' }}>● Ativo</option>
                    <option value="inativo" {{ old('status', $tipo->status) === 'inativo' ? 'selected' : '' }}>● Inativo</option>
                </select>
                @if($tipo->documentos_count > 0)
                    <div style="font-size:12px;color:var(--amarelo);margin-top:4px;">
                        ⚠️ Este tipo possui {{ $tipo->documentos_count }} documento(s) vinculado(s). Não é possível inativar.
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Seção 2: Destino e Responsável --}}
    <div class="card-sced mb-4">
        <div style="font-size:13px;font-weight:700;text-transform:uppercase;letter-spacing:1.2px;color:var(--cinza-400);margin-bottom:20px;padding-bottom:12px;border-bottom:1px solid var(--cinza-200);">
            🏢 Destino e Responsável
        </div>

        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label-sced">Setor de Destino</label>
                <select name="departamento_destino_id" class="form-control-sced">
                    <option value="">— Nenhum —</option>
                    @foreach($departamentos as $dep)
                        <option value="{{ $dep->id }}" {{ old('departamento_destino_id', $tipo->departamento_destino_id) == $dep->id ? 'selected' : '' }}>
                            {{ $dep->nome }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label-sced">Cargo Responsável</label>
                <select name="cargo_responsavel" class="form-control-sced">
                    <option value="">— Nenhum —</option>
                    <option value="N1" {{ old('cargo_responsavel', $tipo->cargo_responsavel) === 'N1' ? 'selected' : '' }}>N1 — Atendimento</option>
                    <option value="N2" {{ old('cargo_responsavel', $tipo->cargo_responsavel) === 'N2' ? 'selected' : '' }}>N2 — Analista</option>
                    <option value="N3" {{ old('cargo_responsavel', $tipo->cargo_responsavel) === 'N3' ? 'selected' : '' }}>N3 — Supervisor</option>
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label-sced">SLA — Prazo Máximo (horas)</label>
                <input type="number" name="sla_horas" class="form-control-sced"
                       min="1" max="8760" value="{{ old('sla_horas', $tipo->sla_horas) }}"
                       placeholder="Ex: 24">
            </div>
        </div>
    </div>

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
.form-label-sced { font-size:13px;font-weight:600;color:var(--cinza-600);margin-bottom:6px;display:block; }
.form-control-sced { width:100%;padding:10px 14px;border:1.5px solid var(--cinza-200);border-radius:var(--radius-sm);font-size:14px;font-family:'Sora',sans-serif;background:var(--branco);color:var(--cinza-800);transition:var(--transicao);outline:none; }
.form-control-sced:focus { border-color:var(--azul-claro);box-shadow:0 0 0 3px rgba(37,99,235,0.12); }
.invalid-feedback { color:var(--vermelho);font-size:12px;margin-top:4px; }
</style>
@endpush
