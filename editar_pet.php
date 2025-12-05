<?php
include 'config.php';

$mensagem = '';
$tipo = '';
$pet = null;

// Verificar se o ID foi fornecido
if (!isset($_GET['id'])) {
    header('Location: pets.php');
    exit;
}

$id = intval($_GET['id']);

// Buscar pet
$result = $conn->query("SELECT * FROM pets WHERE id = $id");

if ($result->num_rows == 0) {
    header('Location: pets.php');
    exit;
}

$pet = $result->fetch_assoc();

// Buscar clientes para o dropdown
$clientes = $conn->query("SELECT id, nome FROM clientes WHERE ativo = TRUE ORDER BY nome ASC");

// Processar atualização
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $cliente_id = intval($_POST['cliente_id']);
    $nome = sanitizar($_POST['nome']);
    $especie = sanitizar($_POST['especie']);
    $raca = sanitizar($_POST['raca']);
    $data_nascimento = sanitizar($_POST['data_nascimento']);
    $peso = sanitizar($_POST['peso']);
    $cor = sanitizar($_POST['cor']);
    $observacoes = sanitizar($_POST['observacoes']);

    // Validações
    if (empty($nome)) {
        $mensagem = "Nome do pet é obrigatório!";
        $tipo = "danger";
    } elseif (empty($especie)) {
        $mensagem = "Espécie é obrigatória!";
        $tipo = "danger";
    } elseif ($cliente_id <= 0) {
        $mensagem = "Cliente é obrigatório!";
        $tipo = "danger";
    } else {
        // Preparar valores para atualização
        $nome = $conn->real_escape_string($nome);
        $especie = $conn->real_escape_string($especie);
        $raca = $conn->real_escape_string($raca);
        $cor = $conn->real_escape_string($cor);
        $observacoes = $conn->real_escape_string($observacoes);

        // Atualizar pet
        $sql = "UPDATE pets SET 
                cliente_id = $cliente_id,
                nome = '$nome', 
                especie = '$especie', 
                raca = '$raca', 
                data_nascimento = '$data_nascimento', 
                peso = '$peso', 
                cor = '$cor', 
                observacoes = '$observacoes' 
                WHERE id = $id";

        if ($conn->query($sql)) {
            $mensagem = "Pet atualizado com sucesso!";
            $tipo = "success";
            // Recarregar dados do pet
            $result = $conn->query("SELECT * FROM pets WHERE id = $id");
            $pet = $result->fetch_assoc();
        } else {
            $mensagem = "Erro ao atualizar pet: " . $conn->error;
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
    <title>Editar Pet - Dr. Animal Pet Shop</title>
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
                <h2>Editar Pet</h2>
            </div>

            <form method="POST" action="">
                <div class="form-group">
                    <label for="cliente_id">Cliente *</label>
                    <select id="cliente_id" name="cliente_id" required>
                        <option value="">Selecione um cliente</option>
                        <?php
                        $clientes->data_seek(0);
                        while ($cliente = $clientes->fetch_assoc()) {
                            $selected = ($pet['cliente_id'] == $cliente['id']) ? 'selected' : '';
                            echo '<option value="' . $cliente['id'] . '" ' . $selected . '>' . htmlspecialchars($cliente['nome']) . '</option>';
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="nome">Nome do Pet *</label>
                    <input type="text" id="nome" name="nome" required value="<?php echo htmlspecialchars($pet['nome']); ?>">
                </div>

                <div class="form-group">
                    <label for="especie">Espécie *</label>
                    <input type="text" id="especie" name="especie" placeholder="Ex: Cão, Gato, Pássaro..." required value="<?php echo htmlspecialchars($pet['especie']); ?>">
                </div>

                <div class="form-group">
                    <label for="raca">Raça</label>
                    <input type="text" id="raca" name="raca" placeholder="Ex: Labrador, Siamês..." value="<?php echo htmlspecialchars($pet['raca'] ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label for="data_nascimento">Data de Nascimento</label>
                    <input type="date" id="data_nascimento" name="data_nascimento" value="<?php echo htmlspecialchars($pet['data_nascimento'] ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label for="peso">Peso (kg)</label>
                    <input type="number" id="peso" name="peso" step="0.01" placeholder="Ex: 25.50" value="<?php echo htmlspecialchars($pet['peso'] ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label for="cor">Cor</label>
                    <input type="text" id="cor" name="cor" placeholder="Ex: Preto, Branco e Marrom..." value="<?php echo htmlspecialchars($pet['cor'] ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label for="observacoes">Observações</label>
                    <textarea id="observacoes" name="observacoes"><?php echo htmlspecialchars($pet['observacoes'] ?? ''); ?></textarea>
                </div>

                <div class="btn-group">
                    <button type="submit" class="btn btn-primary">✅ Atualizar Pet</button>
                    <a href="pets.php" class="btn btn-secondary">❌ Cancelar</a>
                </div>
            </form>
        </div>

        <footer>
            <p>&copy; 2024 Dr. Animal Pet Shop - Todos os direitos reservados</p>
        </footer>
    </div>
</body>
</html>
