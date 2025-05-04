<?php
    class CadastroProjetoController {
        public function cadastroProjeto() {
            require_once __DIR__ . '/../Config/connection.php';
            $dados = json_decode(file_get_contents('php://input'), true);

            $cod = $dados['cod'] ?? '';
            $id = $dados['id_grupo'] ?? '';
            $integrantes = $dados['integrantes'] ?? '';

            if(empty($cod) || empty($id) || empty($integrantes)) {
                http_response_code(400);
                echo json_encode(["erro" => "Preencha todos os campos corretamente."]);
                return;
            }

            $stmt = $conn->prepare("INSERT INTO projetos (cod, id_grupo, integrantes) VALUES (:cod, :id_grupo, :integrantes)");
            $stmt->bindParam(':cod', $cod);
            $stmt->bindParam(':id_grupo', $id);
            $stmt->bindParam(':integrantes', $integrantes);

            if ($stmt->execute()) {
                http_response_code(201);
                echo json_encode(["mensagem" => "Projeto cadastrado com sucesso."]);
            } else {
                http_response_code(500);
                echo json_encode(["erro" => "Erro ao cadastrar projeto."]);
            }
        }

        public function mostrarProjetos() {
            require_once __DIR__ . '/../Config/connection.php';

            $stmt = $conn->prepare("SELECT * FROM projetos");
            $stmt->execute();
            $projetos = $stmt->fetchAll(PDO::FETCH_ASSOC);

            echo json_encode($projetos);
        }
    }
?>
