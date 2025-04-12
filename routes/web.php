<?php

require_once __DIR__ . '/../config/Database.php';

// Controllers
require_once __DIR__ . '/../app/controllers/PacienteController.php';
require_once __DIR__ . '/../app/controllers/DentistaController.php';
require_once __DIR__ . '/../app/controllers/FuncionarioController.php';
require_once __DIR__ . '/../app/controllers/EspecialidadeController.php';
require_once __DIR__ . '/../app/controllers/AnamneseController.php';
require_once __DIR__ . '/../app/controllers/OrcamentoController.php';
require_once __DIR__ . '/../app/controllers/AgendamentoController.php';
require_once __DIR__ . '/../app/controllers/AuthController.php';
require_once __DIR__ . '/../app/controllers/VerificarController.php';
require_once __DIR__ . '/../app/controllers/ReceitaController.php';
require_once __DIR__ . '/../app/controllers/LancamentoController.php';
require_once __DIR__ . '/../app/controllers/UsuarioController.php';

// Conexão com o banco
$db = (new Database())->connect();

// Inicializar Controllers com conexão
$pacienteController = new PacienteController();
$dentistaController = new DentistaController();
$funcionarioController = new FuncionarioController();
$especialidadeController = new EspecialidadeController();
$anamneseController = new AnamneseController();
$orcamentoController = new OrcamentoController();
$agendamentoController = new AgendamentoController();
$authController = new AuthController();
$verificarController = new VerificarController();
$receitaController = new ReceitaController();
$lancamentoController = new LancamentoController();
$usuarioController = new UsuarioController();

// URI e método
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$basePath = '/sistema_odontologico/public'; // ajuste conforme sua pasta
$uri = str_replace($basePath, '', $uri);

$method = $_SERVER['REQUEST_METHOD'];

if ($uri == '/') {
    require __DIR__ . '/../app/views/dashboard.php';
}

// Verificar Token
elseif (preg_match('/\/verificar\/([a-zA-Z0-9]+)/', $uri, $matches)) {
    $verificarController->verificar($matches[1]);
}

// Login
elseif ($uri == '/login' && $method == 'GET') {
    $authController->showLoginForm();
} elseif ($uri == '/login' && $method == 'POST') {
    $authController->login();
} elseif ($uri == '/logout') {
    $authController->logout();
}

// Pacientes
elseif ($uri == '/pacientes') {
    $pacienteController->index();
} elseif ($uri == '/pacientes/create') {
    $pacienteController->create();
} elseif ($uri == '/pacientes/store' && $method == 'POST') {
    $pacienteController->store();
} elseif (preg_match('/\/pacientes\/edit\/(\d+)/', $uri, $matches)) {
    $pacienteController->edit($matches[1]);
} elseif (preg_match('/\/pacientes\/update\/(\d+)/', $uri, $matches) && $method == 'POST') {
    $pacienteController->update($matches[1]);
} elseif (preg_match('/\/pacientes\/delete\/(\d+)/', $uri, $matches)) {
    $pacienteController->delete($matches[1]);
}

// Dentistas
elseif ($uri == '/dentistas') {
    $dentistaController->index();
} elseif ($uri == '/dentistas/create') {
    $dentistaController->create();
} elseif ($uri == '/dentistas/store' && $method == 'POST') {
    $dentistaController->store();
} elseif (preg_match('/\/dentistas\/edit\/(\d+)/', $uri, $matches)) {
    $dentistaController->edit($matches[1]);
} elseif (preg_match('/\/dentistas\/update\/(\d+)/', $uri, $matches) && $method == 'POST') {
    $dentistaController->update($matches[1]);
} elseif (preg_match('/\/dentistas\/delete\/(\d+)/', $uri, $matches)) {
    $dentistaController->delete($matches[1]);
}

