
<?php
require_once __DIR__ . '/../Controllers/ExemploController.php';
require_once __DIR__ . '/../Controllers/LoginController.php';
require_once __DIR__ . '/../Controllers/EmailController.php';
require_once __DIR__ . '/../Controllers/CadastroUsuarioController.php';
require_once __DIR__ . '/../Controllers/CadastroProjetosController.php';
require_once __DIR__ . '/../Controllers/PostagemController.php';
require_once __DIR__ . '/../Controllers/NotasController.php';
require_once __DIR__ . '/../Controllers/SenhaUsuarioController.php';

$scriptName = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
$uri = str_replace($scriptName, '', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
$method = $_SERVER['REQUEST_METHOD'];

$exemploController = new ExemploController();
$loginController = new LoginController();
$cadastroProjetoController = new CadastroProjetoController();
$postagemController = new PostagemController();
$cadastroUsuarioController = new CadastroUsuarioController();
$notaController = new NotasController();
$senhaUsuarioController = new SenhaUsuarioController();

if ($uri == '/api' && $method == 'GET') {
    $exemploController->index();
} elseif ($uri == '/api/usuarios' && $method == 'GET') {
    $exemploController->listarUsuarios();
} elseif ($uri == '/api/login/aluno' && $method == 'POST') {
    $loginController->VerificaLoginAluno();
} elseif ($uri == '/api/login/professor' && $method == 'POST') {
    $loginController->VerificaLoginProfessor();
} elseif($uri == '/api/senha/enviar' && $method == 'POST'){
    $senhaUsuarioController->EnviarSenha();
} elseif($uri == '/api/senha/trocar' && $method == 'POST'){
    $senhaUsuarioController->TrocarSenha();
} elseif ($uri == '/api/projetos/cadastrar' && $method == 'POST') {
    $cadastroProjetoController->cadastroProjeto();
} elseif ($uri == '/api/imagem' && $method == 'GET') {
    $postagemController->todasFotos();
} elseif ($uri == '/api/imagemprojeto' && $method == 'POST') {
    $postagemController->fotosPorGrupo();
} elseif ($uri == '/api/postagem/criar' && $method == 'POST') {
    $postagemController->criarPostagem();
} elseif ($uri == '/api/projetos' && $method == 'GET') {
    $cadastroProjetoController->mostrarProjetos();
} elseif ($uri == '/api/projetos/editar' && $method == 'POST') {
    $cadastroProjetoController->atualizarProjeto();
} elseif ($uri == '/api/cadastrar/aluno' && $method == 'POST') {
    $cadastroUsuarioController->cadastroAluno();
} elseif ($uri == '/api/cadastrar/professor' && $method == 'POST') {
    $cadastroUsuarioController->cadastroProfessor();
} else if ($uri == '/api/notas/cadastrar' && $method == 'POST'){
    $notaController->cadastroNota();
} else {
    http_response_code(404);
    echo json_encode(["erro" => "Rota não encontrada"]);
}
?>