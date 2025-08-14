<?php
 class UsuarioController{
    public function listarUsuarios() {
        require_once __DIR__ . '/../Config/connection.php';

        $turma = $_GET['turma'] ?? '';

        $stmt = $conn->prepare("SELECT * FROM aluno WHERE id_aluno LIKE :turma AND disponivel = true");
        $stmt->bindValue(':turma', $turma . "_%");
        $stmt->execute();

        $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($usuarios, JSON_UNESCAPED_UNICODE);
    }
 }
?>