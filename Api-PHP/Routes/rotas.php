<?php
require_once __DIR__ . '/../Controllers/ExemploController.php';
require_once __DIR__ . '/../Controllers/LoginController.php';
require_once __DIR__ . '/../Controllers/CadastroProjetosController.php';

$scriptName = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
$uri = str_replace($scriptName, '', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
$method = $_SERVER['REQUEST_METHOD'];

$exemploController = new ExemploController();
$loginController = new LoginController();
$cadastroProjetoController = new CadastroProjetoController();

if ($uri == '/api' && $method == 'GET') {
    $exemploController->index();
} elseif ($uri == '/api/usuarios' && $method == 'GET') {
    $exemploController->listarUsuarios();
} elseif ($uri == '/api/login/aluno' && $method == 'POST') {
    $loginController->VerificaLoginAluno();
} elseif ($uri == '/api/login/professor' && $method == 'POST') {
    $loginController->VerificaLoginProfessor();
} elseif ($uri == '/api/projetos/cadastrar' && $method == 'POST') {
    $cadastroProjetoController->cadastroProjeto();
} elseif ($uri == '/api/projetos' && $method == 'GET') {
    $cadastroProjetoController->mostrarProjetos();
} else {
    http_response_code(404);
    echo json_encode(["erro" => "Rota não encontrada"]);
}
?>
