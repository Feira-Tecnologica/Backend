<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recebe as notas do formulário
    $nota1 = $_POST['nota1'];
    $nota2 = $_POST['nota2'];
    $nota3 = $_POST['nota3'];
    $nota4 = $_POST['nota4'];
    $nota5 = $_POST['nota5'];
    $nota6 = $_POST['nota6'];
    $nota7 = $_POST['nota7'];
    $nota8 = $_POST['nota8'];

    // Calcula a média
    $media = ($nota1 + $nota2 + $nota3 + $nota4 + $nota5 + $nota6 + $nota7 + $nota8) / 8;

    // Exibe a média
    echo "<h2>A média das notas é: " . number_format($media, 2) . "</h2>";
}
?>
