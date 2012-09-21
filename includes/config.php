<?php
ini_set("display_errors", '1');
error_reporting(E_ALL);
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
define('SALT1', '{7A(|/End@2#o%C#[,96IfM^U*35;!AH57/=37^*>+(K40Gfh2D2:Z1,u|9~z9L9');
define('SALT2', 'IF9E%/<~2,F(h&K{2@=Y)V01u.U$/D.?;SF>dl=-9>%|$He22d(3aLP24^1*d)bI');
define('SALT3', '2lMw?r1Vfk9.w(mH%mJdYN}FpH^e@+O2}+*eU$9!&DY09~&CP)=7Uy1##a+*t8<T');
define('SALT4', 'i$m?r{U6OENyY.7@}.CXJ[Q?).*{.qOnVv~1{H~g[(F&?$|E<}S-[0@3X-<2Ir@');
error_reporting(E_ALL);
$_ERROR = array();

?>
