<?php
include 'config.php';

$mensagem = '';
$tipo = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = sanitizar($_POST['nome']);
    $email = sanitizar($_POST['email']);
    $telefone = sanitizar($_POST['telefone']);
    $endereco = sanitizar($_POST['endereco']);
    $cidade = sanitizar($_POST['cidade']);
    $estado = sanitizar($_POST['estado']);
    $cep = sanitizar($_POST['cep']);

    // Validações
    if (empty($nome)) {
        $mensagem = "Nome é obrigatório!";
        $tipo = "danger";
    } elseif (!empty($email) && !validar_email($email)) {
        $mensagem = "Email inválido!";
        $tipo = "danger";
    } else {
        // Preparar valores para inserção
        $nome = $conn->real_escape_string($nome);
        $email = $conn->real_escape_string($email);
        $telefone = $conn->real_escape_string($telefone);
        $endereco = $conn->real_escape_string($endereco);
        $cidade = $conn->real_escape_string($cidade);
        $estado = $conn->real_escape_string($estado);
        $cep = $conn->real_escape_string($cep);

        // Inserir cliente
        $sql = "INSERT INTO clientes (nome, email, telefone, endereco, cidade, estado, cep) 
                VALUES ('$nome', '$email', '$telefone', '$endereco', '$cidade', '$estado', '$cep')";

        if ($conn->query($sql)) {
            $mensagem = "Cliente criado com sucesso!";
            $tipo = "success";
            // Limpar formulário
            $_POST = array();
        } else {
            $mensagem = "Erro ao criar cliente: " . $conn->error;
            $tipo = "danger";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Cliente - Dr. Animal Pet Shop</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>🐾 Dr. Animal Pet Shop</h1>
            <p>Sistema de Gerenciamento</p>
        </header>

        <nav>
            <ul>
                <li><a href="index.php">📋 Clientes</a></li>
                <li><a href="pets.php">🐶 Pets</a></li>
                <li><a href="servicos.php">✂️ Serviços</a></li>
            </ul>
        </nav>

        <?php
        if (!empty($mensagem)) {
            echo '<div class="alert alert-' . $tipo . '" role="alert">' . $mensagem . '</div>';
        }
        ?>

        <div class="card">
            <div class="card-header">
                <h2>Novo Cliente</h2>
            </div>

            <form method="POST" action="">
                <div class="form-group">
                    <label for="nome">Nome *</label>
                    <input type="text" id="nome" name="nome" required value="<?php echo htmlspecialchars($_POST['nome'] ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label for="telefone">Telefone</label>
                    <input type="tel" id="telefone" name="telefone" placeholder="(XX) XXXXX-XXXX" value="<?php echo htmlspecialchars($_POST['telefone'] ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label for="endereco">Endereço</label>
                    <input type="text" id="endereco" name="endereco" value="<?php echo htmlspecialchars($_POST['endereco'] ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label for="cidade">Cidade</label>
                    <input type="text" id="cidade" name="cidade" value="<?php echo htmlspecialchars($_POST['cidade'] ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label for="estado">Estado</label>
                    <input type="text" id="estado" name="estado" placeholder="SP, RJ, MG..." maxlength="2" value="<?php echo htmlspecialchars($_POST['estado'] ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label for="cep">CEP</label>
                    <input type="text" id="cep" name="cep" placeholder="XXXXX-XXX" value="<?php echo htmlspecialchars($_POST['cep'] ?? ''); ?>">
                </div>

                <div class="btn-group">
                    <button type="submit" class="btn btn-primary">✅ Salvar Cliente</button>
                    <a href="index.php" class="btn btn-secondary">❌ Cancelar</a>
                </div>
            </form>
        </div>

        <footer>
            <p>&copy; 2024 Dr. Animal Pet Shop - Todos os direitos reservados</p>
        </footer>
    </div>
</body>
</html>
