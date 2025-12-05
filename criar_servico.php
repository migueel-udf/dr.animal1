<?php
include 'config.php';

$mensagem = '';
$tipo = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = sanitizar($_POST['nome']);
    $descricao = sanitizar($_POST['descricao']);
    $preco = sanitizar($_POST['preco']);
    $duracao_minutos = sanitizar($_POST['duracao_minutos']);

    // Validações
    if (empty($nome)) {
        $mensagem = "Nome do serviço é obrigatório!";
        $tipo = "danger";
    } elseif (empty($preco) || !is_numeric($preco)) {
        $mensagem = "Preço é obrigatório e deve ser um número!";
        $tipo = "danger";
    } else {
        // Preparar valores para inserção
        $nome = $conn->real_escape_string($nome);
        $descricao = $conn->real_escape_string($descricao);
        $preco = floatval($preco);
        $duracao_minutos = !empty($duracao_minutos) ? intval($duracao_minutos) : NULL;

        // Inserir serviço
        $sql = "INSERT INTO servicos (nome, descricao, preco, duracao_minutos) 
                VALUES ('$nome', '$descricao', $preco, " . ($duracao_minutos ? $duracao_minutos : 'NULL') . ")";

        if ($conn->query($sql)) {
            $mensagem = "Serviço criado com sucesso!";
            $tipo = "success";
            $_POST = array();
        } else {
            $mensagem = "Erro ao criar serviço: " . $conn->error;
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
    <title>Criar Serviço - Dr. Animal Pet Shop</title>
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
                <h2>Novo Serviço</h2>
            </div>

            <form method="POST" action="">
                <div class="form-group">
                    <label for="nome">Nome do Serviço *</label>
                    <input type="text" id="nome" name="nome" required value="<?php echo htmlspecialchars($_POST['nome'] ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label for="descricao">Descrição</label>
                    <textarea id="descricao" name="descricao"><?php echo htmlspecialchars($_POST['descricao'] ?? ''); ?></textarea>
                </div>

                <div class="form-group">
                    <label for="preco">Preço (R$) *</label>
                    <input type="number" id="preco" name="preco" step="0.01" placeholder="Ex: 50.00" required value="<?php echo htmlspecialchars($_POST['preco'] ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label for="duracao_minutos">Duração (minutos)</label>
                    <input type="number" id="duracao_minutos" name="duracao_minutos" placeholder="Ex: 30" value="<?php echo htmlspecialchars($_POST['duracao_minutos'] ?? ''); ?>">
                </div>

                <div class="btn-group">
                    <button type="submit" class="btn btn-primary">✅ Salvar Serviço</button>
                    <a href="servicos.php" class="btn btn-secondary">❌ Cancelar</a>
                </div>
            </form>
        </div>

        <footer>
            <p>&copy; 2024 Dr. Animal Pet Shop - Todos os direitos reservados</p>
        </footer>
    </div>
</body>
</html>
