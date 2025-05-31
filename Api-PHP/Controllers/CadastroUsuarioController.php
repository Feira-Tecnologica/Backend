<?php

class CadastroUsuarioController {
    public function cadastroAluno() {
        require_once __DIR__ . '/../Config/connection.php';
        require_once __DIR__ . '/../Controllers/EmailController.php';

        $dados = json_decode(file_get_contents('php://input'), true);
        $emailController = new EmailController();

        $nome = $dados['nome'] ?? '';
        $email = $dados['email'] ?? '';
        $rm = $dados['rm'] ?? '';

        if(empty($nome) || empty($email) || empty($rm)) {
            http_response_code(400);
            echo json_encode(["erro" => "Preencha todos os campos corretamente."]);
            return;
        }

        $senha = password_hash($emailController->enviarCodigoConfirmacao($nome, $email), PASSWORD_DEFAULT);

        $reqIds = $conn->prepare("SELECT id_aluno FROM aluno");
        $reqIds->execute();
        $ids_alunos = $reqIds->fetchAll(PDO::FETCH_ASSOC);

        $id = 1;
        for($i = 0; $i < count($ids_alunos); $i++){
            if ($id <= (int)$ids_alunos[$i]['id_aluno']){
                $id = (int)$ids_alunos[$i]['id_aluno'] + 1;
            }
        }

        $stmt = $conn->prepare("INSERT INTO aluno (id_aluno, nome_aluno, email_institucional, rm, senha) VALUES (:id, :nome, :email, :rm, :senha)");
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':rm', $rm);
        $stmt->bindParam(':senha', $senha);

        if ($stmt->execute()) {
            http_response_code(201);
            echo json_encode(["mensagem" => "Aluno cadastrado com sucesso."]);
        } else {
            http_response_code(500);
            echo json_encode(["erro" => "Erro ao cadastrar aluno."]);
        }
    }

    public function cadastroProfessor() {
        require_once __DIR__ . '/../Config/connection.php';
        $dados = json_decode(file_get_contents('php://input'), true);
        $matricula = $dados['matricula'] ?? '';
        $email = $dados['email'] ?? '';

        if(empty($matricula) || empty($email)) {
            http_response_code(400);
            echo json_encode(["erro" => "Preencha todos os campos corretamente."]);
            return;
        }

        $reqIds = $conn->prepare("SELECT id_professor FROM professor");
        $reqIds->execute();
        $ids_profs = $reqIds->fetchAll(PDO::FETCH_ASSOC);

        $id = 1;
        for($i = 0; $i < count($ids_profs); $i++){
            if ($id <= (int)$ids_profs[$i]['id_professor']){
                $id = (int)$ids_profs[$i]['id_professor'] + 1;
            }
        }
  
        $stmt = $conn->prepare("INSERT INTO professor (id_professor, matricula, email) VALUES (:id, :matricula, :email)");
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':matricula', $matricula);
        $stmt->bindParam(':email', $email);

        if ($stmt->execute()) {
            http_response_code(201);
            echo json_encode(["mensagem" => "Professor cadastrado com sucesso."]);
        } else {
            http_response_code(500);
            echo json_encode(["erro" => "Erro ao cadastrar professor."]);
        }
    }
}
?>


