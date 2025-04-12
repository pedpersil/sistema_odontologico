<?php

require_once __DIR__ . '/../../config/Database.php';

class Especialidade {
    private PDO $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->connect();
    }

    public function getAll(): array {
        $stmt = $this->conn->query("SELECT * FROM especialidades");
        return $stmt->fetchAll();
    }

    public function create(array $data): bool {
        $sql = "INSERT INTO especialidades (descricao) VALUES (:descricao)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':descricao' => $data['descricao']
        ]);
    }

    public function find(int $id): array|false {
        $stmt = $this->conn->prepare("SELECT * FROM especialidades WHERE id_especialidade = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function update(int $id, array $data): bool {
        $sql = "UPDATE especialidades SET descricao = :descricao WHERE id_especialidade = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':descricao' => $data['descricao'],
            ':id' => $id
        ]);
    }

    public function delete(int $id): bool {
        $this->conn->prepare("DELETE FROM dentistas WHERE especialidade_id = :id")
                   ->execute([':id' => $id]);
    
        // Agora exclui a especialidade
        $stmt = $this->conn->prepare("DELETE FROM especialidades WHERE id_especialidade = :id");
        return $stmt->execute([':id' => $id]);
    }
    

    public function total(): int {
        $stmt = $this->conn->query("SELECT COUNT(*) AS total FROM especialidades");
        $result = $stmt->fetch();
        return (int) $result['total'];
    }
}
