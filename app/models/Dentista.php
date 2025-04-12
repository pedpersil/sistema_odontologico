<?php

require_once __DIR__ . '/../../config/Database.php';

class Dentista {
    private PDO $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->connect();
    }

    public function getAll(): array {
        $stmt = $this->conn->query("SELECT * FROM dentistas");
        return $stmt->fetchAll();
    }

    public function create(array $data): bool {
        $sql = "INSERT INTO dentistas (
                    nome, especialidade_id, telefone, email,
                    endereco, cidade, estado, cep,
                    rg, cpf, cro
                ) VALUES (
                    :nome, :especialidadeId, :telefone, :email,
                    :endereco, :cidade, :estado, :cep,
                    :rg, :cpf, :cro
                )";

        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':nome' => $data['nome'],
            ':especialidadeId' => $data['especialidade_id'],
            ':telefone' => $data['telefone'],
            ':email' => $data['email'],
            ':endereco' => $data['endereco'],
            ':cidade' => $data['cidade'],
            ':estado' => $data['estado'],
            ':cep' => $data['cep'],
            ':rg' => $data['rg'],
            ':cpf' => $data['cpf'],
            ':cro' => $data['cro']
        ]);
    }

    public function find(int $id): array|false {
        $stmt = $this->conn->prepare("SELECT * FROM dentistas WHERE id_dentista = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function update(int $id, array $data): bool {
        $sql = "UPDATE dentistas SET
                    nome = :nome,
                    especialidade_id = :especialidadeId,
                    telefone = :telefone,
                    email = :email,
                    endereco = :endereco,
                    cidade = :cidade,
                    estado = :estado,
                    cep = :cep,
                    rg = :rg,
                    cpf = :cpf,
                    cro = :cro
                WHERE id_dentista = :id";

        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':nome' => $data['nome'],
            ':especialidadeId' => $data['especialidade_id'],
            ':telefone' => $data['telefone'],
            ':email' => $data['email'],
            ':endereco' => $data['endereco'],
            ':cidade' => $data['cidade'],
            ':estado' => $data['estado'],
            ':cep' => $data['cep'],
            ':rg' => $data['rg'],
            ':cpf' => $data['cpf'],
            ':cro' => $data['cro'],
            ':id' => $id
        ]);
    }

    public function delete(int $id): bool {
        $stmt = $this->conn->prepare("DELETE FROM dentistas WHERE id_dentista = :id");
        return $stmt->execute([':id' => $id]);
    }

    public function total(): int {
        $stmt = $this->conn->query("SELECT COUNT(*) AS total FROM dentistas");
        $result = $stmt->fetch();
        return (int) $result['total'];
    }

    public function getEspecialidadeNameById(int $id): string {
        $stmt = $this->conn->prepare("SELECT descricao FROM especialidades WHERE id_especialidade = :id");
        $stmt->execute([':id' => $id]);
        $result = $stmt->fetch();

        return $result ? $result['descricao'] : 'Especialidade não encontrada';
    }
}
