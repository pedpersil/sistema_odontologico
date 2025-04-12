<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../models/Paciente.php';

class PacienteController {
    private Paciente $pacienteModel;

    public function __construct() {
        $this->pacienteModel = new Paciente();
    }

    public function index(): void {
        $pacientes = $this->pacienteModel->getAll();
        include __DIR__ . '/../views/pacientes/index.php';
    }

    public function create(): void {
        include __DIR__ . '/../views/pacientes/create.php';
    }

    public function store(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'nome' => $_POST['nome'] ?? '',
                'data_nascimento' => $_POST['data_nascimento'] ?? '',
                'endereco' => $_POST['endereco'] ?? '',
                'telefone' => $_POST['telefone'] ?? '',
                'email' => $_POST['email'] ?? '',
                'cep' => $_POST['cep'] ?? '',
                'cpf' => $_POST['cpf'] ?? '',
                'rg' => $_POST['rg'] ?? '',
                'sexo' => $_POST['sexo'] ?? '',
                'cidade' => $_POST['cidade'] ?? '',
                'estado' => $_POST['estado'] ?? ''
            ];
            $this->pacienteModel->create($data);
            header('Location: '. BASE_URL .'/pacientes');
            exit;
        }
    }

    public function edit(int $id): void {
        $paciente = $this->pacienteModel->find($id);
        if ($paciente) {
            include __DIR__ . '/../views/pacientes/edit.php';
        } else {
            echo "Paciente não encontrado.";
        }
    }

    public function update(int $id): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'nome' => $_POST['nome'] ?? '',
                'data_nascimento' => $_POST['data_nascimento'] ?? '',
                'endereco' => $_POST['endereco'] ?? '',
                'telefone' => $_POST['telefone'] ?? '',
                'email' => $_POST['email'] ?? '',
                'cep' => $_POST['cep'] ?? '',
                'cpf' => $_POST['cpf'] ?? '',
                'rg' => $_POST['rg'] ?? '',
                'sexo' => $_POST['sexo'] ?? '',
                'cidade' => $_POST['cidade'] ?? '',
                'estado' => $_POST['estado'] ?? ''
            ];
            $this->pacienteModel->update($id, $data);
            header('Location: '. BASE_URL .'/pacientes');
            exit;
        }
    }

    public function delete(int $id): void {
        $this->pacienteModel->delete($id);
        header('Location: '. BASE_URL .'/pacientes');
        exit;
    }
}
