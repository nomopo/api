<?php
// routes/cita.php
require_once "config/database.php";
require_once "helpers/auth.php";

function createCita($pdo)
{
    $data = json_decode(file_get_contents("php://input"), true);
    if (!empty($data["cita"]) && !empty($data["autor"])) {
        $stmt = $pdo->prepare("INSERT INTO citas (cita, autor) VALUES (?, ?)");
        $stmt->execute([$data["cita"], $data["autor"]]);
        echo json_encode(["message" => "Cita created successfully"]);
    } else {
        http_response_code(400);
        echo json_encode(["message" => "Invalid input"]);
    }
}

function getCitas($pdo)
{
    $pdo->exec("SET NAMES utf8mb4");

    $stmt = $pdo->query("SELECT * FROM citas");
    $citas = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Establecer el header para JSON y UTF-8
    header("Content-Type: application/json; charset=UTF-8");

    // Codificar a JSON con opciones para manejar caracteres Unicode
    echo json_encode($citas, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
}
// function getCitas($pdo)
// {
//     $stmt = $pdo->query("SELECT * FROM citas");
//     $citas = $stmt->fetchAll();
//     echo json_encode($citas);
//}
?>
