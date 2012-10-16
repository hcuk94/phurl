<?php 

ini_set("display_errors", '1');
error_reporting(E_ALL);

include "config.php";
include "functions.php";
/*$url = "http://samfty.com";
$password = "hello";
echo hashPassword($password, hash('sha1', $url))."\n\n";*/


//exit();
$password = "helloworld";
$customSalt = generate_salt(16);
$customSalt = "";
echo $customSalt."\n\n";

echo hashPassword($password, $customSalt);

?>


