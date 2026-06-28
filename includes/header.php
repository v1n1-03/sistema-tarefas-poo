<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Tarefas</title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
<nav>
    <span class="nav-brand">📋 Tarefas</span>
    <ul class="nav-links">
        <?php if (isset($_SESSION['usuario_id'])): ?>
            <li><a href="/dashboard.php">Dashboard</a></li>
            <li><a href="/reports.php">Relatórios</a></li>
            <li><a href="/logout.php" class="btn-logout">Sair</a></li>
        <?php endif; ?>
    </ul>
</nav>