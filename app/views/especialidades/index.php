<?php 
$pageTitle = "Lista de Especialidades - Sistema Odontológico";
require_once __DIR__ . '/../layouts/header.php'; 
?>
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Especialidades</h1>
            <div class="d-flex gap-2">
                <a href="<?= BASE_URL ?>/especialidades/create" class="btn btn-outline-light btn-sm rounded-1 px-4 shadow-sm">Cadastrar Nova Especialidade</a>
            </div>
        </div>
    
    <!-- Tabela -->
    <div class="table-responsive bg-dark p-3 rounded shadow-sm">
        <table id="tabela-especialidades" class="table table-dark table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Especialidade</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($especialidades as $especialidade): ?>
                    <tr>
                        <td><?= htmlspecialchars($especialidade['id_especialidade']) ?></td>
                        <td><?= htmlspecialchars($especialidade['descricao']) ?></td>
                        <td>
                        <div class="text-center">
                                                                    
                            <a href="<?= BASE_URL ?>/especialidades/edit/<?= $especialidade['id_especialidade'] ?>" class="btn btn-sm btn-primary me-1" title="Editar">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            <a href="<?= BASE_URL ?>/especialidades/delete/<?= $especialidade['id_especialidade'] ?>" class="btn btn-sm btn-danger me-1" title="Excluir" onclick="return confirm('Tem certeza que deseja excluir este orçamento?')">
                                <i class="bi bi-trash3"></i>
                            </a>
                        </div>
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
        $('#tabela-especialidades').DataTable({
            language: {
                url: "https://cdn.datatables.net/plug-ins/1.13.6/i18n/pt-BR.json"
            }
        });
    });
    </script>
<?php 
require_once __DIR__ . '/../layouts/footer.php';
?>
