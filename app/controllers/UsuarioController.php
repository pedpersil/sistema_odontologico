<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../models/Usuario.php';

class UsuarioController {
    private Usuario $model;

    public function __construct() {
        $this->model = new Usuario();
    }

    public function create(): void {
        $this->authorize('admin');
        include __DIR__ . '/../views/usuarios/create.php';
    }

    public function store(): void {
        $this->authorize('admin');
    
        if (session_status() === PHP_SESSION_NONE) session_start();
    
        $data = [
            'nome' => $_POST['nome'] ?? '',
            'email' => $_POST['email'] ?? '',
            'senha' => $_POST['senha'] ?? '',
            'tipo' => $_POST['tipo'] ?? '',
        ];
    
        if ($this->model->create($data)) {
            $_SESSION['success'] = 'Usuário criado com sucesso!';
            header('Location: ' . BASE_URL . '/usuarios/create');
            exit;
        } else {
            $_SESSION['error'] = 'Erro ao criar o usuário. Tente novamente.';
            header('Location: ' . BASE_URL . '/usuarios/create');
            exit;
        }
    }
    

    public function changePasswordForm(): void {
        include __DIR__ . '/../views/usuarios/change_password.php';
    }

    public function changePassword(): void {
        $id = $_SESSION[SESSION_NAME]['id'];
        $senhaAtual = $_POST['senha_atual'];
        $novaSenha = $_POST['nova_senha'];

        if ($this->model->verifyPassword($id, $senhaAtual)) {
            $this->model->changePassword($id, $novaSenha);
            $_SESSION['success'] = 'Senha alterada com sucesso!';
            header('Location: ' . BASE_URL . '/usuarios/change-password');
        } else {
            $_SESSION['error'] = "Senha atual incorreta.";
            header('Location: ' . BASE_URL . '/usuarios/change-password');
        }
    }

    private function authorize(string $tipo): void {
        if ($_SESSION[SESSION_NAME]['type'] !== $tipo) {
            header('Location: ' . BASE_URL . '/dashboard');
            exit;
        }
    }
}
