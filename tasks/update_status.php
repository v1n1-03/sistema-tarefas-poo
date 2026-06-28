<?php
require_once '../includes/auth_check.php';
require_once '../classes/Tarefa.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id     = (int)($_POST['id'] ?? 0);
    $status = $_POST['status'] ?? '';

    $statusValidos = ['pendente', 'em_andamento', 'concluida'];

    if ($id > 0 && in_array($status, $statusValidos)) {
        $tarefa = new Tarefa();
        $tarefa->atualizarStatus($id, $_SESSION['usuario_id'], $status);
    }
}

header('Location: /dashboard.php');
exit;