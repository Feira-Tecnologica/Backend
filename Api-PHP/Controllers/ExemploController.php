<?php
class ExemploController {
    public function index() {
        echo json_encode(["mensagem" => "API funcionando!"]);
    }

    public function listarUsuarios() {
        require_once __DIR__ . '/../Config/connection.php';

        $stmt = $conn->prepare("SELECT * FROM usuarios");
        $stmt->execute();
        $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($usuarios);
    }
}
?>
