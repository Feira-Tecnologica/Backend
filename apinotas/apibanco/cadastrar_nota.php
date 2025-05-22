<?php
include 'conecta.php';
$message = '';

function calcularMencao($media)
{
    if ($media < 5) return 'I';
    if ($media < 7) return 'R';
    if ($media < 9) return 'B';
    return 'MB';
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $nota1 = floatval($_POST['nota1']);
    $nota2 = floatval($_POST['nota2']);
    $nota3 = floatval($_POST['nota3']);
    $media = ($nota1 + $nota2 + $nota3) / 3;
    $mencao = calcularMencao($media);

    $stmt = $pdo->prepare("INSERT INTO avaliacoes (nome_aluno, nota1, nota2, nota3, media, mencao) VALUES (?, ?, ?, ?, ?, ?)");
    if ($stmt->execute([$nome, $nota1, $nota2, $nota3, $media, $mencao])) {
        $message = "Notas cadastradas com sucesso!";
    } else {
        $message = "Erro ao cadastrar.";
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Cadastrar Avaliação</title>
    <style>
        body {
            font-family: Arial;
            background: #f2f2f2;
            padding: 20px;
        }

        .container {
            background: #fff;
            padding: 20px;
            border-radius: 5px;
            max-width: 600px;
            margin: auto;
        }

        input {
            width: 100%;
            margin-bottom: 10px;
            padding: 8px;
        }

        input[type="submit"] {
            background: green;
            color: white;
            cursor: pointer;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Cadastrar Notas do Aluno</h2>
        <?php if ($message): ?><p><?php echo $message; ?></p><?php endif; ?>
        <form method="post">
            <label>Nome do Aluno:</label>
            <input type="text" name="nome" required>
            <label>Nota do Professor 1:</label>
            <input type="number" name="nota1" step="0.01" required>
            <label>Nota do Professor 2:</label>
            <input type="number" name="nota2" step="0.01" required>
            <label>Nota do Professor 3:</label>
            <input type="number" name="nota3" step="0.01" required>
            <input type="submit" value="Cadastrar Avaliação">
        </form>
        <a href="tela_valida_notas.php">Ver Avaliações</a>
    </div>
</body>

</html>