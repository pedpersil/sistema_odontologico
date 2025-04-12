
# 🦷 Sistema de Consultório Odontológico

Sistema web desenvolvido em PHP 8.2, MySQL e PDO, com interface moderna e responsiva utilizando Bootstrap 5.3.3. Voltado para a gestão completa de clínicas odontológicas.

## ✅ Funcionalidades

### 🔐 Autenticação e Controle de Acesso
- Login com verificação segura de senha (hash).
- Sessão protegida por tipo de usuário (`admin`, `dentista`, `funcionário`).
- Middleware de autorização por tipo.
- Alteração de senha com verificação da senha atual.

### 👤 Gestão de Usuários
- Cadastro de novos usuários (admin, dentista e funcionário).
- Edição, listagem e exclusão de contas.
- Acesso restrito à área de gerenciamento para administradores.

### 🧑‍⚕️ Gestão de Profissionais
- **Dentistas**:
  - Nome, especialidade, telefone, e-mail e endereço.
- **Funcionários**:
  - Cargos como recepcionista, técnico de saúde bucal, porteiro, etc.
  - Informações como telefone, CPF, RG, endereço e sexo.

### 👥 Gestão de Pacientes
- Cadastro completo de pacientes.
- Listagem com busca, edição e exclusão.
- Ficha individual do paciente.

### 📋 Anamneses
- Registro do histórico clínico do paciente.
- Relatório com assinatura digital (QR Code).
- Exportação para PDF.

### 💰 Orçamentos
- Criação de orçamentos vinculados à anamnese.
- Geração automática do `anamnese_id` com base no paciente.
- Exportação de orçamento em PDF.

### 📅 Agendamentos
- Registro de consultas com data, hora, dentista e paciente.
- Tela de listagem responsiva.
- Visualização em formato de calendário.

### 📑 Lançamentos Contábeis
- Registro de créditos e débitos associados a orçamentos.
- Controle de pagamentos e saldo por paciente.
- Geração de extrato financeiro em PDF.

### 📄 Relatórios
- Relatório PDF de extratos e orçamentos.
- Informações organizadas por paciente.
- Estilização adequada para impressão.

### 🏥 Informações da Clínica
- Nome, endereço, cidade, estado e CEP da clínica.
- Visualização com ícones e layout informativo.

## 🛠️ Tecnologias Utilizadas
- PHP 8.2+
- MySQL
- PDO (PHP Data Objects)
- Bootstrap 5.3.3
- DomPDF (geração de PDF)
- Endroid QR Code (QR Code para assinatura)
- Apache + PHP + MySQL + PhpMyAdmin (ambiente de desenvolvimento)

## 📁 Estrutura de Diretórios
```
├── app/
│   ├── controllers/
│   ├── models/
│   └── views/
├── config/
├── public/
├── routes/
└── vendor/
```

## 🚀 Como Executar
1. Clone o repositório:
   ```bash
   git clone https://github.com/pedpersil/sistema_odontologico.git
   ```
2. Configure o arquivo `config/config.php` com os dados necessarios.
3. Instale as dependências via Composer:
   ```bash
   composer install
   ```
4. Inicie o servidor.
5. Crie um banco de dados no PhpMyAdmin chamado "sistema_odontologico".
6. Execute o arquivo que está na pasta database/ chamado sistema_odontologico.sql no banco de dados "sistema_odontologico".

## 🔐 Acesso ao Sistema
- Acesse via `http://localhost/sistema_odontologico/public`.
- Acesse o sistema.
- Email: admin@admin.com
- Senha: 123456

---

> Desenvolvido com 💙 para facilitar a gestão de clínicas odontológicas.
