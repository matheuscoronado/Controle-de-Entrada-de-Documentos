{{-- ============================================================
     Arquivo: resources/views/auth/login.blade.php
     SUBSTITUI o arquivo de login padrão do Breeze
     ============================================================ --}}
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SCED — Login</title>
    <link rel="stylesheet" href="{{ asset('css/sced.css') }}">
</head>
<body>

<div class="login-page">
    <div class="login-card">

        {{-- Logo --}}
        <div class="login-logo">
            <div class="logo-box">📂</div>
            <h1>SCED</h1>
            <p>Sistema de Controle de Entrada de Documentos</p>
        </div>

        {{-- Erros de validação --}}
        @if($errors->any())
            <div class="alert-sced alert-error" style="margin-bottom: 20px;">
                ⚠️ {{ $errors->first() }}
            </div>
        @endif

        {{-- Mensagem de sessão --}}
        @if(session('status'))
            <div class="alert-sced alert-success">
                ✅ {{ session('status') }}
            </div>
        @endif

        {{-- Formulário de login --}}
        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="form-group">
                <label class="form-label-sced">E-mail</label>
                <input
                    type="email"
                    name="email"
                    class="form-input-sced {{ $errors->has('email') ? 'is-invalid' : '' }}"
                    value="{{ old('email') }}"
                    placeholder="seu@email.com"
                    autofocus
                    required
                >
            </div>

            <div class="form-group">
                <label class="form-label-sced">Senha</label>
                <input
                    type="password"
                    name="password"
                    class="form-input-sced {{ $errors->has('password') ? 'is-invalid' : '' }}"
                    placeholder="••••••••"
                    required
                >
            </div>

            <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:24px;">
                <label style="display:flex; align-items:center; gap:8px; font-size:13px; color:var(--cinza-600); cursor:pointer;">
                    <input type="checkbox" name="remember"> Manter conectado
                </label>
            </div>

            <button type="submit" class="btn-login">Entrar no sistema</button>
        </form>

        <p style="text-align:center; margin-top:24px; font-size:12px; color:var(--cinza-400);">
            SCED © {{ date('Y') }} — Acesso restrito
        </p>
    </div>
</div>

</body>
</html>
