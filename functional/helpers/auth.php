<?php
// helpers/auth.php
function isAuthorized()
{
    $headers = getallheaders();
    if (isset($headers["Authorization"])) {
        $token = $headers["Authorization"];
        // In a real scenario, validate the token (e.g., check against a database or use JWT)
        return !empty($token);
    }
    return false;
}
?>
