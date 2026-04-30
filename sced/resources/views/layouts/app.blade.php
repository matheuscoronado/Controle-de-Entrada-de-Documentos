{{-- ============================================================
     Arquivo: resources/views/layouts/app.blade.php
     Layout principal com sidebar — usado em TODAS as telas internas
     ============================================================ --}}
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SCED — @yield('title', 'Sistema')</title>
    <link rel="stylesheet" href="{{ asset('css/sced.css') }}">
    {{-- Bootstrap só para paginação e grid --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    @stack('styles')
</head>
<body>

{{-- ── SIDEBAR ── --}}
<aside class="sidebar" id="sidebar">

    {{-- Logo --}}
    <div class="sidebar-logo">
        <div class="logo-icon">📂</div>
        <div class="logo-title">SCED</div>
        <div class="logo-sub">Controle de Documentos</div>
    </div>

    {{-- Navegação --}}
    <nav class="sidebar-nav">

        <div class="nav-section-label">Principal</div>

        <a href="{{ route('dashboard') }}"
           class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <span class="nav-icon">🏠</span> Dashboard
        </a>

        <a href="{{ route('documentos.index') }}"
           class="sidebar-link {{ request()->routeIs('documentos.*') ? 'active' : '' }}">
            <span class="nav-icon">📄</span> Documentos
        </a>

        <a href="{{ route('documentos.create') }}"
           class="sidebar-link">
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

    {{-- Rodapé com usuário logado --}}
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

{{-- ── CONTEÚDO PRINCIPAL ── --}}
<div class="main-content">

    {{-- Topbar --}}
    <div class="topbar">
        <div>
            <div class="topbar-title">@yield('title', 'Dashboard')</div>
            <div class="topbar-sub">@yield('subtitle', '')</div>
        </div>
        <div style="display:flex; gap:8px; align-items:center;">
            {{-- Botão mobile para abrir sidebar --}}
            <button class="btn-secondary-sced d-lg-none" onclick="document.getElementById('sidebar').classList.toggle('open')">
                ☰
            </button>
            @yield('topbar-actions')
        </div>
    </div>

    {{-- Alertas globais --}}
    <div class="page-body" style="padding-bottom:0; padding-top:0;">
        @if(session('success'))
            <div class="alert-sced alert-success" style="margin-top:20px;">
                ✅ {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="alert-sced alert-error" style="margin-top:20px;">
                ❌ {{ session('error') }}
            </div>
        @endif
    </div>

    {{-- Conteúdo da página --}}
    <div class="page-body">
        @yield('content')
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
