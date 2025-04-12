<?php
// config.php - Configuração do banco de dados e sessão

define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'sistema_odontologico');
define('DB_USER', 'root'); // Substitua pelo seu usuário
define('DB_PASS', ''); // Substitua pela sua senha
define('SESSION_NAME', 'sistema_odontologico'); // Nome da sessão
define('BASE_URL', 'http://localhost/sistema_odontologico/public'); // URL Base do Sistema com / no final
define('SECRET_KEY', 'Hello World!!!'); // Chave para ajudar na criptografia.

define('NOME_CLINICA', 'CLÍNICA ODONTOLÓGICA'); // Nome da clinica
define('ENDERECO_CLINICA', 'Rua Marechal Deodoro, nº 281'); // Endereço da clinica
define('CIDADE_CLINICA', 'Taperoá'); // Cidade
define('ESTADO_CLINICA', 'BA'); // Estado
define('CEP_CLINICA', '45430-000'); // CEP