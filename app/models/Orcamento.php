<?php

require_once __DIR__ . '/../../config/Database.php';

class Orcamento {
    private PDO $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->connect();
    }

    public function getAll(): array {
        $stmt = $this->conn->query("SELECT * FROM orcamentos");
        return $stmt->fetchAll();
    }

    public function create(array $data): bool|string {
        // Buscar a última anamnese do paciente
        $stmtAnamnese = $this->conn->prepare("
            SELECT id_anamnese 
            FROM anamneses 
            WHERE paciente_id = :paciente_id 
            ORDER BY data DESC, id_anamnese DESC 
            LIMIT 1
        ");
        $stmtAnamnese->execute([':paciente_id' => $data['paciente_id']]);
        $anamnese = $stmtAnamnese->fetch();
    
        // Se não encontrou anamnese, retorna mensagem
        if (!$anamnese || empty($anamnese['id_anamnese'])) {
            return "Erro: Nenhuma anamnese encontrada para este paciente.";
        }
    
        $anamneseId = $anamnese['id_anamnese'];
    
        $sql = "INSERT INTO orcamentos (
                    anamnese_id, paciente_id, dentista_id, descricao_servico, valor, data
                ) VALUES (
                    :anamneseId, :pacienteId, :dentistaId, :descricaoServico, :valor, :data
                )";
    
        $stmt = $this->conn->prepare($sql);
        $success = $stmt->execute([
            ':anamneseId' => $anamneseId,
            ':pacienteId' => $data['paciente_id'],
            ':dentistaId' => $data['dentista_id'],
            ':descricaoServico' => $data['descricao_servico'],
            ':valor' => $data['valor'],
            ':data' => $data['data']
        ]);
    
        return $success;
    }
    

    public function find(int $id): array|false {
        $stmt = $this->conn->prepare("SELECT * FROM orcamentos WHERE id_orcamento = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function update(int $id, array $data): bool {
        $sql = "UPDATE orcamentos 
                SET paciente_id = :pacienteId,
                    dentista_id = :dentistaId,
                    descricao_servico = :descricaoServico,
                    valor = :valor,
                    data = :data 
                WHERE id_orcamento = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':pacienteId' => $data['paciente_id'],
            ':dentistaId' => $data['dentista_id'],
            ':descricaoServico' => $data['descricao_servico'],
            ':valor' => $data['valor'],
            ':data' => $data['data'],
            ':id' => $id
        ]);
    }

    public function delete(int $id): bool {
        $stmt = $this->conn->prepare("DELETE FROM orcamentos WHERE id_orcamento = :id");
        return $stmt->execute([':id' => $id]);
    }

    public function total(): int {
        $stmt = $this->conn->query("SELECT COUNT(*) AS total FROM orcamentos");
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
        $stmtOrc = $this->conn->prepare("SELECT paciente_id FROM orcamentos WHERE id_orcamento = :id");
        $stmtOrc->execute([':id' => $id]);
        $orcamento = $stmtOrc->fetch();
    
        if (!$orcamento || empty($orcamento['paciente_id'])) {
            return false;
        }
    
        $stmtPac = $this->conn->prepare("SELECT * FROM pacientes WHERE id_paciente = :paciente_id");
        $stmtPac->execute([':paciente_id' => $orcamento['paciente_id']]);
        return $stmtPac->fetch();
    }

    public function getDentistaAllInfoById(int $id): array|false {
        $stmtOrc = $this->conn->prepare("SELECT dentista_id FROM orcamentos WHERE id_orcamento = :id");
        $stmtOrc->execute([':id' => $id]);
        $orcamento = $stmtOrc->fetch();
    
        if (!$orcamento || empty($orcamento['dentista_id'])) {
            return false;
        }
    
        $stmtDent = $this->conn->prepare("SELECT * FROM dentistas WHERE id_dentista = :dentista_id");
        $stmtDent->execute([':dentista_id' => $orcamento['dentista_id']]);
        return $stmtDent->fetch();
    }

    public function getEspecialidadeNameById(int $id): string {
        $stmt = $this->conn->prepare("SELECT descricao FROM especialidades WHERE id_especialidade = :id");
        $stmt->execute([':id' => $id]);
        $result = $stmt->fetch();
    
        return $result ? $result['descricao'] : 'Especialidade não encontrada';
    }

    function gerarNumeroOrcamento(int $idOrcamento): string {
        $ano = date('Y');
        return 'ORÇAMENTO-' . str_pad($idOrcamento, 6, '0', STR_PAD_LEFT) . '/' . $ano;
    }

    public function assinar(string $token, string $tipoRelatorio, array $dadosRelatorio): array|false {
        $stmtCheck = $this->conn->prepare("SELECT token, tipo_relatorio, dados_relatorio FROM assinaturas WHERE token = :token");
        $stmtCheck->execute([':token' => $token]);
        $assinaturaExistente = $stmtCheck->fetch(PDO::FETCH_ASSOC);
    
        if ($assinaturaExistente) {
            return $assinaturaExistente;
        }
    
        $sql = "INSERT INTO assinaturas (token, tipo_relatorio, dados_relatorio) 
                VALUES (:token, :tipoRelatorio, :dadosRelatorio)";
        
        $stmt = $this->conn->prepare($sql);
        $sucesso = $stmt->execute([
            ':token' => $token,
            ':tipoRelatorio' => $tipoRelatorio,
            ':dadosRelatorio' => json_encode($dadosRelatorio, JSON_UNESCAPED_UNICODE)
        ]);
    
        if ($sucesso) {
            return [
                'token' => $token,
                'tipo_relatorio' => $tipoRelatorio,
                'dados_relatorio' => json_encode($dadosRelatorio, JSON_UNESCAPED_UNICODE)
            ];
        }
    
        return false;
    }
}
