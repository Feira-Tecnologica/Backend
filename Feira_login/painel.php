<?php
    session_start();

    if (!isset($_SESSION['usuario_tipo'])) {
        header("Location: login.php");
        exit;
    }

    echo "<h2>Painel da Feira Tecnológica</h2>";

    if ($_SESSION['usuario_tipo'] === 'aluno') {
        echo "<p><strong>Tipo:</strong> Aluno</p>";
        echo "<p><strong>RM:</strong> " . $_SESSION['RM'] . "</p>";
        echo "<p><strong>Email:</strong> " . $_SESSION['email'] . "</p>";
        echo "<p>Bem-vindo ao seu painel de aluno!</p>";

    } elseif ($_SESSION['usuario_tipo'] === 'professor') {
        echo "<p><strong>Tipo:</strong> Professor</p>";
        echo "<p><strong>Email:</strong> " . $_SESSION['email'] . "</p>";
        echo "<p>Bem-vindo ao seu painel de professor!</p>";

    }
?>

<a href="logout.php">Sair</a>
