<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['nome']) || !isset($data['descricao']) || !isset($data['objetivo']) || !isset($data['justificativa'])) {
        http_response_code(400);
        exit;
    }

    $sql = "INSERT INTO projetos (nome, descricao, objetivo, justificativa)
            VALUES (:nome, :descricao, :objetivo, :justificativa)";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':nome', $data['nome']);
    $stmt->bindParam(':descricao', $data['descricao']);
    $stmt->bindParam(':objetivo', $data['objetivo']);
    $stmt->bindParam(':justificativa', $data['justificativa']);

    $stmt->execute();

    http_response_code(200);
    exit;
} else {
    $sql = "SELECT p.id, p.nome, p.descricao, p.objetivo, p.justificativa
            FROM projetos p
            ORDER BY p.id";

    $stmt = $pdo->query($sql);

    $projetos = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $projeto_id = $row['id'];

        if (!isset($projetos[$projeto_id])) {
            $projetos[$projeto_id] = [
                'id' => $row['id'],
                'nome' => $row['nome'],
                'descricao' => $row['descricao'],
                'objetivo' => $row['objetivo'],
                'justificativa' => $row['justificativa']
            ];
        }
    }

    echo json_encode(array_values($projetos), JSON_PRETTY_PRINT);
}
