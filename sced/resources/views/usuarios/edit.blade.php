{{-- ============================================================
     Arquivo: resources/views/usuarios/edit.blade.php
     ============================================================ --}}
@extends('layouts.app')
@section('title', 'Editar Usuário')
@section('subtitle', $usuario->nome)

@section('topbar-actions')
    <a href="{{ route('usuarios.index') }}" class="btn-secondary-sced">← Voltar</a>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-lg-7">
        <div class="card-sced card-body-sced">
            <strong style="font-size:16px; color:var(--azul-escuro); display:block; margin-bottom:24px;">
                ✏️ Editar Dados do Usuário
            </strong>
            <form method="POST" action="{{ route('usuarios.update', $usuario) }}">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label class="form-label-sced">Nome completo *</label>
                    <input type="text" name="nome"
                           class="form-input-sced {{ $errors->has('nome') ? 'is-invalid' : '' }}"
                           value="{{ old('nome', $usuario->nome) }}"
                           required>
                    @error('nome') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label-sced">E-mail</label>
                    <input type="email" class="form-input-sced"
                           value="{{ $usuario->email }}" disabled
                           style="opacity:0.6; cursor:not-allowed;">
                    <div style="font-size:11px; color:var(--cinza-400); margin-top:4px;">
                        O e-mail não pode ser alterado.
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label class="form-label-sced">Perfil de acesso *</label>
                            <select name="perfil" class="form-input-sced" required>
                                <option value="operador"      {{ old('perfil', $usuario->perfil)=='operador'      ? 'selected' : '' }}>👤 Operador</option>
                                <option value="administrador" {{ old('perfil', $usuario->perfil)=='administrador' ? 'selected' : '' }}>👑 Administrador</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label class="form-label-sced">Status *</label>
                            <select name="status" class="form-input-sced" required>
                                <option value="ativo"   {{ old('status', $usuario->status)=='ativo'   ? 'selected' : '' }}>● Ativo</option>
                                <option value="inativo" {{ old('status', $usuario->status)=='inativo' ? 'selected' : '' }}>● Inativo</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div style="display:flex; gap:12px; justify-content:flex-end; margin-top:8px; padding-top:20px; border-top:1px solid var(--cinza-200);">
                    <a href="{{ route('usuarios.index') }}" class="btn-secondary-sced">Cancelar</a>
                    <button type="submit" class="btn-primary-sced">💾 Salvar Alterações</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
