<?php
require_once '../includes/auth_check.php';
require_once '../classes/Tarefa.php';

$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo      = trim($_POST['titulo'] ?? '');
    $descricao   = trim($_POST['descricao'] ?? '');
    $prioridade  = $_POST['prioridade'] ?? 'media';
    $data_limite = $_POST['data_limite'] ?? '';

    if (empty($titulo)) {
        $erro = 'O título é obrigatório.';
    } else {
        $tarefa = new Tarefa();
        $tarefa->criar($_SESSION['usuario_id'], $titulo, $descricao, $prioridade, $data_limite);
        $_SESSION['msg'] = 'Tarefa criada com sucesso!';
        $_SESSION['msg_tipo'] = 'sucesso';
        header('Location: /dashboard.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nova Tarefa</title>
    <link rel="stylesheet" href="/assets/css/style.css">
    <style>
        body { background: #F7F8FA; font-family: 'Segoe UI', sans-serif; }
        .container { max-width: 520px; margin: 3rem auto; background: #fff; border: 1px solid #E4E7EC; border-radius: 12px; padding: 2rem; box-shadow: 0 1px 3px rgba(0,0,0,.08); }
        h1 { font-size: 1.3rem; margin-bottom: 1.5rem; }
        label { display: block; font-size: .85rem; font-weight: 600; margin-bottom: .3rem; }
        input, textarea, select { width: 100%; padding: .6rem .8rem; border: 1px solid #E4E7EC; border-radius: 8px; font-size: .9rem; margin-bottom: 1rem; font-family: inherit; outline: none; }
        input:focus, textarea:focus, select:focus { border-color: #3B82F6; }
        textarea { resize: vertical; min-height: 80px; }
        .actions { display: flex; gap: .8rem; }
        .btn { padding: .6rem 1.2rem; border-radius: 8px; font-size: .9rem; font-weight: 600; border: none; cursor: pointer; text-decoration: none; display: inline-block; }
        .btn-salvar { background: #1A1D23; color: #fff; }
        .btn-salvar:hover { opacity: .85; }
        .btn-cancelar { background: #F3F4F6; color: #374151; }
        .btn-cancelar:hover { background: #E5E7EB; }
        .erro { background: #FEE2E2; color: #991B1B; padding: .6rem .8rem; border-radius: 8px; font-size: .85rem; margin-bottom: 1rem; }
    </style>
</head>
<body>
<div class="container">
    <h1>➕ Nova tarefa</h1>

    <?php if ($erro): ?>
        <div class="erro"><?= htmlspecialchars($erro) ?></div>
    <?php endif; ?>

    <form method="POST">
        <label for="titulo">Título *</label>
        <input type="text" id="titulo" name="titulo" required autofocus value="<?= htmlspecialchars($_POST['titulo'] ?? '') ?>">

        <label for="descricao">Descrição</label>
        <textarea id="descricao" name="descricao"><?= htmlspecialchars($_POST['descricao'] ?? '') ?></textarea>

        <label for="prioridade">Prioridade</label>
        <select id="prioridade" name="prioridade">
            <option value="baixa"  <?= ($_POST['prioridade'] ?? '') === 'baixa'  ? 'selected' : '' ?>>Baixa</option>
            <option value="media"  <?= ($_POST['prioridade'] ?? 'media') === 'media'  ? 'selected' : '' ?>>Média</option>
            <option value="alta"   <?= ($_POST['prioridade'] ?? '') === 'alta'   ? 'selected' : '' ?>>Alta</option>
        </select>

        <label for="data_limite">Data limite</label>
        <input type="date" id="data_limite" name="data_limite" value="<?= htmlspecialchars($_POST['data_limite'] ?? '') ?>">

        <div class="actions">
            <button type="submit" class="btn btn-salvar">Salvar</button>
            <a href="/dashboard.php" class="btn btn-cancelar">Cancelar</a>
        </div>
    </form>
</div>
</body>
</html>