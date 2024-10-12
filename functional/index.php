<?php
// index.php
require_once 'config/database.php';
require_once 'helpers/auth.php';
require_once 'routes/user.php';
require_once 'routes/cita.php';

header('Content-Type: application/json; charset=UTF-8');
$requestMethod = $_SERVER['REQUEST_METHOD'];

switch ($requestMethod) {
    case 'POST':
        if (isset($_GET['action']) && $_GET['action'] === 'register') {
            registerUser($pdo);
        } elseif (isset($_GET['action']) && $_GET['action'] === 'login') {
            loginUser($pdo);
        } elseif (isAuthorized()) {
            createCita($pdo);
        } else {
            http_response_code(401);
            echo json_encode(["message" => "Unauthorized"]);
        }
        break;
    case 'GET':
        if (isAuthorized()) {
            getCitas($pdo);
        } else {
            http_response_code(401);
            echo json_encode(["message" => "Unauthorized"]);
        }
        break;
    default:
        http_response_code(405);
        echo json_encode(["message" => "Method Not Allowed"]);
        break;
}
?>

<?php
// INSTRUCTIONS
/*
To use the API, follow these instructions:

1. Setup:
   - Ensure you have a MySQL database named 'erp' with tables 'users' and 'citas'.
   - 'users' table should have columns: id, nombre, apellidos, email, usuario, password, activado.
   - 'citas' table should have columns: id, cita, autor.
   - Update the database credentials in 'config/database.php' as per your environment.

2. Endpoints:

   - Register User:
     Endpoint: POST /index.php?action=register
     Payload: {
       "nombre": "John",
       "apellidos": "Doe",
       "email": "john.doe@example.com",
       "usuario": "johndoe",
       "password": "password123"
     }
     Response: User registered successfully with a token.

   - Login User:
     Endpoint: POST /index.php?action=login
     Payload: {
       "usuario": "johndoe",
       "password": "password123"
     }
     Response: Login successful with a token.

   - Create Cita (requires Authorization):
     Endpoint: POST /index.php
     Headers: Authorization: <token>
     Payload: {
       "cita": "Meeting with client",
       "autor": "John Doe"
     }
     Response: Cita created successfully.

   - Get Citas (requires Authorization):
     Endpoint: GET /index.php
     Headers: Authorization: <token>
     Response: List of all citas.

3. Authorization:
   - After registering or logging in, use the token provided in the response as the 'Authorization' header for protected endpoints.
*/
?>
