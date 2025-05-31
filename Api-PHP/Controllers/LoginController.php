<?php
    class LoginController {
        public function VerificaLoginAluno() {
            require_once __DIR__ . '/../Config/connection.php';
            $dados_recebidos = json_decode(file_get_contents('php://input'), true);

            $rm = $dados_recebidos['rm'] ?? '';
            $email = $dados_recebidos['email'] ?? '';
            $senha = $dados_recebidos['senha'] ?? '';

            if(empty($rm) || empty($senha) || empty($email)){
                http_response_code(400);
                echo json_encode(["erro" => "Campo obrigatório não preenchido."]);
                return;
            }

            $stmt = $conn->prepare("SELECT * FROM aluno WHERE RM = :rm AND email_institucional = :email");
            $stmt->bindParam(':rm', $rm);
            $stmt->bindParam(':email', $email);
            $stmt->execute();

            $aluno = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($aluno && password_verify($senha, $aluno['senha'])) {
                unset($aluno['senha']);
                echo json_encode([
                    "mensagem" => "Login bem-sucedido",
                    "usuario" => $aluno
                ]);
            } else {
                http_response_code(401);
                echo json_encode(["erro" => "Email ou senha inválidos"]);
            }
        }

        public function VerificaLoginProfessor() {
            require_once __DIR__ . '/../Config/connection.php';
            $dados_recebidos = json_decode(file_get_contents('php://input'), true);

            $matricula = $dados_recebidos['matricula'] ?? '';
            $email = $dados_recebidos['email'] ?? '';

            if(empty($matricula) || empty($email)){
                http_response_code(400);
                echo json_encode(["erro" => "Campo obrigatório não preenchido."]);
                return;
            }

            $stmt = $conn->prepare("SELECT * FROM professor WHERE matricula = :matricula AND email = :email");
            $stmt->bindParam(':matricula', $matricula);
            $stmt->bindParam(':email', $email);
            $stmt->execute();

            $professor = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($professor) {
                echo json_encode([
                    "mensagem" => "Login bem-sucedido",
                    "usuario" => $professor
                ]);
            } else {
                http_response_code(401);
                echo json_encode(["erro" => "Campo preenchido inválido"]);
            }
        }
    }
?>
