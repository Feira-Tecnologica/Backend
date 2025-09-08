<?php
//Aqui é onde a API começa, você cria um objeto do Controller e dentro desse objeto você vai programar as funções 
class CadastroProjetoController
{

    public function AlunosProjeto(){
        require_once __DIR__ . '/../Config/connection.php';
        $dados = json_decode(file_get_contents('php://input'), true);

        $id_projeto = $dados['id_projeto'];

        if (empty($id_projeto)) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Preencha todos os campos obrigatórios.']);
            exit;
        }

        try {
            $stmt = $conn->prepare("
                SELECT nome_aluno
                FROM aluno
                INNER JOIN projeto_aluno
                ON aluno.id_aluno = projeto_aluno.id_aluno WHERE projeto_aluno.id_projeto = :id_projeto;
            ");

            $stmt->bindParam(':id_projeto', $id_projeto);

            if ($stmt->execute()) {
                $alunos = $stmt->fetchAll(PDO::FETCH_ASSOC);
                echo json_encode($alunos, JSON_UNESCAPED_UNICODE);
            } else {
                http_response_code(500);
                echo json_encode(['status' => 'error', 'message' => 'Erro ao puxar alunos do projeto.']);
            }
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Erro no banco de dados: ' . $e->getMessage()]);
        }
    }

    public function OdsProjeto(){
        require_once __DIR__ . '/../Config/connection.php';
        $dados = json_decode(file_get_contents('php://input'), true);

        $id_projeto = $dados['id_projeto'];

        if (empty($id_projeto)) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Preencha todos os campos obrigatórios.']);
            exit;
        }

        try {
            $stmt = $conn->prepare("
                SELECT nome
                FROM ods
                INNER JOIN projeto_aluno_ods
                ON ods.id_ods = projeto_aluno_ods.id_ods WHERE projeto_aluno_ods.id_projeto = :id_projeto;
            ");

            $stmt->bindParam(':id_projeto', $id_projeto);

            if ($stmt->execute()) {
                $ods = $stmt->fetchAll(PDO::FETCH_ASSOC);
                echo json_encode($ods, JSON_UNESCAPED_UNICODE);
            } else {
                http_response_code(500);
                echo json_encode(['status' => 'error', 'message' => 'Erro ao puxar ods do projeto.']);
            }
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Erro no banco de dados: ' . $e->getMessage()]);
        }
    }

    public function cadastroProjeto()
    {
        require_once __DIR__ . '/../Config/connection.php';
        $dados = json_decode(file_get_contents('php://input'), true);

        // Captura os valores enviados no payload
        $titulo_projeto = $dados['titulo_projeto'] ?? '';
        $descricao = $dados['descricao'] ?? '';
        $bloco = $dados['bloco'] ?? '';
        $sala = $dados['sala'] ?? '';
        $posicao = $dados['posicao'] ?? null;
        $orientador = $dados['orientador'] ?? '';
        $turma = $dados['turma'] ?? '';

        // Validação simples (exemplo: alguns campos obrigatórios)
        if (empty($titulo_projeto) || empty($descricao)) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Preencha todos os campos obrigatórios.']);
            exit;
        }

        try {
            $stmt = $conn->prepare("
                    INSERT INTO projeto (
                        titulo_projeto, descricao, bloco, sala, posicao, orientador, turma
                    ) VALUES (
                        :titulo_projeto, :descricao, :bloco, :sala, :posicao, :orientador, :turma
                    )
                ");

            $stmt->bindParam(':titulo_projeto', $titulo_projeto);
            $stmt->bindParam(':descricao', $descricao);
            $stmt->bindParam(':bloco', $bloco);
            $stmt->bindParam(':sala', $sala);
            $stmt->bindParam(':posicao', $posicao, PDO::PARAM_INT);
            $stmt->bindParam(':orientador', $orientador);
            $stmt->bindParam(':turma', $turma);

            if ($stmt->execute()) {
                echo json_encode(['status' => 'success', 'message' => 'Projeto cadastrado com sucesso.']);
            } else {
                http_response_code(500);
                echo json_encode(['status' => 'error', 'message' => 'Erro ao cadastrar projeto.']);
            }
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Erro no banco de dados: ' . $e->getMessage()]);
        }
    }


    public function mostrarProjetos()
    {
        require_once __DIR__ . '/../Config/connection.php';

        //aqui é a mesma coisa, porém dessa vez fazendo um GET invés de um POST,apenas puxando valores do banco 
        $stmt = $conn->prepare("SELECT * FROM projeto");
        $stmt->execute();
        $projetos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($projetos);
    }

