<?php
// routes/user.php
require_once "config/database.php";
require_once "helpers/token.php";

function registerUser($pdo)
{
    $data = json_decode(file_get_contents("php://input"), true);
    if (
        !empty($data["nombre"]) &&
        !empty($data["apellidos"]) &&
        !empty($data["email"]) &&
        !empty($data["usuario"]) &&
        !empty($data["password"])
    ) {
        $token = generateToken();
        $stmt = $pdo->prepare(
            "INSERT INTO users (nombre, apellidos, email, usuario, password, activado) VALUES (?, ?, ?, ?, ?, ?)"
        );
        $hashedPassword = password_hash($data["password"], PASSWORD_BCRYPT);
        $stmt->execute([
            $data["nombre"],
            $data["apellidos"],
            $data["email"],
            $data["usuario"],
            $hashedPassword,
            1,
        ]);
        echo json_encode([
            "message" => "User registered successfully",
            "token" => $token,
        ]);
    } else {
        http_response_code(400);
        echo json_encode(["message" => "Invalid input"]);
    }
}

function loginUser($pdo)
{
    $data = json_decode(file_get_contents("php://input"), true);
    if (!empty($data["usuario"]) && !empty($data["password"])) {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE usuario = ?");
        $stmt->execute([$data["usuario"]]);
        $user = $stmt->fetch();

        if ($user && password_verify($data["password"], $user["password"])) {
            $token = generateToken();
            echo json_encode([
                "message" => "Login successful",
                "token" => $token,
            ]);
        } else {
            http_response_code(401);
            echo json_encode(["message" => "Invalid credentials"]);
        }
    } else {
        http_response_code(400);
        echo json_encode(["message" => "Invalid input"]);
    }
}
?>