<?php
require_once 'includes/auth_check.php';
require_once 'classes/Relatorio.php';

$relatorio = new Relatorio();

$totalTarefas = $relatorio->totalTarefas($_SESSION['usuario_id']);
$totalConcluidas = $relatorio->totalConcluidas($_SESSION['usuario_id']);
$totalPendentes = $relatorio->totalPendentes($_SESSION['usuario_id']);
$totalAtrasadas = $relatorio->totalAtrasadas($_SESSION['usuario_id']);
$concluidasPorDia = $relatorio->concluidasPorDia($_SESSION['usuario_id']);
$tarefasAtrasadas = $relatorio->tarefasAtrasadas($_SESSION['usuario_id']);

if ($totalTarefas > 0) {
    $percentualConcluido = round(($totalConcluidas / $totalTarefas) * 100);
} else {
    $percentualConcluido = 0;
}

function dataFormatada($data) {
    return $data ? date('d/m/Y', strtotime($data)) : '—';
}

function badgePrioridade($p) {
    $map = [
        'alta'  => ['Alta',  'badge-alta'],
        'media' => ['Média', 'badge-media'],
        'baixa' => ['Baixa', 'badge-baixa'],
    ];

    $label = isset($map[$p]) ? $map[$p][0] : $p;
    $class = isset($map[$p]) ? $map[$p][1] : '';

    return "<span class=\"badge $class\">$label</span>";
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatórios — Tarefas</title>
    <link rel="icon" type="image/svg+xml" href="/assets/img/favicon.svg">
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

<div class="page-header">
    <div>
        <h1>Relatórios</h1>
        <p>Resumo de produtividade das suas tarefas.</p>
    </div>
</div>

<div class="summary">
    <div class="summary-card s-andamento">
        <div class="count"><?= $totalTarefas ?></div>
        <div class="label">Total</div>
    </div>

    <div class="summary-card s-concluida">
        <div class="count"><?= $totalConcluidas ?></div>
        <div class="label">Concluídas</div>
    </div>

    <div class="summary-card s-pendente">
        <div class="count"><?= $totalPendentes ?></div>
        <div class="label">Pendentes</div>
    </div>

    <div class="summary-card s-atrasada">
        <div class="count"><?= $totalAtrasadas ?></div>
        <div class="label">Atrasadas</div>
    </div>
</div>

<main class="main">

    <section>
        <div class="group-header">
            <span class="group-dot andamento"></span>
            <span class="group-title">Resumo geral</span>
        </div>

        <div class="task-row">
            <div>
                <div class="task-title">Produtividade geral</div>
                <div class="task-desc">
                    <?= $percentualConcluido ?>% das tarefas cadastradas foram concluídas.
                </div>
            </div>

            <span class="badge badge-media">
                <?= $percentualConcluido ?>%
            </span>
        </div>
    </section>

    <section>
        <div class="group-header">
            <span class="group-dot concluida"></span>
            <span class="group-title">Tarefas concluídas por dia</span>
            <span class="group-count"><?= count($concluidasPorDia) ?></span>
        </div>

        <?php if (empty($concluidasPorDia)): ?>
            <div class="empty">Nenhuma tarefa concluída ainda.</div>
        <?php else: ?>
            <?php foreach ($concluidasPorDia as $linha): ?>
                <div class="task-row">
                    <div>
                        <div class="task-title"><?= dataFormatada($linha['dia']) ?></div>
                        <div class="task-desc">Tarefas concluídas nesse dia</div>
                    </div>

                    <span class="badge badge-baixa">
                        <?= (int) $linha['total'] ?> concluída(s)
                    </span>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </section>

    <section>
        <div class="group-header">
            <span class="group-dot atrasada"></span>
            <span class="group-title">Tarefas atrasadas</span>
            <span class="group-count"><?= count($tarefasAtrasadas) ?></span>
        </div>

        <?php if (empty($tarefasAtrasadas)): ?>
            <div class="empty">Nenhuma tarefa atrasada.</div>
        <?php else: ?>
            <?php foreach ($tarefasAtrasadas as $t): ?>
                <div class="task-row">
                    <div>
                        <div class="task-title"><?= htmlspecialchars($t['titulo']) ?></div>
                        <div class="task-desc">
                            Status: <?= htmlspecialchars($t['status']) ?> · Prazo: <?= dataFormatada($t['data_limite']) ?>
                        </div>
                    </div>

                    <?= badgePrioridade($t['prioridade']) ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </section>

</main>

<script src="/assets/js/main.js"></script>
</body>
</html>