{{-- ============================================================
     resources/views/usuarios/edit.blade.php
     EDITAR USUÁRIO - COM CORREÇÃO DE SENHA
     ============================================================ --}}
@extends('layouts.app')
@section('title', 'Editar Usuário')
@section('subtitle', $usuario->nome)

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
    .alert-info {
        background: #eff6ff;
        color: #1e40af;
        border: 1px solid #bfdbfe;
        padding: 12px 16px;
        border-radius: 10px;
        font-size: 12px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
</style>

<div class="row justify-content-center">
    <div class="col-lg-8">

        <div class="alert-info">
            🔒 <strong>Informação:</strong> Deixe os campos de senha em branco para manter a senha atual do usuário.
        </div>

        <div class="form-card">
            <div class="form-section-title">
                ✏️ Editar Dados do Usuário
            </div>

            <form method="POST" action="{{ route('usuarios.update', $usuario) }}">
                @csrf
                @method('PUT')

                {{-- Nome --}}
                <div class="mb-3">
                    <label class="form-label-sced">Nome completo <span class="text-danger">*</span></label>
                    <input type="text" name="nome" class="form-input-sced @error('nome') is-invalid @enderror"
                           value="{{ old('nome', $usuario->nome) }}" required>
                    @error('nome')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                {{-- E-mail --}}
                <div class="mb-3">
                    <label class="form-label-sced">E-mail <span class="text-danger">*</span></label>
                    <input type="email" name="email" class="form-input-sced @error('email') is-invalid @enderror"
                           value="{{ old('email', $usuario->email) }}" required>
                    <div class="helper-text">O e-mail será usado para login no sistema.</div>
                    @error('email')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                {{-- Senha (opcional) --}}
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label-sced">Nova senha <span class="text-muted">(opcional)</span></label>
                        <div style="position: relative;">
                            <input type="password" name="password" id="senha" class="form-input-sced"
                                   placeholder="Deixe em branco para não alterar">
                            <button type="button" onclick="toggleSenha('senha', this)" 
                                    style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: var(--cinza-600);">
                                👁️
                            </button>
                        </div>
                        <div class="helper-text">Mínimo de 6 caracteres. Preencha apenas se quiser alterar.</div>
                        @error('password')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-sced">Confirmar nova senha</label>
                        <div style="position: relative;">
                            <input type="password" name="password_confirmation" id="senha_confirma" class="form-input-sced" placeholder="Repita a nova senha">
                            <button type="button" onclick="toggleSenha('senha_confirma', this)" 
                                    style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: var(--cinza-600);">
                                👁️
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Perfil e Status --}}
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label-sced">Perfil de acesso <span class="text-danger">*</span></label>
                        <select name="perfil" class="form-input-sced" required>
                            <option value="operador" {{ old('perfil', $usuario->perfil) == 'operador' ? 'selected' : '' }}>👤 Operador</option>
                            <option value="administrador" {{ old('perfil', $usuario->perfil) == 'administrador' ? 'selected' : '' }}>👑 Administrador</option>
                        </select>
                        <div class="helper-text">Define as permissões gerais do usuário no sistema.</div>
                        @error('perfil')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-sced">Status <span class="text-danger">*</span></label>
                        <select name="status" class="form-input-sced" required>
                            <option value="ativo" {{ old('status', $usuario->status) == 'ativo' ? 'selected' : '' }}>🟢 Ativo</option>
                            <option value="inativo" {{ old('status', $usuario->status) == 'inativo' ? 'selected' : '' }}>🔴 Inativo</option>
                        </select>
                        <div class="helper-text">Usuários inativos não conseguem acessar o sistema.</div>
                        @error('status')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                </div>

                {{-- Departamento e Cargo --}}
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label-sced">Departamento <span class="text-danger">*</span></label>
                        <select name="departamento_id" class="form-input-sced" required>
                            <option value="">Selecione o departamento</option>
                            @foreach($departamentos as $depto)
                                <option value="{{ $depto->id }}" {{ old('departamento_id', $usuario->departamento_id) == $depto->id ? 'selected' : '' }}>
                                    {{ $depto->nome }}
                                </option>
                            @endforeach
                        </select>
                        @error('departamento_id')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-sced">Cargo <span class="text-danger">*</span></label>
                        <select name="cargo" class="form-input-sced" required>
                            <option value="N1" {{ old('cargo', $usuario->cargo) == 'N1' ? 'selected' : '' }}>N1 - Atendimento</option>
                            <option value="N2" {{ old('cargo', $usuario->cargo) == 'N2' ? 'selected' : '' }}>N2 - Analista</option>
                            <option value="N3" {{ old('cargo', $usuario->cargo) == 'N3' ? 'selected' : '' }}>N3 - Supervisor</option>
                        </select>
                        <div class="helper-text">Define o nível hierárquico para fluxo de processos.</div>
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
                                Administradores sempre podem assumir independentemente desta configuração.
                            </div>
                        </div>
                        <label class="toggle-switch">
                            <input type="checkbox" name="pode_assumir" value="1" {{ old('pode_assumir', $usuario->pode_assumir) ? 'checked' : '' }}>
                            <span class="toggle-slider"></span>
                        </label>
                    </div>
                </div>

                {{-- Botões --}}
                <div class="d-flex justify-content-between gap-3 mt-4 pt-3 border-top">
                    <a href="{{ route('usuarios.index') }}" class="btn-secondary-sced">Cancelar</a>
                    <button type="submit" class="btn-primary-sced">💾 Salvar Alterações</button>
                </div>

            </form>
        </div>

    </div>
</div>

<script>
    function toggleSenha(inputId, btn) {
        const input = document.getElementById(inputId);
        if (!input) return;
        
        const type = input.type === 'password' ? 'text' : 'password';
        input.type = type;
        btn.innerHTML = type === 'text' ? '🙈' : '👁️';
    }
</script>

@endsection