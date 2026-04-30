{{-- ============================================================
     Arquivo: resources/views/usuarios/index.blade.php
     ============================================================ --}}
@extends('layouts.app')
@section('title', 'Usuários')
@section('subtitle', 'Gerenciamento de usuários do sistema')

@section('topbar-actions')
    <a href="{{ route('usuarios.create') }}" class="btn-primary-sced">
        ➕ Novo Usuário
    </a>
@endsection

@section('content')
<div class="card-sced">
    <div style="overflow-x:auto;">
        <table class="tabela-sced">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nome</th>
                    <th>E-mail</th>
                    <th>Perfil</th>
                    <th>Status</th>
                    <th>Cadastrado em</th>
                    <th style="text-align:center;">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse($usuarios as $usuario)
                <tr>
                    <td style="color:var(--cinza-400); font-size:13px;">{{ $usuario->id }}</td>
                    <td>
                        <div style="display:flex; align-items:center; gap:10px;">
                            <div style="width:32px;height:32px;border-radius:50%;background:var(--azul-claro);color:white;display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:700;">
                                {{ strtoupper(substr($usuario->nome, 0, 1)) }}
                            </div>
                            <span style="font-weight:500;">{{ $usuario->nome }}</span>
                        </div>
                    </td>
                    <td style="color:var(--cinza-600);">{{ $usuario->email }}</td>
                    <td>
                        @if($usuario->perfil === 'administrador')
                            <span style="background:#eff6ff;color:#2563eb;padding:3px 10px;border-radius:20px;font-size:12px;font-weight:600;">
                                👑 Administrador
                            </span>
                        @else
                            <span style="background:var(--cinza-100);color:var(--cinza-600);padding:3px 10px;border-radius:20px;font-size:12px;font-weight:600;">
                                👤 Operador
                            </span>
                        @endif
                    </td>
                    <td>
                        @if($usuario->status === 'ativo')
                            <span style="background:#f0fdf4;color:#059669;padding:3px 10px;border-radius:20px;font-size:12px;font-weight:600;">● Ativo</span>
                        @else
                            <span style="background:#fef2f2;color:#dc2626;padding:3px 10px;border-radius:20px;font-size:12px;font-weight:600;">● Inativo</span>
                        @endif
                    </td>
                    <td style="color:var(--cinza-400); font-size:13px;">
                        {{ $usuario->created_at->format('d/m/Y') }}
                    </td>
                    <td style="text-align:center;">
                        @if($usuario->id !== auth()->id())
                            <a href="{{ route('usuarios.edit', $usuario) }}" class="btn-outline-sced">
                                ✏️ Editar
                            </a>
                        @else
                            <span style="font-size:12px;color:var(--cinza-400);">— você mesmo —</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align:center;padding:48px;color:var(--cinza-400);">
                        Nenhum usuário cadastrado.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection


{{-- ============================================================
     Arquivo: resources/views/usuarios/create.blade.php
     ============================================================ --}}
{{-- SALVE COMO: resources/views/usuarios/create.blade.php --}}

{{-- @extends('layouts.app')
@section('title', 'Novo Usuário')

@section('topbar-actions')
    <a href="{{ route('usuarios.index') }}" class="btn-secondary-sced">← Voltar</a>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-lg-7">
        <div class="card-sced card-body-sced">
            <strong style="font-size:16px;color:var(--azul-escuro);display:block;margin-bottom:20px;">
                👤 Dados do Novo Usuário
            </strong>
            <form method="POST" action="{{ route('usuarios.store') }}">
                @csrf
                <div class="form-group">
                    <label class="form-label-sced">Nome completo *</label>
                    <input type="text" name="nome" class="form-input-sced {{ $errors->has('nome') ? 'is-invalid' : '' }}"
                           value="{{ old('nome') }}" placeholder="Nome completo" required>
                    @error('nome') <div class="form-error">{{ $message }}</div> @enderror
                </div>
                <div class="form-group">
                    <label class="form-label-sced">E-mail *</label>
                    <input type="email" name="email" class="form-input-sced {{ $errors->has('email') ? 'is-invalid' : '' }}"
                           value="{{ old('email') }}" placeholder="email@dominio.com" required>
                    @error('email') <div class="form-error">{{ $message }}</div> @enderror
                </div>
                <div class="row g-3">
                    <div class="col-6">
                        <div class="form-group">
                            <label class="form-label-sced">Senha *</label>
                            <input type="password" name="password" class="form-input-sced" placeholder="Mínimo 6 caracteres" required>
                            @error('password') <div class="form-error">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label class="form-label-sced">Confirmar Senha *</label>
                            <input type="password" name="password_confirmation" class="form-input-sced" placeholder="Repita a senha" required>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label-sced">Perfil *</label>
                    <select name="perfil" class="form-input-sced" required>
                        <option value="operador" {{ old('perfil')=='operador' ? 'selected' : '' }}>👤 Operador</option>
                        <option value="administrador" {{ old('perfil')=='administrador' ? 'selected' : '' }}>👑 Administrador</option>
                    </select>
                </div>
                <div style="display:flex;gap:12px;justify-content:flex-end;margin-top:8px;padding-top:20px;border-top:1px solid var(--cinza-200);">
                    <a href="{{ route('usuarios.index') }}" class="btn-secondary-sced">Cancelar</a>
                    <button type="submit" class="btn-primary-sced">💾 Cadastrar Usuário</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection --}}