// Funcionários
elseif ($uri == '/funcionarios') {
    $funcionarioController->index();
} elseif ($uri == '/funcionarios/create') {
    $funcionarioController->create();
} elseif ($uri == '/funcionarios/store' && $method == 'POST') {
    $funcionarioController->store();
} elseif (preg_match('/\/funcionarios\/edit\/(\d+)/', $uri, $matches)) {
    $funcionarioController->edit($matches[1]);
} elseif (preg_match('/\/funcionarios\/update\/(\d+)/', $uri, $matches) && $method == 'POST') {
    $funcionarioController->update($matches[1]);
} elseif (preg_match('/\/funcionarios\/delete\/(\d+)/', $uri, $matches)) {
    $funcionarioController->delete($matches[1]);
}

// Especialidades
elseif ($uri == '/especialidades') {
    $especialidadeController->index();
} elseif ($uri == '/especialidades/create') {
    $especialidadeController->create();
} elseif ($uri == '/especialidades/store' && $method == 'POST') {
    $especialidadeController->store();
} elseif (preg_match('/\/especialidades\/edit\/(\d+)/', $uri, $matches)) {
    $especialidadeController->edit($matches[1]);
} elseif (preg_match('/\/especialidades\/update\/(\d+)/', $uri, $matches) && $method == 'POST') {
    $especialidadeController->update($matches[1]);
} elseif (preg_match('/\/especialidades\/delete\/(\d+)/', $uri, $matches)) {
    $especialidadeController->delete($matches[1]);
}

// Anamneses
elseif ($uri == '/anamneses') {
    $anamneseController->index();
} elseif ($uri == '/anamneses/create') {
    $anamneseController->create();
} elseif ($uri == '/anamneses/store' && $method == 'POST') {
    $anamneseController->store();
} elseif (preg_match('/\/anamneses\/edit\/(\d+)/', $uri, $matches)) {
    $anamneseController->edit($matches[1]);
} elseif (preg_match('/\/anamneses\/update\/(\d+)/', $uri, $matches) && $method == 'POST') {
    $anamneseController->update($matches[1]);
} elseif (preg_match('/\/anamneses\/delete\/(\d+)/', $uri, $matches)) {
    $anamneseController->delete($matches[1]);
} elseif (preg_match('/\/anamneses\/relatorio\/(\d+)/', $uri, $matches)) {
    $anamneseController->relatorio($matches[1]);
} elseif (preg_match('/\/anamneses\/relatorio\/pdf\/(\d+)/', $uri, $matches)) {
    $anamneseController->gerarRelatorioPDF($matches[1]);
}

// Orçamentos
elseif ($uri == '/orcamentos') {
    $orcamentoController->index();
} elseif ($uri == '/orcamentos/create') {
    $orcamentoController->create();
} elseif ($uri == '/orcamentos/store' && $method == 'POST') {
    $orcamentoController->store();
} elseif (preg_match('/\/orcamentos\/edit\/(\d+)/', $uri, $matches)) {
    $orcamentoController->edit($matches[1]);
} elseif (preg_match('/\/orcamentos\/update\/(\d+)/', $uri, $matches) && $method == 'POST') {
    $orcamentoController->update($matches[1]);
} elseif (preg_match('/\/orcamentos\/delete\/(\d+)/', $uri, $matches)) {
    $orcamentoController->delete($matches[1]);
} elseif (preg_match('/\/orcamentos\/relatorio\/(\d+)/', $uri, $matches)) {
    $orcamentoController->relatorio($matches[1]);
} elseif (preg_match('/\/orcamentos\/relatorio\/pdf\/(\d+)/', $uri, $matches)) {
    $orcamentoController->gerarRelatorioPDF($matches[1]);
}

