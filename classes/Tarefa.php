<?php
require_once __DIR__ . '/Database.php';

class Tarefa {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    
    public function criar(int $usuario_id, string $titulo, string $descricao, string $prioridade, string $data_limite): bool {
        $sql = "INSERT INTO tarefas (usuario_id, titulo, descricao, prioridade, data_limite)
                VALUES (:usuario_id, :titulo, :descricao, :prioridade, :data_limite)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':usuario_id'  => $usuario_id,
            ':titulo'      => $titulo,
            ':descricao'   => $descricao,
            ':prioridade'  => $prioridade,
            ':data_limite' => $data_limite,
        ]);
    }

    // Lista todas as tarefas do usuário
    public function listarPorUsuario(int $usuario_id): array {
        $sql = "SELECT * FROM tarefas WHERE usuario_id = :usuario_id ORDER BY data_limite ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':usuario_id' => $usuario_id]);
        return $stmt->fetchAll();
    }

    
    public function buscarPorId(int $id, int $usuario_id): array|false {
        $sql = "SELECT * FROM tarefas WHERE id = :id AND usuario_id = :usuario_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id, ':usuario_id' => $usuario_id]);
        return $stmt->fetch();
    }

   
    public function atualizar(int $id, int $usuario_id, string $titulo, string $descricao, string $prioridade, string $data_limite): bool {
        $sql = "UPDATE tarefas
                SET titulo = :titulo, descricao = :descricao, prioridade = :prioridade, data_limite = :data_limite
                WHERE id = :id AND usuario_id = :usuario_id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':titulo'      => $titulo,
            ':descricao'   => $descricao,
            ':prioridade'  => $prioridade,
            ':data_limite' => $data_limite,
            ':id'          => $id,
            ':usuario_id'  => $usuario_id,
        ]);
    }

    
    public function deletar(int $id, int $usuario_id): bool {
        $sql = "DELETE FROM tarefas WHERE id = :id AND usuario_id = :usuario_id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id, ':usuario_id' => $usuario_id]);
    }

    
    public function atualizarStatus(int $id, int $usuario_id, string $status): bool {
        if ($status === 'concluida') {
            $sql = "UPDATE tarefas SET status = :status, concluida_em = NOW()
                    WHERE id = :id AND usuario_id = :usuario_id";
        } else {
            $sql = "UPDATE tarefas SET status = :status, concluida_em = NULL
                    WHERE id = :id AND usuario_id = :usuario_id";
        }
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':status'      => $status,
            ':id'          => $id,
            ':usuario_id'  => $usuario_id,
        ]);
    }
}
