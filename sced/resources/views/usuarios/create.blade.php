{{-- ============================================================
     resources/views/usuarios/create.blade.php
     CRIAR USUÁRIO - COM PERMISSÃO PARA ASSUMIR PROCESSOS
     ============================================================ --}}
@extends('layouts.app')
@section('title', 'Novo Usuário')
@section('subtitle', 'Cadastre um novo usuário no sistema')

@section('topbar-actions')
    <a href="{{ route('usuarios.index') }}" class="btn-secondary-sced">← Voltar</a>
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
    .toggle-switch {
        position: relative;
        display: inline-block;
        width: 52px;
        height: 26px;
    }
    .toggle-switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }
    .toggle-slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: var(--cinza-300);
        transition: 0.3s;
        border-radius: 34px;
    }
    .toggle-slider:before {
        position: absolute;
        content: "";
        height: 20px;
        width: 20px;
        left: 3px;
        bottom: 3px;
        background-color: white;
        transition: 0.3s;
        border-radius: 50%;
    }
    input:checked + .toggle-slider {
        background-color: var(--azul-claro);
    }
    input:checked + .toggle-slider:before {
        transform: translateX(26px);
    }
    .helper-text {
        font-size: 11px;
        color: var(--cinza-400);
        margin-top: 4px;
    }
</style>

<div class="row justify-content-center">
    <div class="col-lg-8">

        <div class="form-card">
            <div class="form-section-title">
                👤 Dados do Novo Usuário
            </div>

            <form method="POST" action="{{ route('usuarios.store') }}">
                @csrf

                {{-- Nome --}}
                <div class="mb-3">
                    <label class="form-label-sced">Nome completo <span class="text-danger">*</span></label>
                    <input type="text" name="nome" class="form-input-sced @error('nome') is-invalid @enderror"
                           value="{{ old('nome') }}" placeholder="Nome completo do usuário" required>
                    @error('nome')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                {{-- E-mail --}}
                <div class="mb-3">
                    <label class="form-label-sced">E-mail <span class="text-danger">*</span></label>
                    <input type="email" name="email" class="form-input-sced @error('email') is-invalid @enderror"
                           value="{{ old('email') }}" placeholder="email@dominio.com" required>
                    @error('email')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                {{-- Senha --}}
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label-sced">Senha <span class="text-danger">*</span></label>
                        <div style="position: relative;">
                            <input type="password" name="password" id="senha" class="form-input-sced @error('password') is-invalid @enderror"
                                   placeholder="Mínimo 6 caracteres" required>
                            <button type="button" onclick="toggleSenha('senha', this)" 
                                    style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer;">
                                👁️
                            </button>
                        </div>
                        @error('password')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-sced">Confirmar Senha <span class="text-danger">*</span></label>
                        <div style="position: relative;">
                            <input type="password" name="password_confirmation" id="senha_confirma" class="form-input-sced" placeholder="Repita a senha" required>
                            <button type="button" onclick="toggleSenha('senha_confirma', this)" 
                                    style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer;">
                                👁️
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Perfil --}}
                <div class="mb-3">
                    <label class="form-label-sced">Perfil de acesso <span class="text-danger">*</span></label>
                    <select name="perfil" class="form-input-sced" required>
                        <option value="operador" {{ old('perfil') == 'operador' ? 'selected' : '' }}>👤 Operador</option>
                        <option value="n3" {{ old('perfil') == 'n3' ? 'selected' : '' }}>⭐ Supervisor N3</option>
                        <option value="administrador" {{ old('perfil') == 'administrador' ? 'selected' : '' }}>👑 Administrador</option>
                    </select>
                    @error('perfil')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                {{-- Departamento e Cargo --}}
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label-sced">Departamento <span class="text-danger">*</span></label>
                        <select name="departamento_id" class="form-input-sced" required>
                            <option value="">Selecione o departamento</option>
                            @foreach($departamentos as $depto)
                                <option value="{{ $depto->id }}" {{ old('departamento_id') == $depto->id ? 'selected' : '' }}>
                                    🏢 {{ $depto->nome }}
                                </option>
                            @endforeach
                        </select>
                        @error('departamento_id')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-sced">Cargo <span class="text-danger">*</span></label>
                        <select name="cargo" class="form-input-sced" required>
                            <option value="">Selecione o cargo</option>
                            <option value="N1" {{ old('cargo') == 'N1' ? 'selected' : '' }}>🎯 N1 - Atendimento</option>
                            <option value="N2" {{ old('cargo') == 'N2' ? 'selected' : '' }}>📊 N2 - Analista</option>
                            <option value="N3" {{ old('cargo') == 'N3' ? 'selected' : '' }}>⭐ N3 - Supervisor</option>
                        </select>
                        @error('cargo')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                </div>

                {{-- Permissão para assumir processos --}}
                <div class="mb-4 p-4" style="background: var(--cinza-100); border-radius: 12px;">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div style="font-weight: 600;">🎯 Pode Assumir Processos</div>
                            <div class="helper-text" style="margin-top: 4px;">
                                Permite que este usuário assuma processos do seu setor.
                                Administradores e N3 sempre podem assumir independentemente desta configuração.
                            </div>
                        </div>
                        <label class="toggle-switch">
                            <input type="checkbox" name="pode_assumir" value="1" {{ old('pode_assumir') ? 'checked' : '' }}>
                            <span class="toggle-slider"></span>
                        </label>
                    </div>
                </div>

                {{-- Botões --}}
                <div class="d-flex justify-content-end gap-3 mt-4 pt-3 border-top">
                    <a href="{{ route('usuarios.index') }}" class="btn-secondary-sced">Cancelar</a>
                    <button type="submit" class="btn-primary-sced">💾 Cadastrar Usuário</button>
                </div>

            </form>
        </div>

    </div>
</div>

<script>
    function toggleSenha(inputId, btn) {
        const input = document.getElementById(inputId);
        const type = input.type === 'password' ? 'text' : 'password';
        input.type = type;
        btn.innerHTML = type === 'text' ? '🙈' : '👁️';
    }
</script>

@endsection