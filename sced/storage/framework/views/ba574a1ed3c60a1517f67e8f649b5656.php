
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SCED — Login</title>
    <link rel="stylesheet" href="<?php echo e(asset('css/sced.css')); ?>">
</head>
<body>

<div class="login-page">
    <div class="login-card">

        <div class="login-logo">
            <div class="logo-box">📂</div>
            <h1>SCED</h1>
            <p>Sistema de Controle de Entrada de Documentos</p>
        </div>

        <?php if($errors->any()): ?>
            <div class="alert-sced alert-error" style="margin-bottom: 20px;">
                ⚠️ <?php echo e($errors->first()); ?>

            </div>
        <?php endif; ?>

        <?php if(session('status')): ?>
            <div class="alert-sced alert-success">
                ✅ <?php echo e(session('status')); ?>

            </div>
        <?php endif; ?>

        <form method="POST" action="<?php echo e(route('login')); ?>">
            <?php echo csrf_field(); ?>

            <div class="form-group">
                <label class="form-label-sced">E-mail</label>
                <input
                    type="email"
                    name="email"
                    id="email"
                    class="form-input-sced <?php echo e($errors->has('email') ? 'is-invalid' : ''); ?>"
                    value="<?php echo e(old('email')); ?>"
                    placeholder="seu@email.com"
                    autofocus
                    required
                >
            </div>

            
            <div class="form-group">
                <label class="form-label-sced">Senha</label>
                <div style="position: relative;">
                    <input
                        type="password"
                        name="password"
                        id="login_password"
                        class="form-input-sced <?php echo e($errors->has('password') ? 'is-invalid' : ''); ?>"
                        placeholder="••••••••"
                        style="padding-right: 44px;"
                        required
                    >
                    <button type="button"
                        onclick="toggleSenha('login_password', this)"
                        style="position:absolute;right:12px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:#94a3b8;display:flex;align-items:center;padding:0;"
                        title="Mostrar/ocultar senha">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                            <circle cx="12" cy="12" r="3"/>
                        </svg>
                    </button>
                </div>
            </div>

            <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:24px;">
                <label style="display:flex; align-items:center; gap:8px; font-size:13px; color:var(--cinza-600); cursor:pointer;">
                    <input type="checkbox" name="remember"> Manter conectado
                </label>
            </div>

            <button type="submit" class="btn-login">Entrar no sistema</button>
        </form>

        <p style="text-align:center; margin-top:24px; font-size:12px; color:var(--cinza-400);">
            SCED © <?php echo e(date('Y')); ?> — Acesso restrito
        </p>
    </div>
</div>

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

</body>
</html>
<?php /**PATH C:\xampp\htdocs\Controle-de-Entrada-de-Documentos\sced\resources\views/auth/login.blade.php ENDPATH**/ ?>