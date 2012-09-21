<?php
//$_SESSION[base64_encode('user')] = $session;
include "../includes/config.php";
include "../includes/functions.php";
db_connect();
//echo is_login();
var_dump(is_login());
//echo is_admin_login();
var_dump(is_admin_login());
//print_r($_SESSION);
?>
