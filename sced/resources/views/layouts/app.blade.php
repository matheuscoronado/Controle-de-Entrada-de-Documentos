{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SCED — @yield('title', 'Sistema')</title>
    <link rel="stylesheet" href="{{ asset('css/sced.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    @stack('styles')
</head>
<body>

<aside class="sidebar" id="sidebar">

    <div class="sidebar-logo">
        <div class="logo-icon">📂</div>
        <div class="logo-title">SCED</div>
        <div class="logo-sub">Controle de Documentos</div>
    </div>

    <nav class="sidebar-nav">

        <div class="nav-section-label">Principal</div>

        <a href="{{ route('dashboard') }}"
           class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <span class="nav-icon">🏠</span> Dashboard
        </a>

        {{-- FIX 1: Documentos só fica ativo em index e show, NÃO em create --}}
        <a href="{{ route('documentos.index') }}"
           class="sidebar-link {{ request()->routeIs('documentos.index') || request()->routeIs('documentos.show') ? 'active' : '' }}">
            <span class="nav-icon">📄</span> Documentos
        </a>

        {{-- FIX 1: Novo Documento fica ativo apenas em create --}}
        <a href="{{ route('documentos.create') }}"
           class="sidebar-link {{ request()->routeIs('documentos.create') ? 'active' : '' }}">
            <span class="nav-icon">➕</span> Novo Documento
        </a>

        <a href="{{ route('tipos.index') }}"
           class="sidebar-link {{ request()->routeIs('tipos.*') ? 'active' : '' }}">
            <span class="nav-icon">🏷️</span> Tipos de Doc.
        </a>

        @if(auth()->user()->isAdmin())
        <div class="nav-section-label">Administração</div>

        <a href="{{ route('usuarios.index') }}"
           class="sidebar-link {{ request()->routeIs('usuarios.*') ? 'active' : '' }}">
            <span class="nav-icon">👥</span> Usuários
        </a>

        <a href="{{ route('relatorios.index') }}"
           class="sidebar-link {{ request()->routeIs('relatorios.*') ? 'active' : '' }}">
            <span class="nav-icon">📊</span> Relatórios
        </a>
        @endif

    </nav>

    <div class="sidebar-footer">
        <div class="user-info">
            <div class="user-avatar">
                {{ strtoupper(substr(auth()->user()->nome, 0, 1)) }}
            </div>
            <div>
                <div class="user-name">{{ auth()->user()->nome }}</div>
                <div class="user-role">
                    {{ auth()->user()->perfil === 'administrador' ? 'Administrador' : 'Operador' }}
                </div>
            </div>
        </div>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn-sair">
                🚪 Sair do sistema
            </button>
        </form>
    </div>
</aside>

<div class="main-content">

    {{-- FIX 6: Topbar sticky fixo no topo --}}
    <div class="topbar">
        <div>
            <div class="topbar-title">@yield('title', 'Dashboard')</div>
            <div class="topbar-sub">@yield('subtitle', '')</div>
        </div>
        <div style="display:flex; gap:8px; align-items:center;">
            <button class="btn-secondary-sced d-lg-none" onclick="document.getElementById('sidebar').classList.toggle('open')">
                ☰
            </button>
            @yield('topbar-actions')
        </div>
    </div>

    {{-- Alertas globais --}}
    @if(session('success'))
        <div style="padding: 0 32px; padding-top: 16px;">
            <div class="alert-sced alert-success">
                ✅ {{ session('success') }}
            </div>
        </div>
    @endif
    @if(session('error'))
        <div style="padding: 0 32px; padding-top: 16px;">
            <div class="alert-sced alert-error">
                ❌ {{ session('error') }}
            </div>
        </div>
    @endif

    {{-- FIX 6: Conteúdo com scroll independente --}}
    <div class="page-body">
        @yield('content')
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

{{-- FIX 5: Função global para toggle de senha --}}
<script>
function toggleSenha(inputId, btn) {
    const input = document.getElementById(inputId);
    const isPassword = input.type === 'password';
    input.type = isPassword ? 'text' : 'password';
    btn.innerHTML = isPassword
        ? '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19m-6.72-1.07a3 3 0 11-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>'
        : '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>';
}
</script>

@stack('scripts')
</body>
</html>
