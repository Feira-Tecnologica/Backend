<?php
class TurmaController {
    public function Turmas() {
        require_once __DIR__ . '/../Config/connection.php';

        $stmt = $conn->prepare("SELECT * FROM turma");
        $stmt->execute();

        $turma = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($turma, JSON_UNESCAPED_UNICODE);
    }
}
?>
