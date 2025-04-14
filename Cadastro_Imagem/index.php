<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Fotos</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <ul><a href='galeria.php'>Galeria</a>
    </header>
    <form action="upload.php" method="POST" enctype="multipart/form-data">
        <label for="sala">Escolha sua sala</label>
        <select name="sala">
            <option>3°A</option>
            <option>3°B</option>
            <option>3°C</option>
        </select>
        <label for="foto">Emvie sua foto</label>
        <input type="file" name="foto" accept="image/*">
        <button type="submit">Enviar</button>
    </form>
    
</body>
</html>