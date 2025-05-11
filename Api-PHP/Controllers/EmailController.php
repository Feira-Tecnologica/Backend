<?php
require_once __DIR__ . '/..//PHPMailer/src/PHPMailer.php';
require_once __DIR__ . '/..//PHPMailer/src/SMTP.php';
require_once __DIR__ . '/..//PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class EmailController {
    public function enviarCodigoConfirmacao() {
        header('Content-Type: application/json');
        $dados = json_decode(file_get_contents('php://input'), true);

        $email = $dados['email'] ?? '';
        $nome = $dados['nome'] ?? '';

        if (empty($email) || empty($nome)) {
            http_response_code(400);
            echo json_encode(["erro" => "Campos obrigatórios não informados."]);
            return;
        }

        $codigo = rand(100000, 999999);

        $mail = new PHPMailer(true);

        try {
            // Config SMTP
            $mail->CharSet = 'UTF-8'; 
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'gabriel.gcd08@gmail.com';
            $mail->Password = 'qisfjtznxanyrdhd';

            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            // Email
            $mail->setFrom('gabriel.gcd08@gmail.com', 'FeiraTecnológica');
            $mail->addAddress($email, $nome);
            $mail->isHTML(true);
            $mail->Subject = 'Código de Confirmação';
            $mail->Body    = "<h3>Olá, $nome</h3><p>Seu código de confirmação é <strong>$codigo</strong></p>";
            $mail->AltBody = "Código: $codigo";

            $mail->send();

            echo json_encode(["mensagem" => "Código enviado com sucesso!", "codigo" => $codigo]);

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(["erro" => "Erro ao enviar o e-mail: {$mail->ErrorInfo}"]);
        }
    }
}