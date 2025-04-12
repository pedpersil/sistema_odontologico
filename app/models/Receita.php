<?php
require_once __DIR__ . '/../../config/Database.php';

class Receita {
    private PDO $conn;

    public function __construct() {
        $this->conn = (new Database())->connect();
    }

    public function getAll(): array {
        $sql = "SELECT r.*, p.nome AS paciente, d.nome AS dentista
                FROM receitas r
                JOIN pacientes p ON r.paciente_id = p.id_paciente
                JOIN dentistas d ON r.dentista_id = d.id_dentista
                ORDER BY r.data DESC";
        return $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create(array $data): bool {
        $sql = "INSERT INTO receitas (paciente_id, dentista_id, data, conteudo)
                VALUES (:paciente_id, :dentista_id, :data, :conteudo)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute($data);
    }

    public function find(int $id): array|false {
        $stmt = $this->conn->prepare("SELECT * FROM receitas WHERE id_receita = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update(int $id, array $data): bool {
        $sql = "UPDATE receitas SET paciente_id = :paciente_id, dentista_id = :dentista_id, data = :data, conteudo = :conteudo WHERE id_receita = :id";
        $stmt = $this->conn->prepare($sql);
        $data['id'] = $id;
        return $stmt->execute($data);
    }

    public function delete(int $id): bool {
        $stmt = $this->conn->prepare("DELETE FROM receitas WHERE id_receita = :id");
        return $stmt->execute([':id' => $id]);
    }

    public function assinar(string $token, string $tipoRelatorio, array $dadosRelatorio): array|false {
        // Verifica se o token já existe
        $stmtCheck = $this->conn->prepare("SELECT token, tipo_relatorio, dados_relatorio FROM assinaturas WHERE token = :token");
        $stmtCheck->execute([':token' => $token]);
        $assinaturaExistente = $stmtCheck->fetch(PDO::FETCH_ASSOC);
    
        if ($assinaturaExistente) {
            // Retorna os dados já existentes
            return $assinaturaExistente;
        }
    
        // Insere nova assinatura
        $sql = "INSERT INTO assinaturas (token, tipo_relatorio, dados_relatorio) 
                VALUES (:token, :tipoRelatorio, :dadosRelatorio)";
        
        $stmt = $this->conn->prepare($sql);
        $sucesso = $stmt->execute([
            ':token' => $token,
            ':tipoRelatorio' => $tipoRelatorio,
            ':dadosRelatorio' => json_encode($dadosRelatorio, JSON_UNESCAPED_UNICODE)
        ]);
    
        // Se inserção for bem-sucedida, retorna os dados assinados
        if ($sucesso) {
            return [
                'token' => $token,
                'tipo_relatorio' => $tipoRelatorio,
                'dados_relatorio' => json_encode($dadosRelatorio, JSON_UNESCAPED_UNICODE)
            ];
        }
    
        return false; // Falha na inserção
    }


}
