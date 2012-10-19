<?php
if (isset($_POST['form']) && $_POST['form'] == "expire_date") {
	require_once "includes/config.php";
	require_once "includes/functions.php";
	error_reporting(0);
	db_connect();
	$alias = mysql_real_escape_string(trim($_POST['alias']));
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
		if (isset($_POST['form']) && isset($_POST['expire_date']) && $_POST['form'] == "expire_date") {
			$new_expire_date = mysql_real_escape_string(trim(urldecode($_POST['expire_date'])));
			mysql_query("UPDATE ".DB_PREFIX."urls SET expire_date='$new_expire_date' WHERE code='$alias'") or die(mysql_error());
			header('Location: '.$_SELF);
			die();
		}
	}
} elseif (isset($_POST['form']) && $_POST['form'] == "url_password") {
	require_once "includes/config.php";
	require_once "includes/functions.php";
	error_reporting(0);
	db_connect();
	$alias = mysql_real_escape_string(trim($_POST['alias']));
	$_SELF = get_phurl_option('site_url')."/".$alias."-";
	$result = mysql_query("SELECT * from ".DB_PREFIX."urls WHERE BINARY code='$alias'");
	$num_rows = mysql_num_rows($result);
	if ($num_rows < 1) {
		header('Location: '.$_SELF);
		die();
	}
	$db_row = mysql_fetch_assoc($result);
	$apiKey = $db_row['api'];
	$url = $db_row['url'];
	$newPassword = hashPassword($_POST['password'], hash('sha1', $url));
	$oldPass = $db_row['password'];
	if (is_login() && $apiKey = $_USER['apiKey']) {
		if (isset($_POST['form']) && isset($_POST['password']) && $_POST['form'] == "url_password") {
			mysql_query("UPDATE ".DB_PREFIX."urls SET password='$newPassword' WHERE code='$alias'") or die(mysql_error());
			header('Location: '.$_SELF);
			die();
		}
	}
}
header('Location: '.$_SELF);
die();
?>
