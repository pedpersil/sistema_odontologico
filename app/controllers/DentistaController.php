<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../models/Dentista.php';

class DentistaController {
    private Dentista $dentistaModel;

    public function __construct() {
        $this->dentistaModel = new Dentista();
    }

    public function index(): void {
        $dentistas = $this->dentistaModel->getAll();
        include __DIR__ . '/../views/dentistas/index.php';
    }

    public function create(): void {
        include __DIR__ . '/../views/dentistas/create.php';
    }

    public function store(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'nome' => $_POST['nome'] ?? '',
                'especialidade_id' => $_POST['especialidade_id'] ?? '',
                'telefone' => $_POST['telefone'] ?? '',
                'email' => $_POST['email'] ?? '',
                'endereco' => $_POST['endereco'] ?? '',
                'cidade' => $_POST['cidade'] ?? '',
                'estado' => $_POST['estado'] ?? '',
                'cep' => $_POST['cep'] ?? '',
                'rg' => $_POST['rg'] ?? '',
                'cpf' => $_POST['cpf'] ?? '',
                'cro' => $_POST['cro'] ?? ''
            ];
            $this->dentistaModel->create($data);
            header('Location: ' . BASE_URL . '/dentistas');
            exit;
        }
    }

    public function edit(int $id): void {
        $dentista = $this->dentistaModel->find($id);
        if ($dentista) {
            include __DIR__ . '/../views/dentistas/edit.php';
        } else {
            echo "Dentista não encontrado.";
        }
    }

    public function update(int $id): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'nome' => $_POST['nome'] ?? '',
                'especialidade_id' => $_POST['especialidade_id'] ?? '',
                'telefone' => $_POST['telefone'] ?? '',
                'email' => $_POST['email'] ?? '',
                'endereco' => $_POST['endereco'] ?? '',
                'cidade' => $_POST['cidade'] ?? '',
                'estado' => $_POST['estado'] ?? '',
                'cep' => $_POST['cep'] ?? '',
                'rg' => $_POST['rg'] ?? '',
                'cpf' => $_POST['cpf'] ?? '',
                'cro' => $_POST['cro'] ?? ''
            ];
            $this->dentistaModel->update($id, $data);
            header('Location: ' . BASE_URL . '/dentistas');
            exit;
        }
    }

    public function delete(int $id): void {
        $this->dentistaModel->delete($id);
        header('Location: ' . BASE_URL . '/dentistas');
        exit;
    }
}
