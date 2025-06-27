<?php

class NotasController
{
    public function cadastroNota()
    {
        require_once __DIR__ . '/../Config/connection.php';

        $dados = json_decode(file_get_contents('php://input'), true);

        $id_nota = $dados['id_nota'] ?? null;
        $criatividade = floatval($dados['criatividade'] ?? null);
        $capricho = floatval($dados['capricho'] ?? null);
        $abordagem = floatval($dados['abordagem'] ?? null);
        $dominio = floatval($dados['dominio'] ?? null);
        $postura = floatval($dados['postura'] ?? null);
        $oralidade = floatval($dados['oralidade'] ?? null);
        $comentario = $dados['comentario'] ?? '';
        $organizacao = $dados['organizacao'] ?? '';

        if (
            is_null($id_nota) ||
            is_null($criatividade) ||
            is_null($capricho) ||
            is_null($abordagem) ||
            is_null($dominio) ||
            is_null($postura) ||
            is_null($oralidade)
        ) {
            http_response_code(400);
            echo json_encode(["erro" => "Preencha todas as notas obrigatórias."]);
            return;
        }

        // Cálculo da média sem salvar no banco (vai ter que mostrar no front pq n tem campo 'media' em 'notas')
        $media = ($criatividade + $capricho + $abordagem + $dominio + $postura + $oralidade) / 6;
        $mencao = $this->calcularMencao($media);

        $stmt = $conn->prepare("
            INSERT INTO nota (
                id_nota, criatividade, capricho, abordagem, dominio, postura, oralidade, comentario, organizacao
            ) VALUES (
                :id_nota, :criatividade, :capricho, :abordagem, :dominio, :postura, :oralidade, :comentario, :organizacao
            )
        ");

        $stmt->bindParam(':id_nota', $id_nota, PDO::PARAM_INT);
        $stmt->bindParam(':criatividade', $criatividade);
        $stmt->bindParam(':capricho', $capricho);
        $stmt->bindParam(':abordagem', $abordagem);
        $stmt->bindParam(':dominio', $dominio);
        $stmt->bindParam(':postura', $postura);
        $stmt->bindParam(':oralidade', $oralidade);
        $stmt->bindParam(':comentario', $comentario);
        $stmt->bindParam(':organizacao', $organizacao);

        if ($stmt->execute()) {
            http_response_code(201);
            echo json_encode([
                "mensagem" => "Nota cadastrada com sucesso.",
                "media" => round($media, 2),
                "mencao" => $mencao
            ]);
        } else {
            http_response_code(500);
            echo json_encode(["erro" => "Erro ao cadastrar nota."]);
        }
    }

    private function calcularMencao($media)
    {
        if ($media < 5)
            return 'I';     // Insatisfatório
        if ($media < 7)
            return 'R';     // Regular
        if ($media < 9)
            return 'B';     // Bom
        return 'MB';                    // Muito Bom
    }
}
