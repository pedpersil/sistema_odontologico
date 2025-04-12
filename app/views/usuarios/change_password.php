<?php 
$pageTitle = "Alterar Senha - Sistema Odontológico";
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

    <h1 class="text-center mt-4">Alterar Senha</h1>

    <div class="form-container">
        <form action="<?= BASE_URL ?>/usuarios/change-password" method="POST">
            <div class="mb-3">
                <label for="senha_atual" class="form-label">Senha Atual:</label>
                <input type="password" name="senha_atual" id="senha_atual" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="nova_senha" class="form-label">Nova Senha:</label>
                <input type="password" name="nova_senha" id="nova_senha" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-custom w-100">Alterar Senha</button>
        </form>

        <div class="text-center mt-3">
            <a href="<?= BASE_URL ?>" class="btn btn-outline-light btn-sm rounded-1 px-4 shadow-sm">← Voltar para Início</a>
        </div>
    </div>
</div>

<?php 
require_once __DIR__ . '/../layouts/footer.php';
?>
