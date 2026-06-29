<?php
session_start();
if (isset($_SESSION['usuario_id'])) {
    header('Location: /dashboard.php');
    exit;
}

require_once 'classes/Usuario.php';
$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';

    $usuario = new Usuario();
    $resultado = $usuario->login($email, $senha);

    if ($resultado) {
        $_SESSION['usuario_id']   = $resultado['id'];
        $_SESSION['usuario_nome'] = $resultado['nome'];
        header('Location: /dashboard.php');
        exit;
    } else {
        $erro = 'E-mail ou senha incorretos.';
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Tarefas</title>
    <link rel="icon" type="image/svg+xml" href="/assets/img/favicon.svg">
    <link rel="stylesheet" href="/assets/css/style.css">
    <style>
        body { display: flex; align-items: center; justify-content: center; min-height: 100vh; background: #F7F8FA; font-family: 'Segoe UI', sans-serif; }
        .card { background: #fff; border: 1px solid #E4E7EC; border-radius: 12px; padding: 2.5rem 2rem; width: 100%; max-width: 380px; box-shadow: 0 1px 3px rgba(0,0,0,.08); }
        h1 { font-size: 1.4rem; margin-bottom: .3rem; }
        p.sub { color: #6B7280; font-size: .9rem; margin-bottom: 1.5rem; }
        label { display: block; font-size: .85rem; font-weight: 600; margin-bottom: .3rem; }
        input { width: 100%; padding: .6rem .8rem; border: 1px solid #E4E7EC; border-radius: 8px; font-size: .9rem; margin-bottom: 1rem; outline: none; }
        input:focus { border-color: #3B82F6; }
        .btn-submit { width: 100%; padding: .7rem; background: #1A1D23; color: #fff; border: none; border-radius: 8px; font-size: .95rem; font-weight: 600; cursor: pointer; }
        .btn-submit:hover { opacity: .85; }
        .erro { background: #FEE2E2; color: #991B1B; padding: .6rem .8rem; border-radius: 8px; font-size: .85rem; margin-bottom: 1rem; }
        .link { text-align: center; margin-top: 1rem; font-size: .85rem; color: #6B7280; }
        .link a { color: #3B82F6; text-decoration: none; }
    </style>
</head>
<body>
<div class="card">
    <h1>📋 Tarefas</h1>
    <p class="sub">Entre na sua conta para continuar.</p>

    <?php if ($erro): ?>
        <div class="erro"><?= htmlspecialchars($erro) ?></div>
    <?php endif; ?>

    <form method="POST">
        <label for="email">E-mail</label>
        <input type="email" id="email" name="email" required autofocus>

        <label for="senha">Senha</label>
        <input type="password" id="senha" name="senha" required>

        <button type="submit" class="btn-submit">Entrar</button>
    </form>

    <div class="link">Não tem conta? <a href="/register.php">Cadastre-se</a></div>
</div>
</body>
</html>