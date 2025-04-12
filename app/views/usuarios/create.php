<?php 
$pageTitle = "Criar Novo Usuário - Sistema Odontológico";
require_once __DIR__ . '/../layouts/header.php'; 

if (session_status() === PHP_SESSION_NONE) session_start();
?>

<div class="container">
    <?php if (!empty($_SESSION['success'])): ?>
        <div class="alert alert-success text-center">
            <?= htmlspecialchars($_SESSION['success']) ?>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (!empty($_SESSION['error'])): ?>
        <div class="alert alert-danger text-center">
            <?= htmlspecialchars($_SESSION['error']) ?>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <h1 class="text-center mt-4">Criar Novo Usuário</h1>

    <div class="form-container">
        <form action="<?= BASE_URL ?>/usuarios/store" method="POST">
            <div class="mb-3">
                <label for="nome" class="form-label">Nome:</label>
                <input type="text" name="nome" id="nome" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email:</label>
                <input type="email" name="email" id="email" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="senha" class="form-label">Senha:</label>
                <input type="password" name="senha" id="senha" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="tipo" class="form-label">Tipo de Usuário:</label>
                <select name="tipo" id="tipo" class="form-control" required>
                    <option value="admin">Admin</option>
                    <option value="dentista">Dentista</option>
                    <option value="funcionario">Funcionário</option>
                </select>
            </div>

            <button type="submit" class="btn btn-custom w-100">Criar Usuário</button>
        </form>

        <div class="text-center mt-3">
            <a href="<?= BASE_URL ?>/usuarios" class="btn btn-outline-light btn-sm rounded-1 px-4 shadow-sm">← Voltar para lista</a>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
