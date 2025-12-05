<?php
include 'config.php';

// Processar exclusão de cliente
if (isset($_GET['deletar'])) {
    $id = intval($_GET['deletar']);
    
    // Verificar se o cliente existe
    $check = $conn->query("SELECT id FROM clientes WHERE id = $id");
    
    if ($check->num_rows > 0) {
        // Deletar pets associados primeiro
        $conn->query("DELETE FROM pets WHERE cliente_id = $id");
        
        // Deletar cliente
        if ($conn->query("DELETE FROM clientes WHERE id = $id")) {
            $mensagem = "Cliente deletado com sucesso!";
            $tipo = "success";
        } else {
            $mensagem = "Erro ao deletar cliente: " . $conn->error;
            $tipo = "danger";
        }
    } else {
        $mensagem = "Cliente não encontrado!";
        $tipo = "warning";
    }
}

// Buscar todos os clientes
$result = $conn->query("SELECT * FROM clientes WHERE ativo = TRUE ORDER BY nome ASC");
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dr. Animal Pet Shop - Clientes</title>
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
        if (isset($mensagem)) {
            echo '<div class="alert alert-' . $tipo . '" role="alert">' . $mensagem . '</div>';
        }
        ?>

        <div class="card">
            <div class="card-header">
                <h2>Clientes Cadastrados</h2>
            </div>

            <a href="criar_cliente.php" class="btn btn-success" style="margin-bottom: 20px;">
                ➕ Novo Cliente
            </a>

            <?php
            if ($result->num_rows > 0) {
                echo '<table>';
                echo '<thead>';
                echo '<tr>';
                echo '<th>ID</th>';
                echo '<th>Nome</th>';
                echo '<th>Email</th>';
                echo '<th>Telefone</th>';
                echo '<th>Cidade</th>';
                echo '<th>Data Cadastro</th>';
                echo '<th>Ações</th>';
                echo '</tr>';
                echo '</thead>';
                echo '<tbody>';

                while ($row = $result->fetch_assoc()) {
                    echo '<tr>';
                    echo '<td>' . htmlspecialchars($row['id']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['nome']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['email'] ?? 'N/A') . '</td>';
                    echo '<td>' . htmlspecialchars($row['telefone'] ?? 'N/A') . '</td>';
                    echo '<td>' . htmlspecialchars($row['cidade'] ?? 'N/A') . '</td>';
                    echo '<td>' . date('d/m/Y', strtotime($row['data_cadastro'])) . '</td>';
                    echo '<td class="actions">';
                    echo '<a href="editar_cliente.php?id=' . $row['id'] . '" class="btn btn-warning">✏️ Editar</a>';
                    echo '<a href="index.php?deletar=' . $row['id'] . '" class="btn btn-danger" onclick="return confirm(\'Tem certeza que deseja deletar este cliente?\');">🗑️ Deletar</a>';
                    echo '</td>';
                    echo '</tr>';
                }

                echo '</tbody>';
                echo '</table>';
            } else {
                echo '<p style="text-align: center; padding: 20px; color: #666;">Nenhum cliente cadastrado. <a href="criar_cliente.php">Clique aqui para criar um novo cliente.</a></p>';
            }
            ?>
        </div>

        <footer>
            <p>&copy; 2024 Dr. Animal Pet Shop - Todos os direitos reservados</p>
        </footer>
    </div>
</body>
</html>
