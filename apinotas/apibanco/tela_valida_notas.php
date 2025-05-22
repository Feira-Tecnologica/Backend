<?php
include 'conecta.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['id']) && isset($_POST['action']) && $_POST['action'] == 'aprovar') {
        $stmt = $pdo->prepare("UPDATE avaliacoes SET aprovado = 1 WHERE id = ?");
        $stmt->execute([$_POST['id']]);
    }
}

$avaliacoes = $pdo->query("SELECT * FROM avaliacoes WHERE aprovado = 0")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Avaliações Pendentes</title>
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
            max-width: 800px;
            margin: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 10px;
        }

        .btn {
            background: #28a745;
            color: #fff;
            padding: 5px 10px;
            border: none;
            cursor: pointer;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Avaliações Pendentes</h2>
        <?php if (count($avaliacoes) > 0): ?>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Aluno</th>
                    <th>Notas</th>
                    <th>Média</th>
                    <th>Menção</th>
                    <th>Ação</th>
                </tr>
                <?php foreach ($avaliacoes as $a): ?>
                    <tr>
                        <td><?= $a['id'] ?></td>
                        <td><?= htmlspecialchars($a['nome_aluno']) ?></td>
                        <td><?= $a['nota1'] ?>, <?= $a['nota2'] ?>, <?= $a['nota3'] ?></td>
                        <td><?= number_format($a['media'], 2) ?></td>
                        <td><?= $a['mencao'] ?></td>
                        <td>
                            <form method="post">
                                <input type="hidden" name="id" value="<?= $a['id'] ?>">
                                <input type="hidden" name="action" value="aprovar">
                                <button class="btn" type="submit">Aprovar</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <p>Nenhuma avaliação pendente.</p>
        <?php endif; ?>
        <a href="cadastrar_nota.php">Cadastrar Nova Avaliação</a>
    </div>
</body>

</html>