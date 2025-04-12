<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../models/Funcionario.php';

class FuncionarioController {
    private Funcionario $funcionarioModel;

    public function __construct() {
        $this->funcionarioModel = new Funcionario();
    }

    public function index(): void {
        $funcionarios = $this->funcionarioModel->getAll();
        include __DIR__ . '/../views/funcionarios/index.php';
    }

    public function create(): void {
        include __DIR__ . '/../views/funcionarios/create.php';
    }

    public function store(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'nome' => $_POST['nome'] ?? '',
                'cargo' => $_POST['cargo'] ?? '',
                'telefone' => $_POST['telefone'] ?? '',
                'email' => $_POST['email'] ?? '',
                'endereco' => $_POST['endereco'] ?? '',
                'cep' => $_POST['cep'] ?? '',
                'cpf' => $_POST['cpf'] ?? '',
                'rg' => $_POST['rg'] ?? '',
                'sexo' => $_POST['sexo'] ?? '',
                'cidade' => $_POST['cidade'] ?? '',
                'estado' => $_POST['estado'] ?? ''
            ];

            $this->funcionarioModel->create($data);
            header('Location: '. BASE_URL .'/funcionarios');
            exit;
        }
    }

    public function edit(int $id): void {
        $funcionario = $this->funcionarioModel->find($id);
        if ($funcionario) {
            include __DIR__ . '/../views/funcionarios/edit.php';
        } else {
            echo "Funcionário não encontrado.";
        }
    }

    public function update(int $id): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'nome' => $_POST['nome'] ?? '',
                'cargo' => $_POST['cargo'] ?? '',
                'telefone' => $_POST['telefone'] ?? '',
                'email' => $_POST['email'] ?? '',
                'endereco' => $_POST['endereco'] ?? '',
                'cep' => $_POST['cep'] ?? '',
                'cpf' => $_POST['cpf'] ?? '',
                'rg' => $_POST['rg'] ?? '',
                'sexo' => $_POST['sexo'] ?? '',
                'cidade' => $_POST['cidade'] ?? '',
                'estado' => $_POST['estado'] ?? ''
            ];

            $this->funcionarioModel->update($id, $data);
            header('Location: '. BASE_URL .'/funcionarios');
            exit;
        }
    }

    public function delete(int $id): void {
        $this->funcionarioModel->delete($id);
        header('Location: '. BASE_URL .'/funcionarios');
        exit;
    }
}
