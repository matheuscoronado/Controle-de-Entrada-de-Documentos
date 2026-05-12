
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SCED — <?php echo $__env->yieldContent('title', 'Sistema'); ?></title>
    <link rel="stylesheet" href="<?php echo e(asset('css/sced.css')); ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <?php echo $__env->yieldPushContent('styles'); ?>
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

        <a href="<?php echo e(route('dashboard')); ?>"
           class="sidebar-link <?php echo e(request()->routeIs('dashboard') ? 'active' : ''); ?>">
            <span class="nav-icon">🏠</span> Dashboard
        </a>

        <a href="<?php echo e(route('documentos.index')); ?>"
           class="sidebar-link <?php echo e(request()->routeIs('documentos.index') || request()->routeIs('documentos.show') ? 'active' : ''); ?>">
            <span class="nav-icon">📄</span> Documentos
        </a>

        <a href="<?php echo e(route('documentos.create')); ?>"
           class="sidebar-link <?php echo e(request()->routeIs('documentos.create') ? 'active' : ''); ?>">
            <span class="nav-icon">➕</span> Novo Documento
        </a>

        <a href="<?php echo e(route('tipos.index')); ?>"
           class="sidebar-link <?php echo e(request()->routeIs('tipos.*') ? 'active' : ''); ?>">
            <span class="nav-icon">🏷️</span> Tipos de Doc.
        </a>

        <?php if(auth()->user()->isAdmin()): ?>
        <div class="nav-section-label">Administração</div>

        <a href="<?php echo e(route('usuarios.index')); ?>"
           class="sidebar-link <?php echo e(request()->routeIs('usuarios.*') ? 'active' : ''); ?>">
            <span class="nav-icon">👥</span> Usuários
        </a>

        <a href="<?php echo e(route('relatorios.index')); ?>"
           class="sidebar-link <?php echo e(request()->routeIs('relatorios.*') ? 'active' : ''); ?>">
            <span class="nav-icon">📊</span> Relatórios
        </a>

        
        <a href="<?php echo e(route('departamentos.index')); ?>"
           class="sidebar-link <?php echo e(request()->routeIs('departamentos.*') ? 'active' : ''); ?>">
            <span class="nav-icon">🏢</span> Departamentos
        </a>
        <?php endif; ?>

    </nav>

    <div class="sidebar-footer">
        <div class="user-info">
            <div class="user-avatar">
                <?php echo e(strtoupper(substr(auth()->user()->nome, 0, 1))); ?>

            </div>
            <div>
                <div class="user-name"><?php echo e(auth()->user()->nome); ?></div>
                <div class="user-role">
                    <?php echo e(auth()->user()->perfil === 'administrador' ? 'Administrador' : 'Operador'); ?>

                </div>
            </div>
        </div>
        <form action="<?php echo e(route('logout')); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <button type="submit" class="btn-sair">
                🚪 Sair do sistema
            </button>
        </form>
    </div>
</aside>

<div class="main-content">

    
    <div class="topbar">
        <div>
            <div class="topbar-title"><?php echo $__env->yieldContent('title', 'Dashboard'); ?></div>
            <div class="topbar-sub"><?php echo $__env->yieldContent('subtitle', ''); ?></div>
        </div>
        <div style="display:flex; gap:8px; align-items:center;">
            <button class="btn-secondary-sced d-lg-none" onclick="document.getElementById('sidebar').classList.toggle('open')">
                ☰
            </button>
            <?php echo $__env->yieldContent('topbar-actions'); ?>
        </div>
    </div>

    
    <?php if(session('success')): ?>
        <div style="padding: 0 32px; padding-top: 16px;">
            <div class="alert-sced alert-success">
                ✅ <?php echo e(session('success')); ?>

            </div>
        </div>
    <?php endif; ?>
    <?php if(session('error')): ?>
        <div style="padding: 0 32px; padding-top: 16px;">
            <div class="alert-sced alert-error">
                ❌ <?php echo e(session('error')); ?>

            </div>
        </div>
    <?php endif; ?>

    
    <div class="page-body">
        <?php echo $__env->yieldContent('content'); ?>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>


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

<?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html><?php /**PATH C:\xampp\htdocs\Controle-de-Entrada-de-Documentos\sced\resources\views/layouts/app.blade.php ENDPATH**/ ?>