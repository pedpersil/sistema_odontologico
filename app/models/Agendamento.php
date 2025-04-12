<?php
require_once __DIR__ . '/../../config/Database.php';

class Agendamento {
    private PDO $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->connect();
    }

    public function getAll(): array {
        $stmt = $this->conn->query("SELECT * FROM agendamentos");
        return $stmt->fetchAll();
    }

    public function create(array $data): bool {
        $sql = "INSERT INTO agendamentos (paciente_id, dentista_id, data_hora, descricao)
                VALUES (:pacienteId, :dentistaId, :dataHora, :descricao)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':pacienteId' => $data['paciente_id'],
            ':dentistaId' => $data['dentista_id'],
            ':dataHora' => $data['data_hora'],
            ':descricao' => $data['descricao']
        ]);
    }

    public function find(int $id): array|false {
        $stmt = $this->conn->prepare("SELECT * FROM agendamentos WHERE id_agendamento = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function update(int $id, array $data): bool {
        $sql = "UPDATE agendamentos 
                SET paciente_id = :pacienteId, dentista_id = :dentistaId, data_hora = :dataHora, descricao = :descricao 
                WHERE id_agendamento = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':pacienteId' => $data['paciente_id'],
            ':dentistaId' => $data['dentista_id'],
            ':dataHora' => $data['data_hora'],
            ':descricao' => $data['descricao'],
            ':id' => $id
        ]);
    }

    public function delete(int $id): bool {
        $stmt = $this->conn->prepare("DELETE FROM agendamentos WHERE id_agendamento = :id");
        return $stmt->execute([':id' => $id]);
    }

    public function total(): int {
        $stmt = $this->conn->query("SELECT COUNT(*) AS total FROM agendamentos");
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

    public function getEventosParaCalendario(?string $dentistaId = null): array {
        $sql = "SELECT a.id_agendamento, a.data_hora, a.descricao, p.nome AS paciente
                FROM agendamentos a
                JOIN pacientes p ON a.paciente_id = p.id_paciente";
        
        if ($dentistaId) {
            $sql .= " WHERE a.dentista_id = :dentista_id";
        }
    
        $stmt = $this->conn->prepare($sql);
        if ($dentistaId) {
            $stmt->bindParam(':dentista_id', $dentistaId, PDO::PARAM_INT);
        }
        $stmt->execute();
    
        $eventos = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $eventos[] = [
                'id' => $row['id_agendamento'],
                'title' => $row['paciente'] . ' - ' . $row['descricao'],
                'start' => $row['data_hora'],
                'allDay' => false
            ];
        }
    
        return $eventos;
    }
    
    public function getEspecialidadeNameById(int $id): string {
        $stmt = $this->conn->prepare("SELECT descricao FROM especialidades WHERE id_especialidade = :id");
        $stmt->execute([':id' => $id]);
        $result = $stmt->fetch();
    
        return $result ? $result['descricao'] : 'Especialidade não encontrada';
    }

    public function getPacienteInfoByAgendamento(int $idAgendamento): array {
        $sql = "SELECT 
                    p.nome AS paciente,
                    p.telefone,
                    p.email,
                    d.nome AS dentista,
                    e.descricao AS especialidade,
                    a.data_hora,
                    a.descricao
                FROM agendamentos a
                JOIN pacientes p ON a.paciente_id = p.id_paciente
                JOIN dentistas d ON a.dentista_id = d.id_dentista
                JOIN especialidades e ON d.especialidade_id = e.id_especialidade
                WHERE a.id_agendamento = :id";
    
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $idAgendamento]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: [];
    }
    
    
}
