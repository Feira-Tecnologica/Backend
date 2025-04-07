<?php
    session_start();

    $host = 'localhost';
    $db = 'feiratecnologica';
    $user = 'root';
    $pass = '';

    $conn = new mysqli($host, $user, $pass, $db);
    if ($conn->connect_error) {
        die("Conexão falhou: " . $conn->connect_error);
    }

    $tipo = $_POST['tipo_usuario']; 
    $login = $_POST['login'];
    $senha = $_POST['senha'];

    if ($tipo === 'aluno') {
        $query = "SELECT * FROM alunos WHERE RM = ?";
    } else {
        $query = "SELECT * FROM professores WHERE matricula = ?";
    }

    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $login);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $usuario = $result->fetch_assoc();

        if (password_verify($senha, $usuario['senha'])) {
            $_SESSION['usuario_tipo'] = $tipo;

            if ($tipo == 'aluno') {
                $_SESSION['RM'] = $usuario['RM'];
                $_SESSION['email'] = $usuario['email_institucional'];
            } else {
                $_SESSION['email'] = $usuario['email'];
            }

            header("Location: painel.php");
            exit;
        } else {
            echo "Senha incorreta!";
        }
    } else {
        echo "Usuário não encontrado!";
    }

    $conn->close();
?>
