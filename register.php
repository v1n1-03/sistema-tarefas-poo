<?php
session_start();

if (isset($_SESSION['usuario_id'])) {
    header('Location: /dashboard.php');
    exit;
}

require_once 'classes/Usuario.php';

$erro = '';
$sucesso = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome     = trim($_POST['nome'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $senha    = $_POST['senha'] ?? '';
    $confirma = $_POST['confirma'] ?? '';

    if (empty($nome)) {
        $erro = 'O nome é obrigatório.';
    } elseif (empty($email)) {
        $erro = 'O e-mail é obrigatório.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erro = 'E-mail inválido.';
    } elseif ($senha !== $confirma) {
        $erro = 'As senhas não coincidem.';
    } elseif (strlen($senha) < 6) {
        $erro = 'A senha deve ter pelo menos 6 caracteres.';
    } else {
        try {
            $usuario = new Usuario();
            $usuario->cadastrar($nome, $email, $senha);
            $sucesso = 'Conta criada! <a href="/index.php">Faça login</a>.';
        } catch (PDOException $e) {
            $erro = 'Este e-mail já está cadastrado.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro — Tarefas</title>
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
        .erro    { background: #FEE2E2; color: #991B1B; padding: .6rem .8rem; border-radius: 8px; font-size: .85rem; margin-bottom: 1rem; }
        .sucesso { background: #D1FAE5; color: #065F46; padding: .6rem .8rem; border-radius: 8px; font-size: .85rem; margin-bottom: 1rem; }
        .link { text-align: center; margin-top: 1rem; font-size: .85rem; color: #6B7280; }
        .link a { color: #3B82F6; text-decoration: none; }
    </style>
</head>
<body>
<div class="card">
    <h1>📋 Criar conta</h1>
    <p class="sub">Preencha os dados para se cadastrar.</p>

    <?php if ($erro): ?>
        <div class="erro"><?= htmlspecialchars($erro) ?></div>
    <?php endif; ?>

    <?php if ($sucesso): ?>
        <div class="sucesso"><?= $sucesso ?></div>
    <?php endif; ?>

    <form method="POST" id="form-cadastro">
        <label for="nome">Nome</label>
        <input type="text" id="nome" name="nome" required value="<?= htmlspecialchars($_POST['nome'] ?? '') ?>">

        <label for="email">E-mail</label>
        <input type="email" id="email" name="email" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">

        <label for="senha">Senha</label>
        <input type="password" id="senha" name="senha" required>

        <label for="confirma">Confirmar senha</label>
        <input type="password" id="confirma" name="confirma" required>

        <button type="submit" class="btn-submit">Cadastrar</button>
    </form>

    <div class="link">Já tem conta? <a href="/index.php">Entrar</a></div>
</div>

<script src="/assets/js/main.js"></script>
</body>
</html>