<?php
class ImageController {
    public function todasFotos() {
        require_once __DIR__ . '/../Config/connection.php';

        $stmt = $conn->prepare("SELECT * FROM imagem");
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

        $stmt = $conn->prepare("SELECT * FROM imagem WHERE id_projeto = :id_projeto");
        $stmt->bindParam(':id_projeto', $id_projeto, PDO::PARAM_INT);
        $stmt->execute();
        $fotos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($fotos);
    }
}
?>