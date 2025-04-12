<?php

require_once __DIR__ . '/../../config/Database.php';

class Anamnese {
    private PDO $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->connect();
    }

    public function getAll(): array {
        $stmt = $this->conn->query("SELECT * FROM anamneses");
        return $stmt->fetchAll();
    }

    public function create(array $data): bool {
        $sql = "INSERT INTO anamneses (paciente_id, dentista_id, descricao, data)
                VALUES (:pacienteId, :dentistaId, :descricao, :data)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':pacienteId' => $data['paciente_id'],
            ':dentistaId' => $data['dentista_id'],
            ':descricao' => $data['descricao'],
            ':data' => $data['data']
        ]);
    }

    public function find(int $id): array|false {
        $stmt = $this->conn->prepare("SELECT * FROM anamneses WHERE id_anamnese = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function update(int $id, array $data): bool {
        $sql = "UPDATE anamneses 
                SET paciente_id = :pacienteId, dentista_id = :dentistaId, descricao = :descricao, data = :data 
                WHERE id_anamnese = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':pacienteId' => $data['paciente_id'],
            ':dentistaId' => $data['dentista_id'],
            ':descricao' => $data['descricao'],
            ':data' => $data['data'],
            ':id' => $id
        ]);
    }

    public function delete(int $id): bool {
        $stmt = $this->conn->prepare("DELETE FROM anamneses WHERE id_anamnese = :id");
        return $stmt->execute([':id' => $id]);
    }

    public function total(): int {
        $stmt = $this->conn->query("SELECT COUNT(*) AS total FROM anamneses");
        $result = $stmt->fetch();
        return (int) $result['total'];
    }

    public function getPacienteNameById(int $id): string {
        $stmt = $this->conn->prepare("SELECT nome FROM pacientes WHERE id_paciente = :id");
        $stmt->execute([':id' => $id]);
        $result = $stmt->fetch();

        return $result ? $result['nome'] : 'Paciente não encontrado';
    }

    public function getDentistaNameById(int $id): string {
        $stmt = $this->conn->prepare("SELECT nome FROM dentistas WHERE id_dentista = :id");
        $stmt->execute([':id' => $id]);
        $result = $stmt->fetch();

        return $result ? $result['nome'] : 'Dentista não encontrado';
    }

    public function getPacienteAllInfoById(int $id): array|false {
        // 1. Buscar o orçamento para obter o paciente_id
        $stmtOrc = $this->conn->prepare("SELECT paciente_id FROM anamneses WHERE id_anamnese = :id");
        $stmtOrc->execute([':id' => $id]);
        $anamnese = $stmtOrc->fetch();
    
        if (!$anamnese || empty($anamnese['paciente_id'])) {
            return false;
        }
    
        // 2. Buscar informações completas do paciente
        $stmtPac = $this->conn->prepare("SELECT * FROM pacientes WHERE id_paciente = :paciente_id");
        $stmtPac->execute([':paciente_id' => $anamnese['paciente_id']]);
        return $stmtPac->fetch();
    }

    public function getDentistaAllInfoById(int $id): array|false {
        // 1. Buscar o orçamento para obter o dentista_id
        $stmtOrc = $this->conn->prepare("SELECT dentista_id FROM anamneses WHERE id_anamnese = :id");
        $stmtOrc->execute([':id' => $id]);
        $anamnese = $stmtOrc->fetch();
    
        if (!$anamnese || empty($anamnese['dentista_id'])) {
            return false;
        }
    
        // 2. Buscar informações completas do dentista
        $stmtDent = $this->conn->prepare("SELECT * FROM dentistas WHERE id_dentista = :dentista_id");
        $stmtDent->execute([':dentista_id' => $anamnese['dentista_id']]);
        return $stmtDent->fetch();
    }
    
    public function getEspecialidadeNameById(int $id): string {
        $stmt = $this->conn->prepare("SELECT descricao FROM especialidades WHERE id_especialidade = :id");
        $stmt->execute([':id' => $id]);
        $result = $stmt->fetch();
    
        return $result ? $result['descricao'] : 'Especialidade não encontrada';
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

    function gerarNumeroAnamnese(int $idAnamnese, int $ano): string {
        return 'ANAMNESE-' . str_pad($idAnamnese, 6, '0', STR_PAD_LEFT) . '/' . $ano;
    }
}
