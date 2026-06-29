<?php
require_once '../includes/auth_check.php';
require_once '../classes/Tarefa.php';

$tarefa = new Tarefa();
$id = (int)($_GET['id'] ?? 0);
$t = $tarefa->buscarPorId($id, $_SESSION['usuario_id']);

if (!$t) {
    header('Location: /dashboard.php');
    exit;
}

$erro = '';

function dataValida(string $data): bool {
    $d = DateTime::createFromFormat('Y-m-d', $data);
    return $d && $d->format('Y-m-d') === $data;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo      = trim($_POST['titulo'] ?? '');
    $descricao   = trim($_POST['descricao'] ?? '');
    $prioridade  = $_POST['prioridade'] ?? 'media';
    $data_limite = $_POST['data_limite'] ?? '';

    $prioridadesValidas = ['baixa', 'media', 'alta'];

    if (empty($titulo)) {
        $erro = 'O título é obrigatório.';
    } elseif (empty($data_limite)) {
        $erro = 'A data limite é obrigatória.';
    } elseif (!dataValida($data_limite)) {
        $erro = 'Data limite inválida.';
    } elseif (!in_array($prioridade, $prioridadesValidas, true)) {
        $erro = 'Prioridade inválida.';
    } else {
        $tarefa->atualizar($id, $_SESSION['usuario_id'], $titulo, $descricao, $prioridade, $data_limite);

        $_SESSION['msg'] = 'Tarefa atualizada!';
        $_SESSION['msg_tipo'] = 'sucesso';

        header('Location: /dashboard.php');
        exit;
    }
}

$tituloValor = $_POST['titulo'] ?? $t['titulo'];
$descricaoValor = $_POST['descricao'] ?? ($t['descricao'] ?? '');
$prioridadeValor = $_POST['prioridade'] ?? $t['prioridade'];
$dataLimiteValor = $_POST['data_limite'] ?? $t['data_limite'];
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Tarefa</title>
    <link rel="icon" type="image/svg+xml" href="/assets/img/favicon.svg">
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
    <h1>Editar tarefa</h1>

    <?php if ($erro): ?>
        <div class="erro"><?= htmlspecialchars($erro) ?></div>
    <?php endif; ?>

    <form method="POST">
        <label for="titulo">Título *</label>
        <input type="text" id="titulo" name="titulo" required value="<?= htmlspecialchars($tituloValor) ?>">

        <label for="descricao">Descrição</label>
        <textarea id="descricao" name="descricao"><?= htmlspecialchars($descricaoValor) ?></textarea>

        <label for="prioridade">Prioridade *</label>
        <select id="prioridade" name="prioridade" required>
            <?php foreach (['baixa' => 'Baixa', 'media' => 'Média', 'alta' => 'Alta'] as $val => $label): ?>
                <option value="<?= $val ?>" <?= $prioridadeValor === $val ? 'selected' : '' ?>>
                    <?= $label ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="data_limite">Data limite *</label>
        <input type="date" id="data_limite" name="data_limite" required value="<?= htmlspecialchars($dataLimiteValor) ?>">

        <div class="actions">
            <button type="submit" class="btn btn-salvar">Salvar</button>
            <a href="/dashboard.php" class="btn btn-cancelar">Cancelar</a>
        </div>
    </form>
</div>
</body>
</html>
