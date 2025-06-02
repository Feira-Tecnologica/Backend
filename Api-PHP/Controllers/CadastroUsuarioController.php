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
        $id = $dados['id'] ?? '';
        $id_turma = $dados['turma'] ?? '';
        $disponivel = false;

        if(empty($nome) || empty($email) || empty($rm)) {
            http_response_code(400);
            echo json_encode(["erro" => "Preencha todos os campos corretamente."]);
            return;
        }

        $senha = password_hash($emailController->enviarCodigoConfirmacao($nome, $email), PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO aluno (id_aluno, nome_aluno, email_institucional, rm, id_turma, disponivel, senha) VALUES (:id, :nome, :email, :rm, :id_turma, :disponivel, :senha)");
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':rm', $rm);
        $stmt->bindParam(':senha', $senha);
        $stmt->bindParam(':id_turma', $id_turma);
        $stmt->bindParam(':disponivel', $disponivel);

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
        $id = $dados['id'] ?? '';

        if(empty($matricula) || empty($email)) {
            http_response_code(400);
            echo json_encode(["erro" => "Preencha todos os campos corretamente."]);
            return;
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


