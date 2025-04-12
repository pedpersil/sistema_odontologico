<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../models/Agendamento.php';

class AgendamentoController {
    private Agendamento $agendamentoModel;

    public function __construct() {
        $this->agendamentoModel = new Agendamento();
    }

    public function index(): void {
        $agendamentos = $this->agendamentoModel->getAll();
        include __DIR__ . '/../views/agendamentos/index.php';
    }

    public function create(): void {
        include __DIR__ . '/../views/agendamentos/create.php';
    }

    public function store(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'paciente_id' => $_POST['paciente_id'] ?? '',
                'dentista_id' => $_POST['dentista_id'] ?? '',
                'data_hora' => $_POST['data_hora'] ?? '',
                'descricao' => $_POST['descricao'] ?? ''
            ];
            $this->agendamentoModel->create($data);
            header('Location: '. BASE_URL .'/agendamentos');
            exit;
        }
    }

    public function edit(int $id): void {
        $agendamento = $this->agendamentoModel->find($id);
        if ($agendamento) {
            include __DIR__ . '/../views/agendamentos/edit.php';
        } else {
            echo "Agendamento não encontrado.";
        }
    }

    public function update(int $id): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'paciente_id' => $_POST['paciente_id'] ?? '',
                'dentista_id' => $_POST['dentista_id'] ?? '',
                'data_hora' => $_POST['data_hora'] ?? '',
                'descricao' => $_POST['descricao'] ?? ''
            ];
            $this->agendamentoModel->update($id, $data);
            header('Location: '. BASE_URL .'/agendamentos');
            exit;
        }
    }

    public function delete(int $id): void {
        $this->agendamentoModel->delete($id);
        header('Location: '. BASE_URL .'/agendamentos');
        exit;
    }

    public function calendario(): void {
        require __DIR__ . '/../views/agendamentos/calendario.php';
    }

    public function getEventosJson(): array {
        $model = new Agendamento();
        $dentistaId = $_GET['dentista_id'] ?? null;
        return $model->getEventosParaCalendario($dentistaId);
    }
    
    public function getPacienteByAgendamentoId(int $idAgendamento): array {
        $model = new Agendamento();
        return $model->getPacienteInfoByAgendamento($idAgendamento);
    }
    
}
