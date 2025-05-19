<?php
class UploadFotoController {
    public function upload() {
        session_start();
        if (!isset($_SESSION['id'])) {
            http_response_code(401);
            echo json_encode(["erro" => "Usuário não autenticado."]);
            exit;
        }

        require_once __DIR__ . '/../Config/connection.php';

        $legenda = $_POST['legenda'] ?? null;
        $fotos = $_FILES['fotos'] ?? null;

        if (!$legenda || !$fotos) {
            http_response_code(400);
            echo json_encode(["erro" => "Legenda ou fotos não fornecidas."]);
            exit;
        }

        if ($_SESSION['tipo'] === 'alunos') {
            $id_aluno = $_SESSION['id'];
            $query_postagem = "INSERT INTO postagens_alunos (legenda, id_aluno) VALUES (:legenda, :id_aluno)";
            $stmt = $conn->prepare($query_postagem);
            $stmt->execute([':legenda' => $legenda, ':id_aluno' => $id_aluno]);
            $postagem_id = $conn->lastInsertId();

            foreach ($fotos['tmp_name'] as $tmp_name) {
                $foto = file_get_contents($tmp_name);
                $query_foto = "INSERT INTO fotos (foto, id_postagem_aluno) VALUES (:foto, :id_postagem)";
                $stmt = $conn->prepare($query_foto);
                $stmt->execute([':foto' => $foto, ':id_postagem' => $postagem_id]);
            }
        } elseif ($_SESSION['tipo'] === 'professor') {
            $id_professor = $_SESSION['id'];
            $query_postagem = "INSERT INTO postagens_professores (legenda, id_professor) VALUES (:legenda, :id_professor)";
            $stmt = $conn->prepare($query_postagem);
            $stmt->execute([':legenda' => $legenda, ':id_professor' => $id_professor]);
            $postagem_id = $conn->lastInsertId();

            foreach ($fotos['tmp_name'] as $tmp_name) {
                $foto = file_get_contents($tmp_name);
                $query_foto = "INSERT INTO fotos (foto, id_postagem_professor) VALUES (:foto, :id_postagem)";
                $stmt = $conn->prepare($query_foto);
                $stmt->execute([':foto' => $foto, ':id_postagem' => $postagem_id]);
            }
        } else {
            http_response_code(403);
            echo json_encode(["erro" => "Tipo de usuário inválido."]);
            exit;
        }

        echo json_encode(["mensagem" => "Upload realizado com sucesso."]);
    }
}
?>