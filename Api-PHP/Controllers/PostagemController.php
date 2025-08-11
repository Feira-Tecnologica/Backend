<?php

    class PostagemController {
        public function criarPostagem() {
            require_once __DIR__ . '/../Config/connection.php';

            $dados = json_decode(file_get_contents("php://input"), true);

            $id_postagem = $dados['id_postagem'];
            $legenda = $dados['legenda'];
            $data = $dados['data'];
            $id_aluno = $dados['id_aluno'];
            $id_projeto = $dados['id_projeto'];

            if (empty($id_postagem)) {
                http_response_code(400);
                echo json_encode(['status' => 'error', 'message' => 'Preencha todos os campos obrigatórios.']);
                exit;
            }

            // Criação da postagem
            $stmt = $conn->prepare("
                INSERT INTO postagem (
                    id_postagem, legenda, data, id_aluno, id_projeto
                ) VALUES (
                    :id_postagem, :legenda, :data, :id_aluno, :id_projeto
                );
            ");

            $stmt->bindParam(':id_postagem', $id_postagem);
            $stmt->bindParam(':legenda', $legenda);
            $stmt->bindParam(':data', $data);
            $stmt->bindParam(':id_aluno', $id_aluno);
            $stmt->bindParam(':id_projeto', $id_projeto);

            if ($stmt->execute()) {
                http_response_code(201);
                echo json_encode(["mensagem" => "Postagem criada com sucesso."]);

                // Atualização da postagem com imagens
                $this->atualizarPostagemComImagens($id_postagem, $dados['imagens']);
            } else {
                http_response_code(500);
                echo json_encode(["erro" => "Erro ao criar postagem."]);
            }
        }

        private function atualizarPostagemComImagens($id_postagem, $imagens) {
            require_once __DIR__ . '/../Config/connection.php';

            foreach ($imagens as $imagem) {
                $stmt = $conn->prepare("
                    INSERT INTO foto (png) VALUES (:imagem);
                ");
                $stmt->bindParam(':imagem', $imagem);

                if ($stmt->execute()) {
                    $id_foto = $conn->lastInsertId(); // Obtém o ID da imagem inserida

                    // Atualiza a postagem com o ID da imagem
                    $stmtUpdate = $conn->prepare("
                        UPDATE postagem SET id_foto = :id_foto WHERE id_postagem = :id_postagem;
                    ");
                    $stmtUpdate->bindParam(':id_foto', $id_foto);
                    $stmtUpdate->bindParam(':id_postagem', $id_postagem);
                    $stmtUpdate->execute();
                } else {
                    http_response_code(500);
                    echo json_encode(["erro" => "Erro ao inserir imagem."]);
                    return;
                }
            }

            echo json_encode(["mensagem" => "Postagem atualizada com imagens com sucesso."]);
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

        public function deletarPostagem() {
            require_once __DIR__ . '/../Config/connection.php';
            
            // Recebe o ID via JSON no corpo da requisição
            $dados = json_decode(file_get_contents("php://input"), true);
            $id_postagem = $dados['id_postagem'] ?? null;
            
            if (empty($id_postagem)) {
                http_response_code(400);
                echo json_encode(['status' => 'error', 'message' => 'ID da postagem é obrigatório']);
                return;
            }
            
            try {
                $conn->beginTransaction();
                
                // 1. Primeiro deleta as fotos associadas
                $stmt_fotos = $conn->prepare("
                    DELETE FROM foto 
                    WHERE id_foto IN (
                        SELECT id_foto FROM postagem WHERE id_postagem = :id_postagem
                    )
                ");
                $stmt_fotos->bindParam(':id_postagem', $id_postagem, PDO::PARAM_INT);
                $stmt_fotos->execute();
                
                // 2. Depois deleta a postagem
                $stmt_postagem = $conn->prepare("
                    DELETE FROM postagem WHERE id_postagem = :id_postagem
                ");
                $stmt_postagem->bindParam(':id_postagem', $id_postagem, PDO::PARAM_INT);
                $stmt_postagem->execute();
                
                $conn->commit();
                
                if ($stmt_postagem->rowCount() > 0) {
                    http_response_code(200);
                    echo json_encode(['status' => 'success', 'message' => 'Postagem e fotos deletadas com sucesso']);
                } else {
                    http_response_code(404);
                    echo json_encode(['status' => 'error', 'message' => 'Postagem não encontrada']);
                }
                
            } catch (PDOException $e) {
                $conn->rollBack();
                http_response_code(500);
                echo json_encode([
                    'status' => 'error', 
                    'message' => 'Erro ao deletar postagem',
                    'error' => $e->getMessage()
                ]);
            }
        }
    }

?>
