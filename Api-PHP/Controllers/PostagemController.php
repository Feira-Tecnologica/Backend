<?php
class PostagemController {
    public function criarPostagem(){
        require_once __DIR__ . '/../Config/connection.php';

        $dados = json_decode(file_get_contents("php://input"), true);

        $id_postagem = $dados['id_postagem'];
        $legenda = $dados['legenda'];
        $data = $dados['data'];
        $id_aluno = $dados['id_aluno'];
        $id_projeto = $dados['id_projeto'];
        $id_foto = $dados['id_foto'];

        if (empty($id_postagem)) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Preencha todos os campos obrigatórios.']);
            exit;
        }

        $stmt = $conn->prepare("
            INSERT INTO postagem (
                id_postagem, legenda, data, id_aluno, id_projeto, id_foto
            ) VALUES (
                :id_postagem, :legenda, :data, :id_aluno, :id_projeto, :id_foto
            );
        ");

        $stmt->bindParam(':id_postagem', $id_postagem);
        $stmt->bindParam(':legenda', $legenda);
        $stmt->bindParam(':data', $data);
        $stmt->bindParam(':id_aluno', $id_aluno);
        $stmt->bindParam(':id_projeto', $id_aluno);
        $stmt->bindParam(':id_foto', $id_foto);

        if ($stmt->execute()) {
            http_response_code(201);
            echo json_encode([
                "mensagem" => "Post feito com sucesso."
            ]);
        } else {
            http_response_code(500);
            echo json_encode(["erro" => "Erro ao realizar postagem."]);
        }
    }
    public function todasFotos() {
        require_once __DIR__ . '/../Config/connection.php';

        $stmt = $conn->prepare("SELECT png FROM foto");
        $stmt->execute();
        $fotos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($fotos);
    }

    public function fotosPorGrupo() {
        require_once __DIR__ . '/../Config/connection.php';

        $dados = json_decode(file_get_contents("php://input"), true);

        $id_projeto = $dados['id'];

        if (empty($id_projeto)) {
            http_response_code(400);
            echo json_encode(["erro" => "ID do projeto é obrigatório."]);
            return;
        }

        $stmt = $conn->prepare("SELECT imagem FROM imagem WHERE id_projeto = :id_projeto");
        $stmt->bindParam(':id_projeto', $id_projeto, PDO::PARAM_INT);
        $stmt->execute();
        $fotos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($fotos);
    }
}
?>