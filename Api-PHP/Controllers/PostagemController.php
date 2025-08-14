<?php
class PostagemController {
    public function criarPostagem(){
        require_once __DIR__ . '/../Config/connection.php';

        $dados = json_decode(file_get_contents("php://input"), true);

        $legenda = $dados['legenda'];
        $id_usuario = $dados['id_usuario'];
        $pngBase64 = $dados['png'] ?? ''; 

        if(empty($id_usuario)) {
            http_response_code(400);
            echo json_encode(["erro" => "Preencha o ID do usuário."]);
            return;
        }

        $tipo_usuario = substr($id_usuario, 0, strpos($id_usuario, '_'));

        if (strpos($pngBase64, ',') !== false) {
            $pngBase64 = explode(',', $pngBase64)[1];
        }
        $pngBinario = base64_decode($pngBase64);

        try {
            $conn->beginTransaction();

            $stmt = $conn->prepare("
                INSERT INTO postagem (legenda, data, png)
                VALUES (:legenda, CURRENT_TIMESTAMP(), :png)
            ");
            $stmt->bindParam(':legenda', $legenda);
            $stmt->bindParam(':png', $pngBinario, PDO::PARAM_LOB);
            $stmt->execute();

            $id_postagem = $conn->lastInsertId();

            if ($tipo_usuario === 'PRO') {
                $stmtProfessor = $conn->prepare("
                    SELECT id_professor FROM professor WHERE id_professor = :id_usuario
                ");
                $stmtProfessor->bindParam(':id_usuario', $id_usuario);
                $stmtProfessor->execute();
                $professor = $stmtProfessor->fetch(PDO::FETCH_ASSOC);

                if ($professor) {
                    $id_professor = $professor['id_professor'];

                    $stmtPertence = $conn->prepare("
                        INSERT INTO pertence (id_postagem, id_professor)
                        VALUES (:id_postagem, :id_professor)
                    ");
                    $stmtPertence->bindParam(':id_postagem', $id_postagem);
                    $stmtPertence->bindParam(':id_professor', $id_professor);
                    $stmtPertence->execute();
                }
            } else {
                $stmtAluno = $conn->prepare("
                    SELECT id_aluno FROM aluno WHERE id_aluno = :id_usuario
                ");
                $stmtAluno->bindParam(':id_usuario', $id_usuario);
                $stmtAluno->execute();
                $aluno = $stmtAluno->fetch(PDO::FETCH_ASSOC);

                if ($aluno) {
                    $id_aluno = $aluno['id_aluno'];

                    $stmtPertence = $conn->prepare("
                        INSERT INTO pertence2 (id_postagem, id_aluno)
                        VALUES (:id_postagem, :id_aluno)
                    ");
                    $stmtPertence->bindParam(':id_postagem', $id_postagem);
                    $stmtPertence->bindParam(':id_aluno', $id_aluno);
                    $stmtPertence->execute();
                }
            }

            $conn->commit();

            http_response_code(201);
            echo json_encode(["mensagem" => "Post feito com sucesso."]);

        } catch (Exception $e) {
            $conn->rollBack();
            http_response_code(500);
            echo json_encode(["erro" => "Erro ao realizar postagem: " . $e->getMessage()]);
        }
    }


    public function DeletarPostagem() {
        require_once __DIR__ . '/../Config/connection.php';

        $dados = json_decode(file_get_contents("php://input"), true);
        $id_postagem = $dados['id_postagem'];

        if(empty($id_postagem)) {
            http_response_code(400);
            echo json_encode(["erro" => "Preencha o ID da postagem."]);
            return;
        }

        try {
            $stmt = $conn->prepare("DELETE FROM postagem WHERE id_postagem = :id_postagem");
            $stmt->bindParam(':id_postagem', $id_postagem, PDO::PARAM_INT);

            if ($stmt->execute()) {
                echo json_encode(["mensagem" => "Post excluído com sucesso."]);
            } else {
                http_response_code(500);
                echo json_encode(["erro" => "Erro ao excluir postagem."]);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(["erro" => "Erro ao excluir postagem: " . $e->getMessage()]);
        }
    }
}
?>