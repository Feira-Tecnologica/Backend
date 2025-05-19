
<?php
require_once __DIR__ . '/../Controllers/ExemploController.php';
require_once __DIR__ . '/../Controllers/LoginController.php';
require_once __DIR__ . '/../Controllers/EmailController.php';
require_once __DIR__ . '/../Controllers/CadastroProjetosController.php';
require_once __DIR__ . '/../Controllers/UploadFotoController.php';

$scriptName = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
$uri = str_replace($scriptName, '', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
$method = $_SERVER['REQUEST_METHOD'];

$exemploController = new ExemploController();
$loginController = new LoginController();
$cadastroProjetoController = new CadastroProjetoController();
$emailController = new EmailController();
$imageController = new ImageController();
$uploadFotoController = new UploadFotoController();

if ($uri == '/api' && $method == 'GET') {
    $exemploController->index();
} elseif ($uri == '/api/usuarios' && $method == 'GET') {
    $exemploController->listarUsuarios();
} elseif ($uri == '/api/login/aluno' && $method == 'POST') {
    $loginController->VerificaLoginAluno();
} elseif ($uri == '/api/login/professor' && $method == 'POST') {
    $loginController->VerificaLoginProfessor();
} elseif ($uri == '/api/codigoconfirmacao' && $method == 'POST') {
    $emailController->enviarCodigoConfirmacao();
} elseif ($uri == '/api/projetos/cadastrar' && $method == 'POST') {
    $cadastroProjetoController->cadastroProjeto();
} elseif ($uri == '/api/imagem' && $method == 'GET') {
    $imageController->todasFotos();
} elseif ($uri == '/api/imagemprojeto' && $method == 'POST') {
    $imageController->fotosPorGrupo();
} elseif ($uri == '/api/projetos' && $method == 'GET') {
    $cadastroProjetoController->mostrarProjetos();
} elseif ($uri == '/api/upload-foto' && $method == 'POST') {
    $uploadFotoController->upload();
}else {
    http_response_code(404);
    echo json_encode(["erro" => "Rota não encontrada"]);
}
?>