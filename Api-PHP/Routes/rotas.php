<?php
require_once __DIR__ . '/../Controllers/ExemploController.php';
require_once __DIR__ . '/../Controllers/ImageController.php';

$scriptName = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
$uri = str_replace($scriptName, '', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
$method = $_SERVER['REQUEST_METHOD'];

$exemploController = new ExemploController();
$imageController = new ImageController();

if ($uri == '/api' && $method == 'GET') {
    $exemploController->index();
} elseif ($uri == '/api/usuarios' && $method == 'GET') {
    $exemploController->listarUsuarios();
} elseif ($uri == '/api/imagem' && $method == 'GET') {
    $imageController->todasFotos();
} elseif ($uri == '/api/imagemprojeto' && $method == 'POST') {
    $imageController->fotosPorGrupo();
} else {
    http_response_code(404);
    echo json_encode(["erro" => "Rota não encontrada"]);
}
?>
