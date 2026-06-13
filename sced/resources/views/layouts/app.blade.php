{{-- ============================================================
     resources/views/layouts/app.blade.php
     MENU LATERAL - Serviços movido para Administração
     ============================================================ --}}
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SCED Help Desk — @yield('title', 'Sistema')</title>
    <link rel="stylesheet" href="{{ asset('css/sced.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    @stack('styles')
</head>
<body>

{{-- ── SIDEBAR ─────────────────────────────────────────────── --}}
<aside class="sidebar" id="sidebar">

    <div class="sidebar-logo">
        <div class="logo-icon">🎯</div>
        <div class="logo-title">SCED</div>
        <div class="logo-sub">Help Desk & Processos</div>
    </div>

    <nav class="sidebar-nav">

        <div class="nav-section-label">Principal</div>

        <a href="{{ route('dashboard') }}"
           class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <span class="nav-icon">🏠</span> Dashboard
        </a>

        <a href="{{ route('documentos.index') }}"
           class="sidebar-link {{ request()->routeIs('documentos.index','documentos.show') ? 'active' : '' }}">
            <span class="nav-icon">📂</span> Processos
            @php $pendentes = \App\Models\Documento::where('status','novo')->whereNull('atribuido_a_id')->count(); @endphp
            @if($pendentes > 0)
                <span class="nav-badge">{{ $pendentes }}</span>
            @endif
        </a>

        <a href="{{ route('documentos.create') }}"
           class="sidebar-link {{ request()->routeIs('documentos.create') ? 'active' : '' }}">
            <span class="nav-icon">➕</span> Novo Processo
        </a>

        {{-- ADMIN ──────────────────────────────────────────── --}}
        @if(auth()->user()->isAdmin())
        <div class="nav-section-label">Administração</div>

        {{-- ⭐ SERVIÇOS movido para ADMINISTRAÇÃO ⭐ --}}
        <a href="{{ route('tipos.index') }}"
           class="sidebar-link {{ request()->routeIs('tipos.*') ? 'active' : '' }}">
            <span class="nav-icon">🏷️</span> Serviços
        </a>

        <a href="{{ route('documentos-tipo.index') }}"
           class="sidebar-link {{ request()->routeIs('documentos-tipo.*') ? 'active' : '' }}">
            <span class="nav-icon">📄</span> Cadastro de Documentos
        </a>

        <a href="{{ route('usuarios.index') }}"
           class="sidebar-link {{ request()->routeIs('usuarios.*') ? 'active' : '' }}">
            <span class="nav-icon">👥</span> Usuários
        </a>

        <a href="{{ route('departamentos.index') }}"
           class="sidebar-link {{ request()->routeIs('departamentos.*') ? 'active' : '' }}">
            <span class="nav-icon">🏢</span> Departamentos
        </a>

        <a href="{{ route('logs.index') }}"
           class="sidebar-link {{ request()->routeIs('logs.*') ? 'active' : '' }}">
            <span class="nav-icon">📋</span> Logs / Auditoria
        </a>

        <a href="{{ route('relatorios.index') }}"
           class="sidebar-link {{ request()->routeIs('relatorios.*') ? 'active' : '' }}">
            <span class="nav-icon">📊</span> Relatórios
        </a>
        @endif

        {{-- N3 (supervisor) ─────────────────────────────── --}}
        @if(auth()->user()->isN3() && !auth()->user()->isAdmin())
        <div class="nav-section-label">Supervisão</div>

        <a href="{{ route('logs.index') }}"
           class="sidebar-link {{ request()->routeIs('logs.*') ? 'active' : '' }}">
            <span class="nav-icon">📋</span> Logs / Auditoria
        </a>
        @endif

    </nav>

    <div class="sidebar-footer">
        <div class="user-info">
            <div class="user-avatar">
                {{ strtoupper(substr(auth()->user()->nome, 0, 1)) }}
            </div>
            <div style="min-width:0;">
                <div class="user-name" style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                    {{ auth()->user()->nome }}
                </div>
                <div class="user-role">{{ auth()->user()->label_perfil }}</div>
            </div>
        </div>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn-sair">🚪 Sair do sistema</button>
        </form>
    </div>
</aside>

{{-- ── CONTEÚDO ─────────────────────────────────────────────── --}}
<div class="main-content">

    <div class="topbar">
        <div>
            <div class="topbar-title">@yield('title', 'Dashboard')</div>
            <div class="topbar-sub">@yield('subtitle', '')</div>
        </div>
        <div class="topbar-actions">
            <button class="btn-secondary-sced d-lg-none"
                    onclick="document.getElementById('sidebar').classList.toggle('open')"
                    style="padding:8px 12px;">☰</button>
            @yield('topbar-actions')
        </div>
    </div>

    <div class="page-body">
        @if(session('success'))
            <div class="alert-sced alert-success">✅ {{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert-sced alert-error">❌ {{ session('error') }}</div>
        @endif
        @if(session('warning'))
            <div class="alert-sced alert-warning">⚠️ {{ session('warning') }}</div>
        @endif

        @yield('content')
    </div>
</div>

<script>
document.addEventListener('click', function(e) {
    const sidebar = document.getElementById('sidebar');
    if (window.innerWidth < 992 && sidebar.classList.contains('open') &&
        !sidebar.contains(e.target)) {
        sidebar.classList.remove('open');
    }
});

function toggleSenha(inputId, btn) {
    const input = document.getElementById(inputId);
    const ok = input.type === 'password';
    input.type = ok ? 'text' : 'password';
    btn.innerHTML = ok
        ? '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19m-6.72-1.07a3 3 0 11-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>'
        : '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>';
}
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>