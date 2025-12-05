<?php
include 'config.php';

$mensagem = '';
$tipo = '';
$cliente = null;

// Verificar se o ID foi fornecido
if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit;
}

$id = intval($_GET['id']);

// Buscar cliente
$result = $conn->query("SELECT * FROM clientes WHERE id = $id");

if ($result->num_rows == 0) {
    header('Location: index.php');
    exit;
}

$cliente = $result->fetch_assoc();

// Processar atualização
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
        // Preparar valores para atualização
        $nome = $conn->real_escape_string($nome);
        $email = $conn->real_escape_string($email);
        $telefone = $conn->real_escape_string($telefone);
        $endereco = $conn->real_escape_string($endereco);
        $cidade = $conn->real_escape_string($cidade);
        $estado = $conn->real_escape_string($estado);
        $cep = $conn->real_escape_string($cep);

        // Atualizar cliente
        $sql = "UPDATE clientes SET 
                nome = '$nome', 
                email = '$email', 
                telefone = '$telefone', 
                endereco = '$endereco', 
                cidade = '$cidade', 
                estado = '$estado', 
                cep = '$cep' 
                WHERE id = $id";

        if ($conn->query($sql)) {
            $mensagem = "Cliente atualizado com sucesso!";
            $tipo = "success";
            // Recarregar dados do cliente
            $result = $conn->query("SELECT * FROM clientes WHERE id = $id");
            $cliente = $result->fetch_assoc();
        } else {
            $mensagem = "Erro ao atualizar cliente: " . $conn->error;
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
    <title>Editar Cliente - Dr. Animal Pet Shop</title>
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
                <h2>Editar Cliente</h2>
            </div>

            <form method="POST" action="">
                <div class="form-group">
                    <label for="nome">Nome *</label>
                    <input type="text" id="nome" name="nome" required value="<?php echo htmlspecialchars($cliente['nome']); ?>">
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($cliente['email'] ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label for="telefone">Telefone</label>
                    <input type="tel" id="telefone" name="telefone" placeholder="(XX) XXXXX-XXXX" value="<?php echo htmlspecialchars($cliente['telefone'] ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label for="endereco">Endereço</label>
                    <input type="text" id="endereco" name="endereco" value="<?php echo htmlspecialchars($cliente['endereco'] ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label for="cidade">Cidade</label>
                    <input type="text" id="cidade" name="cidade" value="<?php echo htmlspecialchars($cliente['cidade'] ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label for="estado">Estado</label>
                    <input type="text" id="estado" name="estado" placeholder="SP, RJ, MG..." maxlength="2" value="<?php echo htmlspecialchars($cliente['estado'] ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label for="cep">CEP</label>
                    <input type="text" id="cep" name="cep" placeholder="XXXXX-XXX" value="<?php echo htmlspecialchars($cliente['cep'] ?? ''); ?>">
                </div>

                <div class="btn-group">
                    <button type="submit" class="btn btn-primary">✅ Atualizar Cliente</button>
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
