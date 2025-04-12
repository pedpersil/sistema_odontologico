<?php

require_once __DIR__ . '/../../config/Database.php';

class Paciente {
    private PDO $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->connect();
    }

    public function getAll(): array {
        $stmt = $this->conn->query("SELECT * FROM pacientes");
        return $stmt->fetchAll();
    }

    public function create(array $data): bool {
        $sql = "INSERT INTO pacientes (
                    nome, data_nascimento, endereco, telefone, email,
                    cep, cpf, rg, sexo, cidade, estado
                ) VALUES (
                    :nome, :dataNascimento, :endereco, :telefone, :email,
                    :cep, :cpf, :rg, :sexo, :cidade, :estado
                )";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':nome' => $data['nome'],
            ':dataNascimento' => $data['data_nascimento'],
            ':endereco' => $data['endereco'],
            ':telefone' => $data['telefone'],
            ':email' => $data['email'],
            ':cep' => $data['cep'],
            ':cpf' => $data['cpf'],
            ':rg' => $data['rg'],
            ':sexo' => $data['sexo'],
            ':cidade' => $data['cidade'],
            ':estado' => $data['estado']
        ]);
    }

    public function find(int $id): array|false {
        $stmt = $this->conn->prepare("SELECT * FROM pacientes WHERE id_paciente = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function update(int $id, array $data): bool {
        $sql = "UPDATE pacientes SET
                    nome = :nome,
                    data_nascimento = :dataNascimento,
                    endereco = :endereco,
                    telefone = :telefone,
                    email = :email,
                    cep = :cep,
                    cpf = :cpf,
                    rg = :rg,
                    sexo = :sexo,
                    cidade = :cidade,
                    estado = :estado
                WHERE id_paciente = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':nome' => $data['nome'],
            ':dataNascimento' => $data['data_nascimento'],
            ':endereco' => $data['endereco'],
            ':telefone' => $data['telefone'],
            ':email' => $data['email'],
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
        // Exclui dependências relacionadas ao paciente
        $this->conn->prepare("DELETE FROM agendamentos WHERE paciente_id = :id")->execute([':id' => $id]);
        $this->conn->prepare("DELETE FROM orcamentos WHERE paciente_id = :id")->execute([':id' => $id]);
        $this->conn->prepare("DELETE FROM receitas WHERE paciente_id = :id")->execute([':id' => $id]);
        $this->conn->prepare("DELETE FROM anamneses WHERE paciente_id = :id")->execute([':id' => $id]);
    
        // Agora sim, exclui o paciente
        $stmt = $this->conn->prepare("DELETE FROM pacientes WHERE id_paciente = :id");
        return $stmt->execute([':id' => $id]);
    }
    
    public function total(): int {
        $stmt = $this->conn->query("SELECT COUNT(*) AS total FROM pacientes");
        $result = $stmt->fetch();
        return (int) $result['total'];
    }

    public function getPacienteAllInfoByLancamentoId($lancamentoId): array|false {
        $sql = "SELECT p.*
                FROM pacientes p
                INNER JOIN lancamentos_contabeis l ON p.id_paciente = l.paciente_id
                WHERE l.id_lancamento = :lancamento_id
                LIMIT 1";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':lancamento_id', $lancamentoId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

}
