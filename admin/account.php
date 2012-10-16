<?php
ini_set('display_errors', '1');
error_reporting(E_ALL);

// Define PHURL to allow includes
define('PHURL', true);

require_once("../includes/config.php");
require_once("../includes/functions.php");
db_connect();

require_login();
$WORKING_DIR = '../';
if (file_exists("../".get_phurl_option('theme_path') . "header.php")) {
        include ("../".get_phurl_option('theme_path') . "header.php");

} else {
        die ("<h2>Could not load theme</h2>");
}
if (isset($_POST['form']) && $_POST['form'] == "passwordChange") {
	if (!isset($_POST['curPassword']) || !isset($_POST['newPassword1']) || !isset($_POST['newPassword2'])) {
		$_ERROR[] = "One of the required fiels was not set.<br />";
	}
	$db_result = mysql_query("SELECT salt FROM ".DB_PREFIX."users WHERE `id`='".$_USER['id']."';");
	if ($db_result != false && mysql_num_rows($db_result) == 1) {
		$db_row = mysql_fetch_assoc($db_result);
		$customSalt = (string)$db_row['salt'];
	} else {
		die("Salt not found!");
	}

	$curPassword = hashPassword(mysql_real_escape_string(trim($_POST['curPassword'])), $customSalt);
	$newSalt = generate_salt(16);
	$newPassword1 = hashPassword(mysql_real_escape_string(trim($_POST['newPassword1'])), $newSalt);
	$newPassword2 = hashPassword(mysql_real_escape_string(trim($_POST['newPassword2'])), $newSalt);
	if ($newPassword1 != $newPassword2) {
		$_ERROR[] = "The new passwords do not match.<br />";
	} 
	if (count($_ERROR) == 0) {
		$db_result = mysql_query("SELECT id,uname,email FROM ".DB_PREFIX."users WHERE `id`='".$_USER['id']."' AND `password`='".$curPassword."';");
		if (mysql_num_rows($db_result) != 1) {
			$_ERROR[] = "Your password was incorrect<br />";
		} else {
			$db_result = mysql_query("UPDATE ".DB_PREFIX."users SET password='".$newPassword1."', salt='".$newSalt."' WHERE id='".$_USER['id']."'");
	                mysql_query("DELETE FROM ".DB_PREFIX."session WHERE uId='".$_USER['id']."'");
			header('Location: '.get_phurl_option('site_url').'/admin/login.php');
			exit();
		}
	}
}
?>
<div id="panel">
<strong>You will be able to change your account password and other details later.</strong><br /><br />
<?php
print_errors();
echo "Username: ".$_USER['uname']."<br />\n";
echo "First name: ".$_USER['fname']."<br />\n";
echo "Last name: ".$_USER['lname']."<br />\n";
echo "Email: ".$_USER['email']."<br />\n";
switch ($_USER['type']) {
	case 'n':
		echo "User type: Normal<br />\n";
		break;
	case 'a':
		echo "User type: Admin<br />\n";
		break;
}
echo "<br />\nAPI Key: ".$_USER['apiKey']."<br />\n";
?>
<h3>Change your password</h3>
<form method="post" action="admin/account.php">
<table width="360"><tr>
<td>Current password: </td><td><input type="password" size="32" name="curPassword"></td>
</tr><tr>
<td>New password: </td><td><input type="password" size="32" name="newPassword1"></td>
</tr><tr>
<td>New password again: </td><td><input type="password" size="32" name="newPassword2"></td>
</tr><tr>
<td></td><td style="text-align: center;"><input type="submit" value="Change password"></td>
</tr></table>
<div style="width: 360px">
<small>By changing your passwords, all of your current sessions will become invalid, and therefor you will have to login again with the new password.</small>
</div>

<input type="hidden" name="form" value="passwordChange">
</form>
</div>
<?php
if (file_exists("../".get_phurl_option('theme_path') . "footer.php")) {
        include ("../".get_phurl_option('theme_path') . "footer.php");
} else {
        die ("<h2>Could not load theme</h2>");
}
?>
