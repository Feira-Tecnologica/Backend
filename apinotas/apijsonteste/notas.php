<?php
// cabeçalho
header("Content-Type: application/json; charset=UTF-8");

$metodo = $_SERVER['REQUEST_METHOD'];

$arquivo = 'notas.json';

if (!file_exists($arquivo)) {
    file_put_contents($arquivo, json_encode([], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

$registros = json_decode(file_get_contents($arquivo), true);

// Função 
function converterMencao($media)
{
    if ($media < 5) return "I";
    elseif ($media < 7) return "R";
    elseif ($media < 9) return "B";
    else return "MB";
}

// Verifica o tipo da requisição
switch ($metodo) {

    // consulta de notas por ID
    case 'GET':
        // Verifica se o ID do aluno foi informado
        if (!isset($_GET['id'])) {
            http_response_code(400);
            echo json_encode(["erro" => "ID do aluno não informado."]);
            exit;
        }

        $id = intval($_GET['id']);
        $encontrado = null;

        // Procura o aluno pelo ID
        foreach ($registros as $registro) {
            if ($registro['id'] == $id) {
                $encontrado = $registro;
                break;
            }
        }

        // Retorna as informações se encontrou
        if ($encontrado) {
            echo json_encode($encontrado, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        } else {
            http_response_code(404);
            echo json_encode(["erro" => "Notas do aluno não encontradas."]);
        }
        break;

    // cases POST e DELETE
    case 'POST':
        // Recebe os dados enviados em JSON
        $dados = json_decode(file_get_contents('php://input'), true);

        // Verificacao se o ID e as notas foram informados
        if (!isset($dados['id']) || !isset($dados['notas']) || count($dados['notas']) !== 3) {
            http_response_code(400);
            echo json_encode(["erro" => "Informe o ID do aluno e exatamente 3 notas."]);
            exit;
        }

        // Calcula a média e a menção
        $id = intval($dados['id']);
        $notas = array_map('floatval', $dados['notas']);
        $media = array_sum($notas) / 3;
        $mencao = converterMencao($media);

        // Verificacao se o aluno já existe
        $atualizado = false;
        foreach ($registros as &$registro) {
            if ($registro['id'] == $id) {
                $registro['notas'] = $notas;
                $registro['media'] = $media;
                $registro['mencao'] = $mencao;
                $atualizado = true;
                break;
            }
        }

        // novo registro se não encontrado
        if (!$atualizado) {
            $registros[] = [
                "id" => $id,
                "notas" => $notas,
                "media" => $media,
                "mencao" => $mencao
            ];
        }

        // salvor os dados no arquivo JSON
        // Se o arquivo não existir, cria um novo
        file_put_contents($arquivo, json_encode($registros, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        // sucess
        echo json_encode([
            "mensagem" => $atualizado ? "Notas atualizadas com sucesso!" : "Notas cadastradas com sucesso!",
            "id" => $id,
            "media" => round($media, 2),
            "mencao" => $mencao
        ]);
        break;

    case 'DELETE':
        // Verificacao do id
        if (!isset($_GET['id'])) {
            http_response_code(400);
            echo json_encode(["erro" => "ID do aluno não informado."]);
            exit;
        }

        $id = intval($_GET['id']);
        $encontrado = false;

        // Procura e remove ID
        foreach ($registros as $i => $registro) {
            if ($registro['id'] == $id) {
                unset($registros[$i]);
                $registros = array_values($registros); // Reorganiza o array
                $encontrado = true;
                break;
            }
        }

        // Retorno da confirmacao
        if ($encontrado) {
            file_put_contents($arquivo, json_encode($registros, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
            echo json_encode(["mensagem" => "Notas removidas com sucesso."]);
        } else {
            http_response_code(404);
            echo json_encode(["erro" => "Notas não encontradas."]);
        }
        break;

    // error
    default:
        http_response_code(405);
        echo json_encode(["erro" => "Método não permitido."]);
        break;
}
