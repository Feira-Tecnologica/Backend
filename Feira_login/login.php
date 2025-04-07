<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Login - Feira Tecnológica</title>
</head>
<body>
    <h2>Login</h2>
    <form action="valida_login.php" method="POST">
        <label>Tipo de usuário:</label>
        <select name="tipo_usuario" required>
            <option value="aluno">Aluno</option>
            <option value="professor">Professor</option>
        </select><br><br>

        <label>Identificação (RM ou Matrícula):</label>
        <input type="text" name="login" required><br><br>

        <label>Senha:</label>
        <input type="password" name="senha" required><br><br>

        <button type="submit">Entrar</button>
    </form>
</body>
</html>