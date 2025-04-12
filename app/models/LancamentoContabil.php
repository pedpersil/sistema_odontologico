<?php

require_once __DIR__ . '/../../config/Database.php';

class LancamentoContabil {
    private PDO $conn;
    
    public function __construct() {
        $this->conn = (new Database())->connect();
    }

    public function getAll(): array {
        $stmt = $this->conn->query("SELECT * FROM lancamentos_contabeis ORDER BY data_lancamento DESC");
        return $stmt->fetchAll();
    }

    public function find(int $id): array {
        // Prepara a consulta SQL para buscar um lançamento pelo ID
        $sql = "SELECT * FROM lancamentos_contabeis WHERE id_lancamento = :id_lancamento LIMIT 1";
        
        // Prepara a consulta no banco de dados
        $stmt = $this->conn->prepare($sql);
        
        // Executa a consulta com o parâmetro id
        $stmt->execute([':id_lancamento' => $id]);
        
        // Retorna o resultado como um array associativo
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: [];
    }
    

    public function create(array $data): bool {
        $sql = "INSERT INTO lancamentos_contabeis (
                    paciente_id, orcamento_id, data_lancamento, descricao, tipo, valor, categoria
                ) VALUES (
                    :paciente_id, :orcamento_id, :data_lancamento, :descricao, :tipo, :valor, :categoria
                )";

        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':paciente_id' => $data['paciente_id'],
            ':orcamento_id' => $data['orcamento_id'] ?? null,
            ':data_lancamento' => $data['data_lancamento'],
            ':descricao' => $data['descricao'],
            ':tipo' => $data['tipo'],
            ':valor' => $data['valor'],
            ':categoria' => $data['categoria']
        ]);
    }

    public function update(int $id, array $data): bool {
        // SQL de atualização
        $sql = "UPDATE lancamentos_contabeis 
                SET paciente_id = :paciente_id, 
                    orcamento_id = :orcamento_id, 
                    data_lancamento = :data_lancamento, 
                    descricao = :descricao, 
                    tipo = :tipo, 
                    valor = :valor, 
                    categoria = :categoria 
                WHERE id_lancamento = :id_lancamento";
        
        // Prepara a query
        $stmt = $this->conn->prepare($sql);
        
        // Executa a query com os dados e o ID do lançamento
        return $stmt->execute([
            ':id_lancamento' => $id,
            ':paciente_id' => $data['paciente_id'],
            ':orcamento_id' => $data['orcamento_id'],
            ':data_lancamento' => $data['data_lancamento'],
            ':descricao' => $data['descricao'],
            ':tipo' => $data['tipo'],
            ':valor' => $data['valor'],
            ':categoria' => $data['categoria']
        ]);
    }
    
    public function delete(int $id): bool {
        $stmt = $this->conn->prepare("DELETE FROM lancamentos_contabeis WHERE id_lancamento = :id");
        return $stmt->execute([':id' => $id]);
    }

    public function getByPaciente(int $pacienteId, ?string $dataInicio = null, ?string $dataFim = null): array {
        $sql = "SELECT * FROM lancamentos_contabeis WHERE paciente_id = :id";
        $params = [':id' => $pacienteId];
    
        if ($dataInicio && $dataFim) {
            $sql .= " AND data_lancamento BETWEEN :dataInicio AND :dataFim";
            $params[':dataInicio'] = $dataInicio;
            $params[':dataFim'] = $dataFim;
        }
    
        $sql .= " ORDER BY data_lancamento DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
    
    public function getSaldoPaciente(int $pacienteId, ?string $dataInicio = null, ?string $dataFim = null): float {
        $sql = "SELECT 
                    SUM(CASE WHEN tipo = 'credito' THEN valor ELSE -valor END) AS saldo
                FROM lancamentos_contabeis
                WHERE paciente_id = :id";
        $params = [':id' => $pacienteId];
    
        if ($dataInicio && $dataFim) {
            $sql .= " AND data_lancamento BETWEEN :dataInicio AND :dataFim";
            $params[':dataInicio'] = $dataInicio;
            $params[':dataFim'] = $dataFim;
        }
    
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetch();
        return (float) ($result['saldo'] ?? 0);
    }
    
    public function getAllFiltrado(?string $dataInicio = null, ?string $dataFim = null): array {
        $sql = "SELECT * FROM lancamentos_contabeis WHERE 1";
        $params = [];
    
        if ($dataInicio && $dataFim) {
            $sql .= " AND data_lancamento BETWEEN :dataInicio AND :dataFim";
            $params[':dataInicio'] = $dataInicio;
            $params[':dataFim'] = $dataFim;
        }
    
        $sql .= " ORDER BY data_lancamento DESC";
    
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function getResumoPorPaciente(string $dataInicio, string $dataFim): array {
        $sql = "
            SELECT 
                lc.paciente_id,
                p.nome AS paciente_nome,
                SUM(CASE WHEN lc.tipo = 'credito' THEN lc.valor ELSE 0 END) AS total_credito,
                SUM(CASE WHEN lc.tipo = 'debito' THEN lc.valor ELSE 0 END) AS total_debito,
                SUM(CASE WHEN lc.tipo = 'credito' THEN lc.valor ELSE -lc.valor END) AS saldo
            FROM lancamentos_contabeis lc
            JOIN pacientes p ON lc.paciente_id = p.id_paciente
            WHERE lc.data_lancamento BETWEEN :dataInicio AND :dataFim
            GROUP BY lc.paciente_id
            ORDER BY p.nome ASC
        ";
    
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':dataInicio' => $dataInicio,
            ':dataFim' => $dataFim
        ]);
        return $stmt->fetchAll();
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
