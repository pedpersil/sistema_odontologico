<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../models/LancamentoContabil.php';
require_once __DIR__ . '/../models/Paciente.php';
require_once __DIR__ . '/../models/Orcamento.php';
require_once __DIR__ . '/../../vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;
use chillerlan\QRCode\{QRCode, QROptions};

class LancamentoController {
    private LancamentoContabil $model;
    private Paciente $pacienteModel;
    private Orcamento $orcamentoModel;

    public function __construct() {
        $this->model = new LancamentoContabil();
        $this->pacienteModel = new Paciente();
        $this->orcamentoModel = new Orcamento();
    }

    public function index(): void {
        $lancamentos = $this->model->getAll();
        include __DIR__ . '/../views/lancamentos/index.php';
    }

    public function create(): void {
        $pacientes = $this->pacienteModel->getAll();
        $orcamentos = $this->orcamentoModel->getAll();
        include __DIR__ . '/../views/lancamentos/create.php';
    }

    public function store(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            if (empty($_POST['orcamento_id'])) {

                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }
        
                $_SESSION['lancamento_error'] = 'É necessário informar um orçamento válido para registrar um lançamento.';

                $error = $_SESSION['lancamento_error'] ?? null;
                unset($_SESSION['lancamento_error']);

                require __DIR__ . '/../views/lancamentos/create.php';
            } else {
                $data = [
                    'paciente_id' => $_POST['paciente_id'] ?? '',
                    'orcamento_id' => $_POST['orcamento_id'] ?? null,
                    'data_lancamento' => $_POST['data_lancamento'] ?? date('Y-m-d'),
                    'descricao' => $_POST['descricao'] ?? '',
                    'tipo' => $_POST['tipo'] ?? 'credito',
                    'valor' => $_POST['valor'] ?? 0,
                    'categoria' => $_POST['categoria'] ?? 'recebimento'
                ];

                $this->model->create($data);
                header('Location: ' . BASE_URL . '/lancamentos');
                exit;
            }
        }
    }

    public function edit(int $id): void {
        $lancamento = $this->model->find($id);
        if ($lancamento) {
            include __DIR__ . '/../views/lancamentos/edit.php';
        } else {
            echo "Lançamento Contábil não encontrado.";
        }
    }

    public function update(int $id): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Verifica se foi enviado o ID do orçamento
            if (empty($_POST['orcamento_id'])) {
    
                // Inicia a sessão se necessário
                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }
            
                $_SESSION['lancamento_error'] = 'É necessário informar um orçamento válido para atualizar o lançamento.';
    
                $error = $_SESSION['lancamento_error'] ?? null;
                unset($_SESSION['lancamento_error']);
    
                // Redireciona de volta para a página de edição do lançamento
                require __DIR__ . '/../views/lancamentos/edit.php';
            } else {
                // Dados que serão atualizados
                $data = [
                    'paciente_id' => $_POST['paciente_id'] ?? '',
                    'orcamento_id' => $_POST['orcamento_id'] ?? null,
                    'data_lancamento' => $_POST['data_lancamento'] ?? date('Y-m-d'),
                    'descricao' => $_POST['descricao'] ?? '',
                    'tipo' => $_POST['tipo'] ?? 'credito',
                    'valor' => $_POST['valor'] ?? 0,
                    'categoria' => $_POST['categoria'] ?? 'recebimento'
                ];
    
                // Atualiza os dados do lançamento no banco de dados, passando o ID
                $this->model->update($id, $data);
    
                // Redireciona para a lista de lançamentos
                header('Location: ' . BASE_URL . '/lancamentos');
                exit;
            }
        } else {
            // Caso o método não seja POST, carrega os dados para edição
            $lancamento = $this->model->find($id);
            if ($lancamento) {
                include __DIR__ . '/../views/lancamentos/edit.php';
            } else {
                echo "Lançamento Contábil não encontrado.";
            }
        }
    }
    
    public function delete(int $id): void {
        $this->model->delete($id);
        header('Location: ' . BASE_URL . '/lancamentos');
        exit;
    }

    public function extrato(int $pacienteId): void {
        $dataInicio = $_GET['data_inicio'] ?? null;
        $dataFim = $_GET['data_fim'] ?? null;
    
        $lancamentos = $this->model->getByPaciente($pacienteId, $dataInicio, $dataFim);
        $paciente = $this->pacienteModel->find($pacienteId);
        $saldo = $this->model->getSaldoPaciente($pacienteId, $dataInicio, $dataFim);
    
        include __DIR__ . '/../views/lancamentos/extrato.php';
    }
    
    public function relatorioGeral(): void {
        $dataInicio = $_GET['data_inicio'] ?? null;
        $dataFim = $_GET['data_fim'] ?? null;
    
        $resumo = [];
    
        if ($dataInicio && $dataFim) {
            $resumo = $this->model->getResumoPorPaciente($dataInicio, $dataFim);
        }
    
        include __DIR__ . '/../views/lancamentos/relatorio_geral.php';
    }

    public function relatorio(int $lancamentoId): void {
        $paciente = $this->pacienteModel->getPacienteAllInfoByLancamentoId($lancamentoId);
        $lancamentos = $this->model->getByPaciente($paciente['id_paciente']);
        $saldo = $this->model->getSaldoPaciente($paciente['id_paciente']);
    
        include __DIR__ . '/../views/lancamentos/relatorio.php';
    }
    
    public function gerarRelatorioPDF(int $pacienteId): void {
        // Buscar dados do paciente e seus lançamentos
        $paciente = $this->pacienteModel->find($pacienteId);
        $lancamentos = $this->model->getByPaciente($pacienteId);
        $saldo = $this->model->getSaldoPaciente($pacienteId);
    
        // Preparar os dados que serão incluídos no relatório
        $dadosRelatorio = [
            'paciente' => $paciente,
            'saldo' => $saldo,
            'lancamentos' => $lancamentos
        ];
    
        // Criar string para geração da assinatura
        $dadosLancamentos = '';
        foreach ($lancamentos as $lancamento) {
            $dadosLancamentos .= $lancamento['id_lancamento'] . $lancamento['descricao'] . $lancamento['valor'];
        }
    
        $dadosParaAssinar = $paciente['nome'] . $paciente['cpf'] . $paciente['rg'] . $dadosLancamentos;
    
        // Geração do hash SHA-256 com a SECRET_KEY definida no config
        $assinatura = hash('sha256', $dadosParaAssinar . SECRET_KEY);
    
        // Registrar ou obter assinatura no banco
        $this->model->assinar($assinatura, 'Extratos', $dadosRelatorio);
    
        // Criar QR Code para verificação da autenticidade
        $urlVerificacao = BASE_URL . "/verificar/" . $assinatura;
        $qrcode = '<div style="text-align: center;"><img src="' . (new QRCode())->render($urlVerificacao) . '" alt="QR Code" style="width:150px; height:150px;"/></div>';
    
        // Iniciar captura do HTML da view
        ob_start();
        include __DIR__ . '/../views/lancamentos/relatorio_pdf.php';
        $html = ob_get_clean();
    
        // Configuração do DomPDF
        $options = new Options();
        $options->set('defaultFont', 'Arial');
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
    
        // Instanciar e renderizar o PDF
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
    
        // Exibir o PDF no navegador (sem forçar download)
        $dompdf->stream("Lancamentos_{$paciente['nome']}.pdf", ["Attachment" => false]);
    }    
    
}
