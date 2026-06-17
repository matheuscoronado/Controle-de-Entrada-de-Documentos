
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>SCED Help Desk — <?php echo $__env->yieldContent('title', 'Sistema'); ?></title>
    <link rel="stylesheet" href="<?php echo e(asset('css/sced.css')); ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body>


<aside class="sidebar" id="sidebar">

    <div class="sidebar-logo">
        <div class="logo-icon">🎯</div>
        <div class="logo-title">SCED</div>
        <div class="logo-sub">Sistema de Controle de Entrada de Documentos</div>
    </div>

    <nav class="sidebar-nav">

        <div class="nav-section-label">Principal</div>

        <a href="<?php echo e(route('dashboard')); ?>"
           class="sidebar-link <?php echo e(request()->routeIs('dashboard') ? 'active' : ''); ?>">
            <span class="nav-icon">🏠</span> Dashboard
        </a>

        
        <?php
            $user = auth()->user();
            // Contador de processos que exigem ação do usuário
            $processosAcaoPendente = \App\Models\Documento::where(function($q) use ($user) {
                $q->where('atribuido_a_id', $user->id)
                  ->whereIn('status', ['novo', 'em_analise', 'pendente']);
            })->orWhere(function($q) use ($user) {
                $q->where('usuario_registro_id', $user->id)
                  ->where('status', 'pendente');
            })->count();
            
            $contadorProcessos = $processosAcaoPendente > 0 ? $processosAcaoPendente : null;
        ?>
        
        <a href="<?php echo e(route('documentos.index')); ?>"
           class="sidebar-link <?php echo e(request()->routeIs('documentos.index','documentos.show') ? 'active' : ''); ?>">
            <span class="nav-icon">📂</span> Processos
            <?php if($contadorProcessos): ?>
                <span class="nav-badge"><?php echo e($contadorProcessos); ?></span>
            <?php endif; ?>
        </a>

        <a href="<?php echo e(route('documentos.create')); ?>"
           class="sidebar-link <?php echo e(request()->routeIs('documentos.create') ? 'active' : ''); ?>">
            <span class="nav-icon">➕</span> Novo Processo
        </a>

        
        <?php if($user->isAdmin() || $user->isN3()): ?>
        <div class="nav-section-label">Supervisão</div>

        <a href="<?php echo e(route('logs.index')); ?>"
           class="sidebar-link <?php echo e(request()->routeIs('logs.*') ? 'active' : ''); ?>">
            <span class="nav-icon">📋</span> Logs / Auditoria
        </a>
        <?php endif; ?>

        
        <?php if($user->isAdmin()): ?>
        <div class="nav-section-label">Administração</div>

        <a href="<?php echo e(route('tipos.index')); ?>"
           class="sidebar-link <?php echo e(request()->routeIs('tipos.*') ? 'active' : ''); ?>">
            <span class="nav-icon">🏷️</span> Cadastro de Serviço
        </a>

        <a href="<?php echo e(route('documentos-tipo.index')); ?>"
           class="sidebar-link <?php echo e(request()->routeIs('documentos-tipo.*') ? 'active' : ''); ?>">
            <span class="nav-icon">📄</span> Cadastro de Documentos
        </a>

        <a href="<?php echo e(route('usuarios.index')); ?>"
           class="sidebar-link <?php echo e(request()->routeIs('usuarios.*') ? 'active' : ''); ?>">
            <span class="nav-icon">👥</span> Usuários
        </a>

        <a href="<?php echo e(route('departamentos.index')); ?>"
           class="sidebar-link <?php echo e(request()->routeIs('departamentos.*') ? 'active' : ''); ?>">
            <span class="nav-icon">🏢</span> Departamentos
        </a>

        <a href="<?php echo e(route('relatorios.index')); ?>"
           class="sidebar-link <?php echo e(request()->routeIs('relatorios.*') ? 'active' : ''); ?>">
            <span class="nav-icon">📊</span> Relatórios
        </a>
        <?php endif; ?>

    </nav>

    
    <div class="sidebar-footer">
        <div class="user-info">
            <div class="user-avatar">
                <?php echo e(strtoupper(substr(auth()->user()->nome, 0, 1))); ?>

            </div>
            <div class="user-details">
                <div class="user-name">
                    <?php echo e(auth()->user()->nome); ?>

                </div>
                <div class="user-role">
                    <?php
                        $cargoLabels = [
                            'N1' => 'Atendimento',
                            'N2' => 'Analista',
                            'N3' => 'Supervisor',
                            'administrador' => 'Administrador',
                            'admin' => 'Administrador'
                        ];
                        $cargoLabel = $cargoLabels[auth()->user()->cargo] ?? auth()->user()->cargo;
                    ?>
                    <?php echo e($cargoLabel); ?>

                </div>
                <div class="user-sector">
                    <?php echo e(auth()->user()->departamentoRelacionado->nome ?? 'Setor não definido'); ?>

                </div>
            </div>
        </div>
        <form action="<?php echo e(route('logout')); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <button type="submit" class="btn-sair">🚪 Sair do sistema</button>
        </form>
    </div>
</aside>


<div class="main-content">

    <div class="topbar">
        <div>
            <div class="topbar-title"><?php echo $__env->yieldContent('title', 'Dashboard'); ?></div>
            <div class="topbar-sub"><?php echo $__env->yieldContent('subtitle', ''); ?></div>
        </div>
        <div class="topbar-actions">
            <button class="btn-secondary-sced d-lg-none"
                    onclick="document.getElementById('sidebar').classList.toggle('open')"
                    style="padding:8px 12px;">☰</button>
            <?php echo $__env->yieldContent('topbar-actions'); ?>
        </div>
    </div>

    <div class="page-body">
        <?php if(session('success')): ?>
            <div class="alert-sced alert-success">✅ <?php echo e(session('success')); ?></div>
        <?php endif; ?>
        <?php if(session('error')): ?>
            <div class="alert-sced alert-error">❌ <?php echo e(session('error')); ?></div>
        <?php endif; ?>
        <?php if(session('warning')): ?>
            <div class="alert-sced alert-warning">⚠️ <?php echo e(session('warning')); ?></div>
        <?php endif; ?>

        <?php echo $__env->yieldContent('content'); ?>
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
<?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html><?php /**PATH C:\xampp\htdocs\Controle-de-Entrada-de-Documentos\sced\resources\views/layouts/app.blade.php ENDPATH**/ ?>