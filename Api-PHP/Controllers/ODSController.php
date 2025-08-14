<?php
 class ODSController{
    public function listarODS() {
        require_once __DIR__ . '/../Config/connection.php';

        $stmt = $conn->prepare("SELECT * FROM ods");
        $stmt->execute();

        $ods = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($ods, JSON_UNESCAPED_UNICODE);
    }
 }
?>