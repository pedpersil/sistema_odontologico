<?php
declare(strict_types=1);

require_once __DIR__ . '/../../config/Database.php';

class Verificar {
    private PDO $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->connect();
    }

    /**
     * Verifica se o token existe e retorna os dados associados
     *
     * @param string $token
     * @return array|null
     */
    public function verificarToken(string $token): ?array {
        $stmt = $this->conn->prepare("SELECT * FROM assinaturas WHERE token = :token LIMIT 1");
        $stmt->bindParam(':token', $token, PDO::PARAM_STR);
        $stmt->execute();

        $dados = $stmt->fetch(PDO::FETCH_ASSOC);
        return $dados ?: null;
    }
}
