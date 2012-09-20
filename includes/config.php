<?php
/* 	
	You should *only* need to edit this file if your database settings need changing.
	All options are stored in the database and should be set in the Phurl admin panel.
*/

// MySQL Setup
define('DB_HOSTNAME', 'localhost');
define('DB_USERNAME', 'phurl');
define('DB_PASSWORD', 'password');
define('DB_NAME',  'phurl3');
define('DB_PREFIX', 'phurl_');


// Do *NOT* mess with anything below
define('URL_PROTOCOLS', 'http|https|ftp|ftps|mailto|news|mms|rtmp|rtmpt|e2dk');
error_reporting(E_ALL);
$_ERROR = array();

?>
