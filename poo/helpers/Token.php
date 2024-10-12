<?php
// helpers/Token.php
class Token
{
    public static function generate()
    {
        return bin2hex(random_bytes(16));
    }
} ?>

?>
