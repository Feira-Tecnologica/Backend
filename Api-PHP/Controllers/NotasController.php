<?php

class NotasController
{
    public function cadastroNota()
    {
        require_once __DIR__ . '/../Config/connection.php';

        $dados = json_decode(file_get_contents('php://input'), true);

        $criatividade = floatval($dados['criatividade'] ?? null);
        $capricho = floatval($dados['capricho'] ?? null);
        $abordagem = floatval($dados['abordagem'] ?? null);
        $dominio = floatval($dados['dominio'] ?? null);
        $postura = floatval($dados['postura'] ?? null);
        $oralidade = floatval($dados['oralidade'] ?? null);
        $comentario = $dados['comentario'] ?? '';
        $organizacao = $dados['organizacao'] ?? '';
        $id_professor = $dados['id_professor'] ?? '';
        $id_projeto = $dados['id_projeto'] ?? '';

        if (
            is_null($criatividade) ||
            is_null($capricho) ||
            is_null($abordagem) ||
            is_null($dominio) ||
            is_null($postura) ||
            is_null($oralidade) ||
            is_null($id_professor) ||
            is_null($id_projeto) 
        ) {
            http_response_code(400);
            echo json_encode(["erro" => "Preencha todas as notas obrigatórias."]);
            return;
        }

        // Cálculo da média
        $media = ($criatividade + $capricho + $abordagem + $dominio + $postura + $oralidade + $organizacao) / 7;
        $mencao = $this->calcularMencao($media);

        $stmt = $conn->prepare("
            INSERT INTO nota (
                criatividade, capricho, abordagem, dominio, postura, oralidade, comentario, organizacao, id_professor, id_projeto
            ) VALUES (
                :criatividade, :capricho, :abordagem, :dominio, :postura, :oralidade, :comentario, :organizacao, :id_professor, :id_projeto
            );
        ");

        // Atributos para a média
        $stmt->bindParam(':criatividade', $criatividade);
        $stmt->bindParam(':capricho', $capricho);
        $stmt->bindParam(':abordagem', $abordagem);
        $stmt->bindParam(':dominio', $dominio);
        $stmt->bindParam(':postura', $postura);
        $stmt->bindParam(':oralidade', $oralidade);
        $stmt->bindParam(':organizacao', $organizacao);

        // ID's e comentário do professor
        $stmt->bindParam(':id_professor', $id_professor);
        $stmt->bindParam(':id_projeto', $id_projeto);
        $stmt->bindParam(':comentario', $comentario);

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

    // CRITÉRIO DE NOTAS
    // I: 0 - 2,5
    // R: 2,5 - 5
    // B: 5 - 7,5
    // MB: 7,5 - 10

    private function calcularMencao($media)
    {
        if ($media < 2.5 || $media > 0)
            return 'I';     // Insatisfatório
        if ($media < 7)
            return 'R';     // Regular
        if ($media < 9)
            return 'B';     // Bom
        return 'MB';                    // Muito Bom
    }
}
