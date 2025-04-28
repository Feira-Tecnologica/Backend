<?php
require_once __DIR__ . '/../Controllers/ExemploController.php';

$base = '/Api-PHP'; 
$uri = str_replace($base, '', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
$method = $_SERVER['REQUEST_METHOD'];

$exemploController = new ExemploController();

if ($uri == '/api' && $method == 'GET') {
    $exemploController->index();
} elseif ($uri == '/api/usuarios' && $method == 'GET') {
    $exemploController->listarUsuarios();
} else {
    http_response_code(404);
    echo json_encode(["erro" => "Rota não encontrada"]);
}
?>

