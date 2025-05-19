<?php
class CadastroUsuarioController {
    public function cadastroAluno() {
        require_once __DIR__ . '/../Config/connection.php';
        $dados = json_decode(file_get_contents('php://input'), true);

        $nome = $dados['nome'] ?? '';
        $email = $dados['email'] ?? '';
        $rm = $dados['rm'] ?? '';

        if(empty($nome) || empty($email) || empty($rm)) {
            http_response_code(400);
            echo json_encode(["erro" => "Preencha todos os campos corretamente."]);
            return;
        }

     
        $stmt = $conn->prepare("INSERT INTO alunos (nome, email, rm) VALUES (:nome, :email, :rm)");
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':rm', $rm);

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
        $nomeprof = $dados['nomeprof'] ?? '';
        $matricula = $dados['matricula'] ?? '';
        $senhaprof = $dados['senhaprof'] ?? '';

        if(empty($nomeprof) || empty($matricula) || empty($senhaprof)) {
            http_response_code(400);
            echo json_encode(["erro" => "Preencha todos os campos corretamente."]);
            return;
        }

  
        $stmt = $conn->prepare("INSERT INTO professor (nomeprof, matricula, senhaprof) VALUES (:nomeprof, :matricula, :senhaprof)");
        $stmt->bindParam(':nomeprof', $nomeprof);
        $stmt->bindParam(':matricula', $matricula);
        $stmt->bindParam(':senhaprof', $senhaprof);

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