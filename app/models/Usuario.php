<?php

require_once __DIR__ . '/../../config/Database.php';

class Usuario {
    private PDO $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->connect();
    }

    public function create(array $data): bool {
        $sql = "INSERT INTO usuarios (nome, email, senha, tipo, criado_em, atualizado_em)
                VALUES (:nome, :email, :senha, :tipo, NOW(), NOW())";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':nome' => $data['nome'],
            ':email' => $data['email'],
            ':senha' => password_hash($data['senha'], PASSWORD_DEFAULT),
            ':tipo' => $data['tipo'],
        ]);
    }

    public function changePassword(int $id, string $newPassword): bool {
        $sql = "UPDATE usuarios SET senha = :senha, atualizado_em = NOW() WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':senha' => password_hash($newPassword, PASSWORD_DEFAULT),
            ':id' => $id
        ]);
    }

    public function verifyPassword(int $id, string $password): bool {
        $stmt = $this->conn->prepare("SELECT senha FROM usuarios WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
        return $usuario && password_verify($password, $usuario['senha']);
    }
}
