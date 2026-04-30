{{-- ============================================================
     Arquivo: resources/views/usuarios/create.blade.php
     ============================================================ --}}
@extends('layouts.app')
@section('title', 'Novo Usuário')
@section('subtitle', 'Cadastre um novo usuário no sistema')

@section('topbar-actions')
    <a href="{{ route('usuarios.index') }}" class="btn-secondary-sced">← Voltar</a>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-lg-7">
        <div class="card-sced card-body-sced">
            <strong style="font-size:16px; color:var(--azul-escuro); display:block; margin-bottom:24px;">
                👤 Dados do Novo Usuário
            </strong>
            <form method="POST" action="{{ route('usuarios.store') }}">
                @csrf

                <div class="form-group">
                    <label class="form-label-sced">Nome completo *</label>
                    <input type="text" name="nome"
                           class="form-input-sced {{ $errors->has('nome') ? 'is-invalid' : '' }}"
                           value="{{ old('nome') }}"
                           placeholder="Nome completo do usuário"
                           required>
                    @error('nome') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label-sced">E-mail *</label>
                    <input type="email" name="email"
                           class="form-input-sced {{ $errors->has('email') ? 'is-invalid' : '' }}"
                           value="{{ old('email') }}"
                           placeholder="email@dominio.com"
                           required>
                    @error('email') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="row g-3">
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label class="form-label-sced">Senha *</label>
                            <input type="password" name="password"
                                   class="form-input-sced {{ $errors->has('password') ? 'is-invalid' : '' }}"
                                   placeholder="Mínimo 6 caracteres"
                                   required>
                            @error('password') <div class="form-error">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label class="form-label-sced">Confirmar Senha *</label>
                            <input type="password" name="password_confirmation"
                                   class="form-input-sced"
                                   placeholder="Repita a senha"
                                   required>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label-sced">Perfil de acesso *</label>
                    <select name="perfil" class="form-input-sced" required>
                        <option value="operador"       {{ old('perfil')=='operador'       ? 'selected' : '' }}>👤 Operador — Registra e consulta documentos</option>
                        <option value="administrador"  {{ old('perfil')=='administrador'  ? 'selected' : '' }}>👑 Administrador — Acesso completo ao sistema</option>
                    </select>
                    @error('perfil') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div style="display:flex; gap:12px; justify-content:flex-end; margin-top:8px; padding-top:20px; border-top:1px solid var(--cinza-200);">
                    <a href="{{ route('usuarios.index') }}" class="btn-secondary-sced">Cancelar</a>
                    <button type="submit" class="btn-primary-sced">💾 Cadastrar Usuário</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
