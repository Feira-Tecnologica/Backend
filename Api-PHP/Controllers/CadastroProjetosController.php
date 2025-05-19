<?php
//Aqui é onde a API começa, você cria um objeto do Controller e dentro desse objeto você vai programar as funções 
    class CadastroProjetoController {
        public function cadastroProjeto() {
            //aqui é feita a conexão com o banco 
            //caso seja nescessário mudar a conectado com o banco, é nescessário mexer no .ENV
            require_once __DIR__ . '/../Config/connection.php';
            $dados = json_decode(file_get_contents('php://input'), true);

            //aqui se criam as variáveis 
            //por ser um insert os valores serão atribuídos depois,seja via FORMS no front,ou através do JSON no teste da API
            $cod = $dados['cod'] ?? '';
            $id = $dados['id_grupo'] ?? '';
            $integrantes = $dados['integrantes'] ?? '';

            //retorna um erro caso os valores não sejam preenchidos corretamente 
            if(empty($cod) || empty($id) || empty($integrantes)) {
                http_response_code(400);
                echo json_encode(["erro" => "Preencha todos os campos corretamente."]);
                return;
            }

            //aqui é feito o insert na tabela do banco
            $stmt = $conn->prepare("INSERT INTO projetos (cod, id_grupo, integrantes) VALUES (:cod, :id_grupo, :integrantes)");
            //o ':cod' faz uma réplica do valor $cod através do bindParam
            $stmt->bindParam(':cod', $cod);
            $stmt->bindParam(':id_grupo', $id);
            $stmt->bindParam(':integrantes', $integrantes);
            //basicamente aquilo as variáveis com : na frente está pegando os valores das variáveis $ e inserindo no banco

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

            //aqui é a mesma coisa, porém dessa vez fazendo um GET invés de um POST,apenas puxando valores do banco 
            $stmt = $conn->prepare("SELECT * FROM projetos");
            $stmt->execute();
            $projetos = $stmt->fetchAll(PDO::FETCH_ASSOC);

            echo json_encode($projetos);
        }
    }
?>
