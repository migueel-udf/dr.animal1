<?php
// =====================================================
// CONFIGURAÇÃO E CONEXÃO COM O BANCO DE DADOS
// =====================================================

// Configurações do banco de dados
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'dr_animal_petshop');

// Criar conexão
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Verificar conexão
if ($conn->connect_error) {
    die("Erro na conexão com o banco de dados: " . $conn->connect_error);
}

// Definir charset para UTF-8
$conn->set_charset("utf8");

// Função para sanitizar entrada de dados
function sanitizar($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

// Função para validar email
function validar_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Função para exibir mensagens de sucesso
function mensagem_sucesso($msg) {
    echo '<div class="alert alert-success" role="alert">' . $msg . '</div>';
}

// Função para exibir mensagens de erro
function mensagem_erro($msg) {
    echo '<div class="alert alert-danger" role="alert">' . $msg . '</div>';
}
?>
