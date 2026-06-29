<?php
require_once __DIR__ . '/Database.php';

class Relatorio {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function totalTarefas(int $usuario_id): int {
        $sql = "SELECT COUNT(*) FROM tarefas
                WHERE usuario_id = :usuario_id";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':usuario_id' => $usuario_id]);

        return (int) $stmt->fetchColumn();
    }

    public function totalConcluidas(int $usuario_id): int {
        $sql = "SELECT COUNT(*) FROM tarefas
                WHERE usuario_id = :usuario_id
                AND status = 'concluida'";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':usuario_id' => $usuario_id]);

        return (int) $stmt->fetchColumn();
    }

    public function totalPendentes(int $usuario_id): int {
        $sql = "SELECT COUNT(*) FROM tarefas
                WHERE usuario_id = :usuario_id
                AND status != 'concluida'";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':usuario_id' => $usuario_id]);

        return (int) $stmt->fetchColumn();
    }

    public function totalAtrasadas(int $usuario_id): int {
        $sql = "SELECT COUNT(*) FROM tarefas
                WHERE usuario_id = :usuario_id
                AND status != 'concluida'
                AND data_limite < CURDATE()";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':usuario_id' => $usuario_id]);

        return (int) $stmt->fetchColumn();
    }

    public function concluidasPorDia(int $usuario_id): array {
        $sql = "SELECT DATE(concluida_em) AS dia, COUNT(*) AS total
                FROM tarefas
                WHERE usuario_id = :usuario_id
                AND status = 'concluida'
                AND concluida_em IS NOT NULL
                GROUP BY DATE(concluida_em)
                ORDER BY dia DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':usuario_id' => $usuario_id]);

        return $stmt->fetchAll();
    }

    public function tarefasAtrasadas(int $usuario_id): array {
        $sql = "SELECT titulo, prioridade, data_limite, status
                FROM tarefas
                WHERE usuario_id = :usuario_id
                AND status != 'concluida'
                AND data_limite < CURDATE()
                ORDER BY data_limite ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':usuario_id' => $usuario_id]);

        return $stmt->fetchAll();
    }
}