// Agendamentos
elseif ($uri == '/agendamentos') {
    $agendamentoController->index();
} elseif ($uri == '/agendamentos/create') {
    $agendamentoController->create();
} elseif ($uri == '/agendamentos/store' && $method == 'POST') {
    $agendamentoController->store();
} elseif ($uri === '/agendamentos/calendario') {
    $agendamentoController->calendario();
} elseif ($uri === '/agendamentos/eventos') {
    header('Content-Type: application/json');
    echo json_encode($agendamentoController->getEventosJson());
} elseif (preg_match('/\/agendamentos\/paciente\/(\d+)/', $uri, $matches)) {
    header('Content-Type: application/json');
    echo json_encode($agendamentoController->getPacienteByAgendamentoId((int)$matches[1]));
} elseif (preg_match('/\/agendamentos\/edit\/(\d+)/', $uri, $matches)) {
    $agendamentoController->edit($matches[1]);
} elseif (preg_match('/\/agendamentos\/update\/(\d+)/', $uri, $matches) && $method == 'POST') {
    $agendamentoController->update($matches[1]);
} elseif (preg_match('/\/agendamentos\/delete\/(\d+)/', $uri, $matches)) {
    $agendamentoController->delete($matches[1]);
} 

// Receitas
elseif ($uri == '/receitas') {
    $receitaController->index();
} elseif ($uri == '/receitas/create') {
    $receitaController->create();
} elseif ($uri == '/receitas/store' && $method == 'POST') {
    $receitaController->store();
} elseif (preg_match('/\/receitas\/edit\/(\d+)/', $uri, $matches)) {
    $receitaController->edit($matches[1]);
} elseif (preg_match('/\/receitas\/update\/(\d+)/', $uri, $matches) && $method == 'POST') {
    $receitaController->update($matches[1]);
} elseif (preg_match('/\/receitas\/delete\/(\d+)/', $uri, $matches)) {
    $receitaController->delete($matches[1]);
} elseif (preg_match('/\/receitas\/relatorio\/(\d+)/', $uri, $matches)) {
    $receitaController->relatorio($matches[1]);
} elseif (preg_match('/\/receitas\/relatorio\/pdf\/(\d+)/', $uri, $matches)) {
    $receitaController->gerarRelatorioPDF($matches[1]);
}

// Lançamentos
elseif ($uri == '/lancamentos') {
    $lancamentoController->index();
} elseif ($uri == '/lancamentos/create') {
    $lancamentoController->create();
} elseif ($uri == '/lancamentos/store' && $method == 'POST') {
    $lancamentoController->store();
} elseif (preg_match('/\/lancamentos\/edit\/(\d+)/', $uri, $matches)) {
    $lancamentoController->edit($matches[1]);
} elseif (preg_match('/\/lancamentos\/update\/(\d+)/', $uri, $matches) && $method == 'POST') {
    $lancamentoController->update($matches[1]);
} elseif (preg_match('/\/lancamentos\/delete\/(\d+)/', $uri, $matches)) {
    $lancamentoController->delete($matches[1]);
}

// Extrato por paciente
elseif (preg_match('/\/lancamentos\/extrato\/(\d+)/', $uri, $matches)) {
    $lancamentoController->extrato($matches[1]);
}

// Relatório individual com PDF
elseif (preg_match('/\/lancamentos\/relatorio\/(\d+)/', $uri, $matches)) {
    $lancamentoController->relatorio($matches[1]);
} elseif (preg_match('/\/lancamentos\/relatorio\/pdf\/(\d+)/', $uri, $matches)) {
    $lancamentoController->gerarRelatorioPDF($matches[1]);
}

// Relatório geral por período
elseif ($uri == '/lancamentos/relatorioGeral') {
    $lancamentoController->relatorioGeral();
} 

// Usuários
elseif ($uri == '/usuarios/create') {
    $usuarioController->create();
} elseif ($uri == '/usuarios/store' && $method == 'POST') {
    $usuarioController->store();
} elseif ($uri == '/usuarios/change-password') {
    if ($method == 'GET') {
        $usuarioController->changePasswordForm();
    } elseif ($method == 'POST') {
        $usuarioController->changePassword();
    }
}


// Dashboard
elseif ($uri === '/dashboard') {
    require __DIR__ . '/../app/views/dashboard.php';
}

// Erro  404
else {
    http_response_code(404);
    echo "Página não encontrada.";
}
