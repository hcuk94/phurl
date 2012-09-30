<?php 

ini_set("display_errors", '1');
error_reporting(E_ALL);

include "config.php";
include "functions.php";

$password = "password";
$customSalt = generate_salt(16);
echo $customSalt."\n\n";

echo hashPassword($password, $customSalt);

?>


