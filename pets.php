<?php
include 'config.php';

// Processar exclusão de pet
if (isset($_GET['deletar'])) {
    $id = intval($_GET['deletar']);
    
    // Verificar se o pet existe
    $check = $conn->query("SELECT id FROM pets WHERE id = $id");
    
    if ($check->num_rows > 0) {
        if ($conn->query("DELETE FROM pets WHERE id = $id")) {
            $mensagem = "Pet deletado com sucesso!";
            $tipo = "success";
        } else {
            $mensagem = "Erro ao deletar pet: " . $conn->error;
            $tipo = "danger";
        }
    } else {
        $mensagem = "Pet não encontrado!";
        $tipo = "warning";
    }
}

// Buscar todos os pets com informações do cliente
$result = $conn->query("
    SELECT p.*, c.nome as cliente_nome 
    FROM pets p 
    JOIN clientes c ON p.cliente_id = c.id 
    WHERE p.ativo = TRUE 
    ORDER BY p.nome ASC
");
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pets - Dr. Animal Pet Shop</title>
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
                <h2>Pets Cadastrados</h2>
            </div>

            <a href="criar_pet.php" class="btn btn-success" style="margin-bottom: 20px;">
                ➕ Novo Pet
            </a>

            <?php
            if ($result->num_rows > 0) {
                echo '<table>';
                echo '<thead>';
                echo '<tr>';
                echo '<th>ID</th>';
                echo '<th>Nome</th>';
                echo '<th>Cliente</th>';
                echo '<th>Espécie</th>';
                echo '<th>Raça</th>';
                echo '<th>Peso</th>';
                echo '<th>Cor</th>';
                echo '<th>Ações</th>';
                echo '</tr>';
                echo '</thead>';
                echo '<tbody>';

                while ($row = $result->fetch_assoc()) {
                    echo '<tr>';
                    echo '<td>' . htmlspecialchars($row['id']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['nome']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['cliente_nome']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['especie']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['raca'] ?? 'N/A') . '</td>';
                    echo '<td>' . htmlspecialchars($row['peso'] ?? 'N/A') . ' kg</td>';
                    echo '<td>' . htmlspecialchars($row['cor'] ?? 'N/A') . '</td>';
                    echo '<td class="actions">';
                    echo '<a href="editar_pet.php?id=' . $row['id'] . '" class="btn btn-warning">✏️ Editar</a>';
                    echo '<a href="pets.php?deletar=' . $row['id'] . '" class="btn btn-danger" onclick="return confirm(\'Tem certeza que deseja deletar este pet?\');">🗑️ Deletar</a>';
                    echo '</td>';
                    echo '</tr>';
                }

                echo '</tbody>';
                echo '</table>';
            } else {
                echo '<p style="text-align: center; padding: 20px; color: #666;">Nenhum pet cadastrado. <a href="criar_pet.php">Clique aqui para criar um novo pet.</a></p>';
            }
            ?>
        </div>

        <footer>
            <p>&copy; 2024 Dr. Animal Pet Shop - Todos os direitos reservados</p>
        </footer>
    </div>
</body>
</html>
