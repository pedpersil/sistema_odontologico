<?php
// Conexão com o banco de dados
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/Database.php';

$database = new Database();
$conn = $database->connect();

if (isset($_GET['query'])) {
    $query = $_GET['query']; // Obtém a string de busca

    // Evitar SQL Injection usando prepared statements
    $stmt = $conn->prepare("SELECT id_paciente, nome FROM pacientes WHERE nome LIKE :query LIMIT 5");
    $stmt->execute([':query' => '%' . $query . '%']);
    
    // Verifica se existem resultados e exibe
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if ($result) {
        foreach ($result as $row) {
            // Cada item da lista é um link que chama a função selectPatient passando o id_paciente e nome
            echo '<a href="javascript:void(0);" class="list-group-item list-group-item-action" onclick="selectPatient(' . $row['id_paciente'] . ', \'' . htmlspecialchars($row['nome']) . '\')">' . htmlspecialchars($row['nome']) . '</a>';
        }
    } else {
        echo '<a href="javascript:void(0);" class="list-group-item list-group-item-action">Nenhum resultado encontrado.</a>';
    }
}
?>
