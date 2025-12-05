<?php
include 'config.php';

// Processar exclusão de serviço
if (isset($_GET['deletar'])) {
    $id = intval($_GET['deletar']);
    
    // Verificar se o serviço existe
    $check = $conn->query("SELECT id FROM servicos WHERE id = $id");
    
    if ($check->num_rows > 0) {
        if ($conn->query("DELETE FROM servicos WHERE id = $id")) {
            $mensagem = "Serviço deletado com sucesso!";
            $tipo = "success";
        } else {
            $mensagem = "Erro ao deletar serviço: " . $conn->error;
            $tipo = "danger";
        }
    } else {
        $mensagem = "Serviço não encontrado!";
        $tipo = "warning";
    }
}

// Buscar todos os serviços
$result = $conn->query("SELECT * FROM servicos WHERE ativo = TRUE ORDER BY nome ASC");
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Serviços - Dr. Animal Pet Shop</title>
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
                <h2>Serviços Cadastrados</h2>
            </div>

            <a href="criar_servico.php" class="btn btn-success" style="margin-bottom: 20px;">
                ➕ Novo Serviço
            </a>

            <?php
            if ($result->num_rows > 0) {
                echo '<table>';
                echo '<thead>';
                echo '<tr>';
                echo '<th>ID</th>';
                echo '<th>Nome</th>';
                echo '<th>Descrição</th>';
                echo '<th>Preço</th>';
                echo '<th>Duração</th>';
                echo '<th>Ações</th>';
                echo '</tr>';
                echo '</thead>';
                echo '<tbody>';

                while ($row = $result->fetch_assoc()) {
                    echo '<tr>';
                    echo '<td>' . htmlspecialchars($row['id']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['nome']) . '</td>';
                    echo '<td>' . htmlspecialchars(substr($row['descricao'] ?? '', 0, 50)) . '...</td>';
                    echo '<td>R$ ' . number_format($row['preco'], 2, ',', '.') . '</td>';
                    echo '<td>' . htmlspecialchars($row['duracao_minutos'] ?? 'N/A') . ' min</td>';
                    echo '<td class="actions">';
                    echo '<a href="editar_servico.php?id=' . $row['id'] . '" class="btn btn-warning">✏️ Editar</a>';
                    echo '<a href="servicos.php?deletar=' . $row['id'] . '" class="btn btn-danger" onclick="return confirm(\'Tem certeza que deseja deletar este serviço?\');">🗑️ Deletar</a>';
                    echo '</td>';
                    echo '</tr>';
                }

                echo '</tbody>';
                echo '</table>';
            } else {
                echo '<p style="text-align: center; padding: 20px; color: #666;">Nenhum serviço cadastrado. <a href="criar_servico.php">Clique aqui para criar um novo serviço.</a></p>';
            }
            ?>
        </div>

        <footer>
            <p>&copy; 2024 Dr. Animal Pet Shop - Todos os direitos reservados</p>
        </footer>
    </div>
</body>
</html>
