<?php 
$pageTitle = "Lista de Pacientes - Sistema Odontológico";
require_once __DIR__ . '/../layouts/header.php'; 
?>

    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Pacientes</h1>
            <div class="d-flex gap-2">
                <a href="<?= BASE_URL ?>/pacientes/create" class="btn btn-outline-light btn-sm rounded-1 px-4 shadow-sm">Cadastrar Novo Paciente</a>
            </div>
        </div>

    <!-- Tabela -->
    <div class="table-responsive bg-dark p-3 rounded shadow-sm">
        <table id="tabela-pacientes" class="table table-dark table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Nascimento</th>
                    <th>Endereço</th>
                    <th>CEP</th>
                    <th>Telefone</th>
                    <th>Email</th>
                    <th>CPF</th>
                    <th>RG</th>
                    <th>Sexo</th>
                    <th>cidade</th>
                    <th>Estado</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pacientes as $paciente): ?>
                    <tr>
                        <td><?= htmlspecialchars($paciente['id_paciente']) ?></td>
                        <td><?= htmlspecialchars($paciente['nome']) ?></td>
                        <td><?= htmlspecialchars(date("d/m/Y", strtotime($paciente['data_nascimento']))) ?></td>
                        <td><?= htmlspecialchars($paciente['endereco']) ?></td>
                        <td><?= htmlspecialchars($paciente['cep']) ?></td>
                        <td><?= htmlspecialchars($paciente['telefone']) ?></td>
                        <td><?= htmlspecialchars($paciente['email']) ?></td>
                        <td><?= htmlspecialchars($paciente['cpf']) ?></td>
                        <td><?= htmlspecialchars($paciente['rg']) ?></td>
                        <td><?= htmlspecialchars($paciente['sexo']) ?></td>
                        <td><?= htmlspecialchars($paciente['cidade']) ?></td>
                        <td><?= htmlspecialchars($paciente['estado']) ?></td>
                        <td>
                                                                
                            <a href="<?= BASE_URL ?>/pacientes/edit/<?= $paciente['id_paciente'] ?>" class="btn btn-sm btn-primary me-1" title="Editar">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            <a href="<?= BASE_URL ?>/pacientes/delete/<?= $paciente['id_paciente'] ?>" class="btn btn-sm btn-danger me-1" title="Excluir" onclick="return confirm('Tem certeza que deseja excluir este orçamento?')">
                                <i class="bi bi-trash3"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
        <div class="d-flex justify-content-end mt-3">
            <a href="<?= BASE_URL ?>/dashboard" class="btn btn-outline-light btn-sm rounded-1 px-4 shadow-sm">
                Voltar para o Dashboard
            </a>
        </div>
    </div>

    <script>
    $(document).ready(function () {
        $('#tabela-pacientes').DataTable({
            "scrollX": true,
            language: {
                url: "https://cdn.datatables.net/plug-ins/1.13.6/i18n/pt-BR.json"
            }
        });
    });
    </script>

<?php 
require_once __DIR__ . '/../layouts/footer.php';
?>
