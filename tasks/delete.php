<?php
require_once '../includes/auth_check.php';
require_once '../classes/Tarefa.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)($_POST['id'] ?? 0);
    $tarefa = new Tarefa();
    $tarefa->deletar($id, $_SESSION['usuario_id']);
    $_SESSION['msg'] = 'Tarefa removida.';
    $_SESSION['msg_tipo'] = 'sucesso';
}

header('Location: /dashboard.php');
exit;