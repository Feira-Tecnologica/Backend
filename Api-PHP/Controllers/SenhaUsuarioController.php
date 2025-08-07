<?php

    class SenhaUsuarioController {
        public function EnviarSenha() {
            require_once __DIR__ . '/../Config/connection.php';
            require_once __DIR__ . '/../Controllers/EmailController.php';

            $dados_recebidos = json_decode(file_get_contents('php://input'), true);

            $email = $dados_recebidos['email'] ?? '';

            $nome = substr($email, 0, strpos( $email, '@' ));

            if(empty($email)){
                http_response_code(400);
                echo json_encode(["erro" => "Campo obrigatório não preenchido."]);
                return;
            }

            $emailController = new EmailController();
            $senha = password_hash($emailController->enviarCodigoConfirmacao($nome, $email), PASSWORD_DEFAULT);
            
            if($senha){
                $stmt = $conn->prepare("UPDATE aluno SET senha = :senha WHERE email_institucional = :email");
                $stmt->bindParam(':senha', $senha);
                $stmt->bindParam(':email', $email);

                if ($stmt->execute()) {
                    http_response_code(200);
                    echo json_encode(["mensagem" => "Email enviado com sucesso"]);
                } else {
                    http_response_code(500);
                    echo json_encode(["erro" => "Erro ao enviar email."]);
                }
            } else {
                http_response_code(401);
                echo json_encode(["erro" => "Email não conseguiu ser enviado"]);
            }
        }

        public function TrocarSenha(){
            require_once __DIR__ . '/../Config/connection.php';

            $dados_recebidos = json_decode(file_get_contents('php://input'), true);

            $email = $dados_recebidos['email'] ?? '';
            $novaSenha = $dados_recebidos['senha_nova'] ?? '';

            if(empty($novaSenha)){
                http_response_code(400);
                echo json_encode(["erro" => "Campo obrigatório não preenchido."]);
                return;
            }

            $novaSenhaCriptografada = password_hash($novaSenha, PASSWORD_DEFAULT);

            $stmt = $conn->prepare("UPDATE aluno SET senha = :senha_nova WHERE email_institucional = :email");
            $stmt->bindParam(':senha_nova', $novaSenhaCriptografada);
            $stmt->bindParam(':email', $email);

            if ($stmt->execute()) {
                http_response_code(200);
                echo json_encode(["mensagem" => "Senha alterada com sucesso"]);
            } else {
                http_response_code(500);
                echo json_encode(["erro" => "Erro ao alterar senha."]);
            }
        }
    }
?>
