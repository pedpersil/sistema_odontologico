<?php 
$pageTitle = "Editar Paciente - Sistema Odontológico";
require_once __DIR__ . '/../layouts/header.php'; 
?>
<div class="container">
    <h1 class="text-center mt-4">Editar Paciente</h1>

    <div class="form-container">
        <form action="<?= BASE_URL ?>/pacientes/update/<?= $paciente['id_paciente'] ?>" method="POST">
            <div class="mb-3">
                <label for="nome" class="form-label">Nome:</label>
                <input type="text" class="form-control" name="nome" id="nome" value="<?= htmlspecialchars($paciente['nome']) ?>" required>
            </div>

            <div class="mb-3">
                <label for="data_nascimento" class="form-label">Data de Nascimento:</label>
                <input type="date" class="form-control" name="data_nascimento" id="data_nascimento" value="<?= htmlspecialchars($paciente['data_nascimento']) ?>" required>
            </div>

            <div class="mb-3">
                <label for="sexo" class="form-label">Sexo:</label>
                <select class="form-control" name="sexo" id="sexo" required>
                    <option value="">Selecione</option>
                    <option value="M" <?= $paciente['sexo'] === 'M' ? 'selected' : '' ?>>Masculino</option>
                    <option value="F" <?= $paciente['sexo'] === 'F' ? 'selected' : '' ?>>Feminino</option>
                    <option value="Outro" <?= $paciente['sexo'] === 'Outro' ? 'selected' : '' ?>>Outro</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="cpf" class="form-label">CPF:</label>
                <input type="text" class="form-control" name="cpf" id="cpf" value="<?= htmlspecialchars($paciente['cpf']) ?>" required>
            </div>

            <div class="mb-3">
                <label for="rg" class="form-label">RG:</label>
                <input type="text" class="form-control" name="rg" id="rg" value="<?= htmlspecialchars($paciente['rg']) ?>" required>
            </div>

            <div class="mb-3">
                <label for="cep" class="form-label">CEP:</label>
                <input type="text" class="form-control" name="cep" id="cep" value="<?= htmlspecialchars($paciente['cep']) ?>" required>
            </div>

            <div class="mb-3">
                <label for="endereco" class="form-label">Endereço:</label>
                <input type="text" class="form-control" name="endereco" id="endereco" value="<?= htmlspecialchars($paciente['endereco']) ?>" required>
            </div>

            <div class="mb-3">
                <label for="cidade" class="form-label">Cidade:</label>
                <input type="text" class="form-control" name="cidade" id="cidade" value="<?= htmlspecialchars($paciente['cidade']) ?>" required>
            </div>

            <div class="mb-3">
                <label for="estado" class="form-label">Estado (UF):</label>
                <input type="text" class="form-control" name="estado" id="estado" value="<?= htmlspecialchars($paciente['estado']) ?>" maxlength="2" required>
            </div>

            <div class="mb-3">
                <label for="telefone" class="form-label">Telefone:</label>
                <input type="text" class="form-control" name="telefone" id="telefone" value="<?= htmlspecialchars($paciente['telefone']) ?>" required>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email:</label>
                <input type="email" class="form-control" name="email" id="email" value="<?= htmlspecialchars($paciente['email']) ?>" required>
            </div>

            <button type="submit" class="btn btn-custom w-100">Atualizar</button>
        </form>

        <div class="text-center mt-3">
            <a href="<?= BASE_URL ?>/pacientes" class="btn btn-outline-light btn-sm rounded-1 px-4 shadow-sm">Voltar para lista</a>
        </div>
    </div>
</div>

<?php 
require_once __DIR__ . '/../layouts/footer.php';
?>
