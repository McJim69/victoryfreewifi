<?php
    require("connect.php");

    $id = $_GET["id"];
    $select = $pdo->prepare("SELECT * FROM device WHERE device_id = :sitid ");
    $select->bindParam(":sitid", $id);
    $select->execute();
    $row = $select->fetch(PDO::FETCH_ASSOC);
    $response=$row;
    header('Content-Type: application/json');
    echo json_encode($response);
?>