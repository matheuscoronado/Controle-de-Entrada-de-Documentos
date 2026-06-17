
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>SCED - Sistema de Controle de Entrada de Documentos</title>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Sora', sans-serif;
            min-height: 100vh;
            background: linear-gradient(135deg, #0f2744 0%, #1a3f6f 60%, #164e8f 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .login-card {
            background: white;
            border-radius: 20px;
            padding: 44px 40px;
            width: 100%;
            max-width: 420px;
            box-shadow: 0 24px 64px rgba(0,0,0,0.25);
            animation: fadeUp 0.4s ease;
        }
        
        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: none;
            }
        }
        
        .login-logo {
            text-align: center;
            margin-bottom: 32px;
        }
        
        .logo-box {
            width: 56px;
            height: 56px;
            background: linear-gradient(135deg, #2563eb, #06b6d4);
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            margin: 0 auto 14px;
            box-shadow: 0 8px 24px rgba(37,99,235,0.3);
        }
        
        .login-logo h1 {
            font-size: 22px;
            font-weight: 700;
            color: #0f2744;
        }
        
        .login-logo p {
            font-size: 12px;
            color: #94a3b8;
            margin: 4px 0 0;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-label {
            display: block;
            font-size: 11px;
            font-weight: 600;
            color: #475569;
            margin-bottom: 6px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }
        
        .form-input {
            width: 100%;
            padding: 12px 45px 12px 14px;
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            font-family: 'Sora', sans-serif;
            font-size: 13px;
            color: #1e293b;
            background: white;
            transition: all 0.22s;
            outline: none;
        }
        
        .form-input:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37,99,235,0.1);
        }
        
        .form-input.is-invalid {
            border-color: #ef4444;
        }
        
        .btn-toggle-password {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            font-size: 18px;
            padding: 4px 8px;
            border-radius: 8px;
            color: #94a3b8;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .btn-toggle-password:hover {
            color: #2563eb;
            background: #f1f5f9;
        }
        
        .invalid-feedback {
            font-size: 11px;
            color: #ef4444;
            margin-top: 4px;
        }
        
        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 24px;
        }
        
        .checkbox-group input {
            width: 16px;
            height: 16px;
            cursor: pointer;
        }
        
        .checkbox-group label {
            font-size: 12px;
            color: #475569;
            cursor: pointer;
        }
        
        .btn-login {
            width: 100%;
            padding: 13px;
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            color: white;
            border: none;
            border-radius: 10px;
            font-family: 'Sora', sans-serif;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.22s;
            box-shadow: 0 4px 14px rgba(37,99,235,0.3);
        }
        
        .btn-login:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(37,99,235,0.4);
        }
        
        .alert-error {
            background: #fef2f2;
            color: #991b1b;
            border: 1px solid #fecaca;
            padding: 12px 16px;
            border-radius: 10px;
            font-size: 13px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .footer {
            text-align: center;
            margin-top: 24px;
            font-size: 11px;
            color: #94a3b8;
        }
        
        .footer a {
            color: #2563eb;
            text-decoration: none;
        }
        
        .footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="login-logo">
            <div class="logo-box">🎯</div>
            <h1>SCED</h1>
            <p>Sistema de Controle de Entrada de Documentos</p>
        </div>

        
        <?php if($errors->any()): ?>
            <div class="alert-error">
                ⚠️ <?php echo e($errors->first()); ?>

            </div>
        <?php endif; ?>

        <?php if(session('error')): ?>
            <div class="alert-error">
                ⚠️ <?php echo e(session('error')); ?>

            </div>
        <?php endif; ?>

        <form method="POST" action="<?php echo e(route('login')); ?>">
            <?php echo csrf_field(); ?>

            <div class="form-group">
                <label class="form-label">E-MAIL</label>
                <input type="email" 
                       name="email" 
                       class="form-input <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                       value="<?php echo e(old('email')); ?>" 
                       required 
                       autofocus
                       placeholder="seu@email.com">
                <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="form-group">
                <label class="form-label">SENHA</label>
                <div class="input-wrapper">
                    <input type="password" 
                           name="password" 
                           id="password"
                           class="form-input <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                           required
                           placeholder="••••••••">
                    <button type="button" 
                            class="btn-toggle-password" 
                            onclick="togglePassword('password')"
                            title="Mostrar/ocultar senha">
                        👁️
                    </button>
                </div>
                <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="checkbox-group">
                <input type="checkbox" name="remember" id="remember">
                <label for="remember">Manter conectado</label>
            </div>

            <button type="submit" class="btn-login">
                Entrar no sistema
            </button>
        </form>

        <div class="footer">
            SCED © <?php echo e(date('Y')); ?> — Acesso restrito
        </div>
    </div>

    <script>
        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            const btn = event.currentTarget;
            
            if (input.type === 'password') {
                input.type = 'text';
                btn.innerHTML = '🙈';
                btn.title = 'Ocultar senha';
            } else {
                input.type = 'password';
                btn.innerHTML = '👁️';
                btn.title = 'Mostrar senha';
            }
        }
    </script>
</body>
</html><?php /**PATH C:\xampp\htdocs\Controle-de-Entrada-de-Documentos\sced\resources\views/auth/login.blade.php ENDPATH**/ ?>