    public function atualizarProjeto() {
        require_once __DIR__ . '/../Config/connection.php';
        $dados = json_decode(file_get_contents('php://input'), true);

        $id_projeto = $dados['id_projeto'] ?? null;
        if (empty($id_projeto)) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Informe o id_projeto.']);
            exit;
        }

        // Campos possíveis (exceto PK)
        $colunas = [
            'titulo_projeto' => PDO::PARAM_STR,
            'descricao'      => PDO::PARAM_STR,
            'bloco'          => PDO::PARAM_STR,
            'sala'           => PDO::PARAM_STR,
            'posicao'        => PDO::PARAM_INT,
            'orientador'     => PDO::PARAM_STR,
            'turma'          => PDO::PARAM_STR,
        ];

        // Monta SET dinamicamente apenas com o que veio no payload
        $setParts = [];
        $binds = [];
        foreach ($colunas as $col => $type) {
            if (array_key_exists($col, $dados)) {
                $setParts[] = "$col = :$col";
                $binds[] = ['name' => $col, 'value' => $dados[$col], 'type' => $type];
            }
        }

        if (empty($setParts)) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Nenhum campo para atualizar.']);
            exit;
        }

        try {
            $sql = "UPDATE projeto SET " . implode(', ', $setParts) . " WHERE id_projeto = :id_projeto";
            $stmt = $conn->prepare($sql);

            // Binds dos campos variáveis
            foreach ($binds as $b) {
                if (is_null($b['value'])) {
                    $stmt->bindValue(':' . $b['name'], null, PDO::PARAM_NULL);
                } else {
                    if ($b['type'] === PDO::PARAM_INT) {
                        $stmt->bindValue(':' . $b['name'], (int)$b['value'], PDO::PARAM_INT);
                    } else {
                        $stmt->bindValue(':' . $b['name'], $b['value'], PDO::PARAM_STR);
                    }
                }
            }

            // Bind do identificador
            $stmt->bindValue(':id_projeto', $id_projeto);

            if ($stmt->execute()) {
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Projeto atualizado com sucesso.',
                    'rows_affected' => $stmt->rowCount()
                ]);
            } else {
                http_response_code(500);
                echo json_encode(['status' => 'error', 'message' => 'Erro ao atualizar projeto.']);
            }
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Erro no banco de dados: ' . $e->getMessage()]);
        }
    }

    public function InserirAluno(){
        require_once __DIR__ . '/../Config/connection.php';
        $dados = json_decode(file_get_contents('php://input'), true);

        $id_projeto = $dados['id_projeto'];
        $id_aluno = $dados['id_aluno'];

        if (empty($id_projeto) || empty($id_aluno)) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Preencha todos os campos obrigatórios.']);
            exit;
        }

        try{
            $stmt = $conn->prepare("
                INSERT INTO projeto_aluno (
                    id_projeto, id_aluno
                ) VALUES (
                    :id_projeto, :id_aluno
                )
            ");
            $stmt->bindParam(':id_projeto', $id_projeto);
            $stmt->bindParam(':id_aluno', $id_aluno);

            if ($stmt->execute()) {
                echo json_encode(['status' => 'success', 'message' => 'Aluno inserido no projeto com sucesso.']);
            } else {
                http_response_code(500);
                echo json_encode(['status' => 'error', 'message' => 'Erro ao inserir aluno no projeto.']);
            }
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Erro no banco de dados: ' . $e->getMessage()]);
        }
    }

    public function InserirOds(){
        require_once __DIR__ . '/../Config/connection.php';
        $dados = json_decode(file_get_contents('php://input'), true);

        $id_projeto = $dados['id_projeto'];
        $id_ods = $dados['id_ods'];

        if (empty($id_projeto) || empty($id_ods)) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Preencha todos os campos obrigatórios.']);
            exit;
        }

        try{
            $stmt = $conn->prepare("
                INSERT INTO projeto_ods (
                    id_projeto, id_ods
                ) VALUES (
                    :id_projeto, :id_ods
                )
            ");
            $stmt->bindParam(':id_projeto', $id_projeto);
            $stmt->bindParam(':id_ods', $id_ods);

            if ($stmt->execute()) {
                echo json_encode(['status' => 'success', 'message' => 'Ods inserida no projeto com sucesso.']);
            } else {
                http_response_code(500);
                echo json_encode(['status' => 'error', 'message' => 'Erro ao inserir Ods no projeto.']);
            }
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Erro no banco de dados: ' . $e->getMessage()]);
        }
    }
}

?>
