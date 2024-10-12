<?php
// controllers/CitaController.php
require_once "config/Database.php";
require_once "helpers/Auth.php";

class CitaController
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function createCita()
    {
        $data = json_decode(file_get_contents("php://input"), true);
        if (!empty($data["cita"]) && !empty($data["autor"])) {
            $stmt = $this->pdo->prepare(
                "INSERT INTO citas (cita, autor) VALUES (?, ?)"
            );
            $stmt->execute([$data["cita"], $data["autor"]]);
            echo json_encode(["message" => "Cita created successfully"]);
        } else {
            http_response_code(400);
            echo json_encode(["message" => "Invalid input"]);
        }
    }

    public function getCitas()
    {
        $stmt = $this->pdo->query("SELECT * FROM citas");
        $citas = $stmt->fetchAll();
        echo json_encode($citas, JSON_UNESCAPED_UNICODE);
    }
}
?>
