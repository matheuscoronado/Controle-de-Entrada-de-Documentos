{{-- resources/views/usuarios/create.blade.php --}}
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
                    @error('nome')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label-sced">E-mail *</label>
                    <input type="email" name="email"
                           class="form-input-sced {{ $errors->has('email') ? 'is-invalid' : '' }}"
                           value="{{ old('email') }}"
                           placeholder="email@dominio.com"
                           required>
                    @error('email')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row g-3">
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label class="form-label-sced">Senha *</label>
                            <div style="position:relative;">
                                <input type="password" name="password" id="senha_nova"
                                       class="form-input-sced {{ $errors->has('password') ? 'is-invalid' : '' }}"
                                       placeholder="Mínimo 6 caracteres"
                                       style="padding-right:44px;"
                                       required>
                                <button type="button"
                                    onclick="toggleSenha('senha_nova', this)"
                                    style="position:absolute;right:12px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:#94a3b8;display:flex;align-items:center;padding:0;"
                                    title="Mostrar/ocultar senha">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                        <circle cx="12" cy="12" r="3"/>
                                    </svg>
                                </button>
                            </div>
                            @error('password')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label class="form-label-sced">Confirmar Senha *</label>
                            <div style="position:relative;">
                                <input type="password" name="password_confirmation" id="senha_confirma"
                                       class="form-input-sced"
                                       placeholder="Repita a senha"
                                       style="padding-right:44px;"
                                       required>
                                <button type="button"
                                    onclick="toggleSenha('senha_confirma', this)"
                                    style="position:absolute;right:12px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:#94a3b8;display:flex;align-items:center;padding:0;"
                                    title="Mostrar/ocultar senha">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                        <circle cx="12" cy="12" r="3"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label-sced">Perfil de acesso *</label>
                    <select name="perfil" class="form-input-sced" required>
                        <option value="operador"      {{ old('perfil')=='operador'      ? 'selected' : '' }}>
                            👤 Operador — Registra e consulta documentos
                        </option>
                        <option value="administrador" {{ old('perfil')=='administrador' ? 'selected' : '' }}>
                            👑 Administrador — Acesso completo ao sistema
                        </option>
                    </select>
                    @error('perfil')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                {{-- NOVOS CAMPOS ADICIONADOS AQUI --}}
                <div class="row g-3">
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label class="form-label-sced">Departamento *</label>
                            <select name="departamento" class="form-input-sced" required>
                                <option value="" disabled {{ old('departamento') ? '' : 'selected' }}>Selecione o departamento</option>
                                <option value="RH" {{ old('departamento') == 'RH' ? 'selected' : '' }}>🏢 RH</option>
                                <option value="COMERCIAL" {{ old('departamento') == 'COMERCIAL' ? 'selected' : '' }}>💼 Comercial</option>
                                <option value="SUPORTE" {{ old('departamento') == 'SUPORTE' ? 'selected' : '' }}>🛠️ Suporte</option>
                            </select>
                            @error('departamento')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label class="form-label-sced">Cargo *</label>
                            <select name="cargo" class="form-input-sced" required>
                                <option value="" disabled {{ old('cargo') ? '' : 'selected' }}>Selecione o nível</option>
                                <option value="N1" {{ old('cargo') == 'N1' ? 'selected' : '' }}>Nivel 1 (N1)</option>
                                <option value="N2" {{ old('cargo') == 'N2' ? 'selected' : '' }}>Nivel 2 (N2)</option>
                                <option value="N3" {{ old('cargo') == 'N3' ? 'selected' : '' }}>Nivel 3 (N3)</option>
                            </select>
                            @error('cargo')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div style="display:flex; gap:12px; justify-content:flex-end; margin-top:8px; padding-top:20px; border-top:1px solid var(--cinza-200);">
                    <a href="{{ route('usuarios.index') }}" class="btn-secondary-sced">Cancelar</a>
                    <button type="submit" class="btn-primary-sced">💾 Cadastrar Usuário</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function toggleSenha(id, btn) {
        const input = document.getElementById(id);
        const type = input.type === 'password' ? 'text' : 'password';
        input.type = type;
        btn.style.color = type === 'text' ? 'var(--azul-primario)' : '#94a3b8';
    }
</script>
@endsection