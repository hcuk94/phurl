<?php
require_once "includes/config.php";
require_once "includes/functions.php";
error_reporting(0);
db_connect();
$alias = mysql_real_escape_string(trim($_GET['alias']));
$_SELF = get_phurl_option('site_url')."/".$alias."-";
$result = mysql_query("SELECT * from ".DB_PREFIX."urls WHERE BINARY code='$alias'");
$num_rows = mysql_num_rows($result);
if ($num_rows < 1) {
	header('Location: '.$_SELF);
	die();
}
$db_row = mysql_fetch_assoc($result);
$apiKey = $db_row['api'];
$expire_date = $db_row['expire_date'];
if (is_login() && $apiKey = $_USER['apiKey']) {
	if (isset($_GET['form']) && isset($_GET['expire_date']) && $_GET['form'] == "expire_date") {
		$new_expire_date = mysql_real_escape_string(trim(urldecode($_GET['expire_date'])));
		mysql_query("UPDATE ".DB_PREFIX."urls SET expire_date='$new_expire_date' WHERE code='$alias'") or die(mysql_error());
		header('Location: '.$_SELF);
		die();
	}
}
header('Location: '.$_SELF);
die();
?>
