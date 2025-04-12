<?php

require_once __DIR__ . '/../../config/Database.php';

class Funcionario {
    private PDO $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->connect();
    }

    public function getAll(): array {
        $stmt = $this->conn->query("SELECT * FROM funcionarios");
        return $stmt->fetchAll();
    }

    public function create(array $data): bool {
        $sql = "INSERT INTO funcionarios (
                    nome, cargo, telefone, email, endereco, cep, cpf, rg,
                    sexo, cidade, estado
                ) VALUES (
                    :nome, :cargo, :telefone, :email, :endereco, :cep, :cpf, :rg,
                    :sexo, :cidade, :estado
                )";

        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':nome' => $data['nome'],
            ':cargo' => $data['cargo'],
            ':telefone' => $data['telefone'],
            ':email' => $data['email'],
            ':endereco' => $data['endereco'],
            ':cep' => $data['cep'],
            ':cpf' => $data['cpf'],
            ':rg' => $data['rg'],
            ':sexo' => $data['sexo'],
            ':cidade' => $data['cidade'],
            ':estado' => $data['estado']
        ]);
    }

    public function find(int $id): array|false {
        $stmt = $this->conn->prepare("SELECT * FROM funcionarios WHERE id_funcionario = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function update(int $id, array $data): bool {
        $sql = "UPDATE funcionarios SET
                    nome = :nome,
                    cargo = :cargo,
                    telefone = :telefone,
                    email = :email,
                    endereco = :endereco,
                    cep = :cep,
                    cpf = :cpf,
                    rg = :rg,
                    sexo = :sexo,
                    cidade = :cidade,
                    estado = :estado
                WHERE id_funcionario = :id";

        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':nome' => $data['nome'],
            ':cargo' => $data['cargo'],
            ':telefone' => $data['telefone'],
            ':email' => $data['email'],
            ':endereco' => $data['endereco'],
            ':cep' => $data['cep'],
            ':cpf' => $data['cpf'],
            ':rg' => $data['rg'],
            ':sexo' => $data['sexo'],
            ':cidade' => $data['cidade'],
            ':estado' => $data['estado'],
            ':id' => $id
        ]);
    }

    public function delete(int $id): bool {
        $stmt = $this->conn->prepare("DELETE FROM funcionarios WHERE id_funcionario = :id");
        return $stmt->execute([':id' => $id]);
    }

    public function total(): int {
        $stmt = $this->conn->query("SELECT COUNT(*) AS total FROM funcionarios");
        $result = $stmt->fetch();
        return (int) $result['total'];
    }
}
