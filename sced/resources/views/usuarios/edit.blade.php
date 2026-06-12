{{-- resources/views/usuarios/edit.blade.php --}}
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
                    @error('nome')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
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

                <div class="row g-3" style="margin-bottom: 4px;">
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label class="form-label-sced">Nova senha</label>
                            <div style="position:relative;">
                                <input type="password" name="password" id="edit_senha"
                                       class="form-input-sced {{ $errors->has('password') ? 'is-invalid' : '' }}"
                                       placeholder="Deixe em branco para não alterar"
                                       style="padding-right:44px;">
                                <button type="button"
                                    onclick="toggleSenha('edit_senha', this)"
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
                            <label class="form-label-sced">Confirmar nova senha</label>
                            <div style="position:relative;">
                                <input type="password" name="password_confirmation" id="edit_confirma"
                                       class="form-input-sced"
                                       placeholder="Repita a nova senha"
                                       style="padding-right:44px;">
                                <button type="button"
                                    onclick="toggleSenha('edit_confirma', this)"
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

                {{-- BLOCO CORRIGIDO: Departamento e Cargo --}}
                <div class="row g-3">
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label class="form-label-sced">Departamento *</label>
                            {{-- Alterado o name para departamento_id --}}
                            <select name="departamento_id" class="form-input-sced" required>
                                <option value="">Selecione um departamento</option>
                                @foreach($departamentos as $depto)
                                    {{-- O value agora é o ID numérico e a comparação usa departamento_id --}}
                                    <option value="{{ $depto->id }}" 
                                        {{ old('departamento_id', $usuario->departamento_id) == $depto->id ? 'selected' : '' }}>
                                        🏢 {{ $depto->nome }}
                                    </option>
                                @endforeach
                            </select>
                            @error('departamento_id')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label class="form-label-sced">Cargo *</label>
                            <select name="cargo" class="form-input-sced" required>
                                <option value="N1" {{ old('cargo', $usuario->cargo) == 'N1' ? 'selected' : '' }}>Nivel 1 (N1)</option>
                                <option value="N2" {{ old('cargo', $usuario->cargo) == 'N2' ? 'selected' : '' }}>Nivel 2 (N2)</option>
                                <option value="N3" {{ old('cargo', $usuario->cargo) == 'N3' ? 'selected' : '' }}>Nivel 3 (N3)</option>
                            </select>
                            @error('cargo')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Permissão de Assumir Processos --}}
                <div class="form-group" style="padding: 16px; background: var(--cinza-50, #f8fafc); border-radius: var(--radius-sm); border: 1.5px solid var(--cinza-200);">
                    <div style="display:flex; align-items:center; justify-content:space-between; gap:12px;">
                        <div>
                            <div style="font-size:14px; font-weight:600; color:var(--cinza-800);">🎯 Pode Assumir Processos</div>
                            <div style="font-size:12px; color:var(--cinza-400); margin-top:3px;">
                                Permite que este usuário assuma processos do seu setor. Administradores e N3 sempre podem assumir independentemente desta configuração.
                            </div>
                        </div>
                        <label style="position:relative; display:inline-block; width:44px; height:24px; flex-shrink:0;">
                            <input type="checkbox" name="pode_assumir" value="1"
                                   id="pode_assumir_edit"
                                   {{ old('pode_assumir', $usuario->pode_assumir) ? 'checked' : '' }}
                                   style="opacity:0; width:0; height:0;">
                            <span class="toggle-track" onclick="document.getElementById('pode_assumir_edit').click()"></span>
                        </label>
                    </div>
                </div>

                <div style="display:flex; gap:12px; justify-content:flex-end; padding-top:20px; border-top:1px solid var(--cinza-200); margin-top: 20px;">
                    <a href="{{ route('usuarios.index') }}" class="btn-secondary-sced">Cancelar</a>
                    <button type="submit" class="btn-primary-sced">💾 Salvar Alterações</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.toggle-track {
    position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0;
    background: var(--cinza-200, #e2e8f0); border-radius: 24px; transition: .2s;
}
.toggle-track:before {
    position: absolute; content: ""; height: 18px; width: 18px; left: 3px; bottom: 3px;
    background: white; border-radius: 50%; transition: .2s;
}
input:checked + .toggle-track { background: var(--azul-claro, #2563eb); }
input:checked + .toggle-track:before { transform: translateX(20px); }
</style>
<script>
    function toggleSenha(id, btn) {
        const input = document.getElementById(id);
        const type = input.type === 'password' ? 'text' : 'password';
        input.type = type;
        btn.style.color = type === 'text' ? 'var(--azul-primario)' : '#94a3b8';
    }
</script>
@endsection