<?php
require_once 'includes/auth_check.php';
require_once 'classes/Tarefa.php';

$tarefa = new Tarefa();
$todasTarefas = $tarefa->listarPorUsuario($_SESSION['usuario_id']);

$pendentes    = [];
$em_andamento = [];
$concluidas   = [];
$atrasadas    = [];

$hoje = date('Y-m-d');

foreach ($todasTarefas as $t) {
    if ($t['status'] !== 'concluida' && !empty($t['data_limite']) && $t['data_limite'] < $hoje) {
        $atrasadas[] = $t;
    } elseif ($t['status'] === 'pendente') {
        $pendentes[] = $t;
    } elseif ($t['status'] === 'em_andamento') {
        $em_andamento[] = $t;
    } elseif ($t['status'] === 'concluida') {
        $concluidas[] = $t;
    }
}

function badgePrioridade($p) {
    $map = [
        'alta'  => ['Alta',  'badge-alta'],
        'media' => ['Média', 'badge-media'],
        'baixa' => ['Baixa', 'badge-baixa'],
    ];
    [$label, $class] = $map[$p] ?? [$p, ''];
    return "<span class=\"badge $class\">$label</span>";
}

function dataFormatada($data) {
    return $data ? date('d/m/Y', strtotime($data)) : '—';
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard — Tarefas</title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>

<nav>
    <a href="dashboard.php" class="nav-brand">Tarefas</a>
    <ul class="nav-links">
        <li><a href="dashboard.php">Dashboard</a></li>
        <li><a href="reports.php">Relatórios</a></li>
        <li><a href="logout.php" class="btn-logout">Sair</a></li>
    </ul>
</nav>

<?php if (isset($_SESSION['msg'])): ?>
    <div class="flash <?= htmlspecialchars($_SESSION['msg_tipo'] ?? 'sucesso') ?>">
        <?= htmlspecialchars($_SESSION['msg']) ?>
    </div>
    <?php unset($_SESSION['msg'], $_SESSION['msg_tipo']); ?>
<?php endif; ?>

<div class="page-header">
    <div>
        <h1>Olá, <?= htmlspecialchars($_SESSION['usuario_nome']) ?></h1>
        <p><?= date('d \d\e F \d\e Y') ?></p>
    </div>
    <a href="tasks/create.php" class="btn-criar">+ Nova tarefa</a>
</div>

<div class="summary">
    <div class="summary-card s-atrasada">
        <div class="count"><?= count($atrasadas) ?></div>
        <div class="label">Atrasadas</div>
    </div>
    <div class="summary-card s-pendente">
        <div class="count"><?= count($pendentes) ?></div>
        <div class="label">Pendentes</div>
    </div>
    <div class="summary-card s-andamento">
        <div class="count"><?= count($em_andamento) ?></div>
        <div class="label">Em andamento</div>
    </div>
    <div class="summary-card s-concluida">
        <div class="count"><?= count($concluidas) ?></div>
        <div class="label">Concluídas</div>
    </div>
</div>

<main class="main">
<?php
$grupos = [
    ['titulo' => 'Atrasadas',    'dot' => 'atrasada',  'tarefas' => $atrasadas],
    ['titulo' => 'Pendentes',    'dot' => 'pendente',  'tarefas' => $pendentes],
    ['titulo' => 'Em andamento', 'dot' => 'andamento', 'tarefas' => $em_andamento],
    ['titulo' => 'Concluídas',   'dot' => 'concluida', 'tarefas' => $concluidas],
];

foreach ($grupos as $grupo):
?>
<section>
    <div class="group-header">
        <span class="group-dot <?= $grupo['dot'] ?>"></span>
        <span class="group-title"><?= $grupo['titulo'] ?></span>
        <span class="group-count"><?= count($grupo['tarefas']) ?></span>
    </div>

    <?php if (empty($grupo['tarefas'])): ?>
        <div class="empty">Nenhuma tarefa aqui.</div>
    <?php else: ?>
        <?php foreach ($grupo['tarefas'] as $t): ?>
        <div class="task-row">
            <div>
                <div class="task-title"><?= htmlspecialchars($t['titulo']) ?></div>
                <?php if (!empty($t['descricao'])): ?>
                    <div class="task-desc"><?= htmlspecialchars($t['descricao']) ?></div>
                <?php endif; ?>
            </div>

            <?= badgePrioridade($t['prioridade']) ?>

            <span class="task-date"><?= dataFormatada($t['data_limite']) ?></span>

            <div class="actions">
                <form method="POST" action="tasks/update_status.php" style="display:inline">
                    <input type="hidden" name="id" value="<?= $t['id'] ?>">
                    <select name="status" class="status-select" onchange="this.form.submit()">
                        <option value="pendente"     <?= $t['status'] === 'pendente'     ? 'selected' : '' ?>>Pendente</option>
                        <option value="em_andamento" <?= $t['status'] === 'em_andamento' ? 'selected' : '' ?>>Em andamento</option>
                        <option value="concluida"    <?= $t['status'] === 'concluida'    ? 'selected' : '' ?>>Concluída</option>
                    </select>
                </form>
                <a href="tasks/edit.php?id=<?= $t['id'] ?>" class="btn-action">Editar</a>
                <form method="POST" action="tasks/delete.php" style="display:inline" onsubmit="return confirmarExclusao()">
                    <input type="hidden" name="id" value="<?= $t['id'] ?>">
                    <button type="submit" class="btn-action delete">Excluir</button>
                </form>
            </div>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>
</section>
<?php endforeach; ?>
</main>

<script src="/assets/js/main.js"></script>
</body>
</html>