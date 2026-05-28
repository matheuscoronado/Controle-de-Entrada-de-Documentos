{{--
    ============================================================
    ADIÇÃO AO layouts/app.blade.php — Parte 1
    Inclua este bloco dentro do <nav class="sidebar-nav">,
    dentro da seção @if(auth()->user()->isAdmin())
    ============================================================
--}}

@if(auth()->user()->isAdmin())
<div class="nav-section-label">Administração</div>

<a href="{{ route('usuarios.index') }}"
   class="sidebar-link {{ request()->routeIs('usuarios.*') ? 'active' : '' }}">
    <span class="nav-icon">👥</span> Usuários
</a>

{{-- ADICIONADO: Tipos de Documento (com nova parametrização) --}}
<a href="{{ route('tipos.index') }}"
   class="sidebar-link {{ request()->routeIs('tipos.*') ? 'active' : '' }}">
    <span class="nav-icon">🏷️</span> Tipos de Doc.
</a>

<a href="{{ route('departamentos.index') }}"
   class="sidebar-link {{ request()->routeIs('departamentos.*') ? 'active' : '' }}">
    <span class="nav-icon">🏢</span> Departamentos
</a>

{{-- ADICIONADO: Logs de Auditoria (novo — Parte 1) --}}
<a href="{{ route('logs.index') }}"
   class="sidebar-link {{ request()->routeIs('logs.*') ? 'active' : '' }}">
    <span class="nav-icon">📋</span> Logs / Auditoria
</a>

<a href="{{ route('relatorios.index') }}"
   class="sidebar-link {{ request()->routeIs('relatorios.*') ? 'active' : '' }}">
    <span class="nav-icon">📊</span> Relatórios
</a>

@endif

{{-- ADICIONADO: Supervisor N3 vê um painel próprio (sem acesso admin completo) --}}
@if(auth()->user()->isN3() && !auth()->user()->isAdmin())
<div class="nav-section-label">Supervisão</div>

<a href="#" class="sidebar-link">
    <span class="nav-icon">📊</span> Painel N3
</a>
{{-- Expandido na Parte 2 --}}
@endif
