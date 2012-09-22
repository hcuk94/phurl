<?php
ini_set('display_errors', '1');
error_reporting(E_ALL);

// Define PHURL to allow includes
define('PHURL', true);

require_once("../includes/config.php");
require_once("../includes/functions.php");
db_connect();
if (is_login()) {
	header('Location: '.get_phurl_option('site_url').'/admin/');
}

//require_once("header.php");
$WORKING_DIR = '../';
if (file_exists("../".get_phurl_option('theme_path') . "header.php")) {
        include ("../".get_phurl_option('theme_path') . "header.php");

} else {
        die ("<h2>Could not load theme</h2>");
}
print_errors();
?>
<?php
if (isset($_POST['form']) && $_POST['form'] == "login") {
	// Remove some unwanted characters
	$uname = mysql_real_escape_string(trim($_POST['uname']));
	$password = mysql_real_escape_string(trim($_POST['password']));

	if (
	(!isset($uname) || $uname == "") || 
	(!isset($password) || $password == "")) {
		if (!isset($uname) || $uname == "") {
			$_ERROR[] = "Please enter your username.<br />";
		} 
		if (!isset($password) || $password == "") {
			$_ERROR[] = "Please enter your password.<br />";
		}
	} else {
		$password = hashPassword($password);
		$db_result = mysql_query("SELECT id,uname,email FROM ".DB_PREFIX."users WHERE `uname`='".$uname."' AND `password`='".$password."';");
		if ($db_result != false && mysql_num_rows($db_result) == 1) {
			$db_row = mysql_fetch_assoc($db_result);
			$dbId = $db_row['id'];
			$dbUname = $db_row['uname'];
			$dbEmail = $db_row['email'];
			$_ERROR[] = "Login complete";
			$session = hash('sha256', hash('sha256', time().SALT3.$dbId.hash('sha1',uniqid().hash('sha1', $password))).$dbUname.SALT4.$dbEmail.time());
			$_SESSION[base64_encode('user')] = $session;
			$ipAddr = $_SERVER['REMOTE_ADDR'];
			clean_old_sessions();
			mysql_query("INSERT INTO ".DB_PREFIX."session (session, uId, ip, time) VALUES ('".$session."', '".$dbId."', '".$ipAddr."', '".time()."')") or die(mysql_error());
			header('Location: '.get_phurl_option('site_url').'/admin/');
		} else {
			$_ERROR[] = "There was an error with your username/password.<br />";
		}
	}

} elseif (isset($_POST['form']) && $_POST['form'] == "register") {

	// Remove some unwanted characters
	$uname = mysql_real_escape_string(trim($_POST['uname']));
	$email = mysql_real_escape_string(trim($_POST['email']));
	$fname = mysql_real_escape_string(trim($_POST['fname']));
	$lname = mysql_real_escape_string(trim($_POST['lname']));
	$password = mysql_real_escape_string(trim($_POST['password']));

	// Make sure everything has been set
	if (
	(!isset($uname) || $uname == "") || 
	(!isset($email) || $email == "") || 
	(!isset($fname) || $fname == "") || 
	(!isset($lname) || $lname == "") || 
	(!isset($password) || $password == "") ||
	(!filter_var($email, FILTER_VALIDATE_EMAIL))) {
		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$_ERROR[] = "Please enter a valid email.<br />";
		} else {
			$_ERROR[] = "Please complete the whole form.<br />";
		}
	} else {
		//$_ERROR[]
		// Check if the username or email is already know to us
		$db_result = mysql_query("SELECT id,uname,email FROM ".DB_PREFIX."users WHERE `uname`='".$uname."' OR `email`='".$email."';");
		if ($db_result != false && mysql_num_rows($db_result) != 0) {
			while ($db_row = mysql_fetch_assoc($db_result)) {
				$takenUname = $db_row['uname'];
				$takenEmail = $db_row['email'];
				if ($takenUsername = $uname) {
					$_ERROR[] = "The selected username is already taken, plase choose another one.<br />";
				}
				if ($takenEmail == $email) {
					$_ERROR[] = "The selected email address is already in our database, please choose another one.<br />";
				}
			}
		} else {
			$passwordNew = hashPassword($password);
//			echo $password."-".$passwordNew."\n";
			$apiKey = apiKeyGen(16);
			$db_result = mysql_query("INSERT INTO ".DB_PREFIX."users (uname, fname, lname, email, password, apikey) VALUES ('".$uname."', '".$fname."', '".$lname."', '".$email."', '".$passwordNew."', '".$apiKey."')") or db_die(__FILE__, __LINE__, mysql_error());
			$_ERROR[] = "Your account has been created, you can now login.";
?>
<?php
		}
	}
} 
?>
<div id="login">
<h2>User Login</h2>
<form method="post" action="admin/login.php">
<?php
if (isset($_POST['form']) && $_POST['form'] == "login") {
	print_errors();
}
?>
<input type="hidden" name="form" value="login">
<table id="user_login">
<tr>
<td><strong>Username:</strong></td>
<td><input type="text" name="uname" size="30" value="" /></td>
</tr>
<tr>
<td><strong>Password:</strong></td>
<td><input type="password" name="password" size="30" value="" /></td>
</tr>
<tr>
<td>&nbsp;</td>
<td><input type="submit" value="Login" /></td>
</tr>
</table>
</form>
</div>
<div id="register">
<h2>Create an account</h2>
<form method="post" action="admin/login.php">
<?php
if (isset($_POST['form']) && $_POST['form'] == "register") {
	print_errors();
}
?>
<input type="hidden" name="form" value="register">
<table id="user_reg">
<tr>
<td><strong>Username:</strong></td>
<td><input type="text" name="uname" size="30" value="" /></td>
</tr>
<td><strong>Email:</strong></td>
<td><input type="text" name="email" size="30" value="" /></td>
</tr>
<td><strong>First name:</strong></td>
<td><input type="text" name="fname" size="30" value="" /></td>
</tr>
<td><strong>Last name:</strong></td>
<td><input type="text" name="lname" size="30" value="" /></td>
</tr>
<tr>
<td><strong>Password:</strong></td>
<td><input type="password" name="password" size="30" value="" /></td>
</tr>
<tr>
<td>&nbsp;</td>
<td><input type="submit" value="Login" /></td>
</tr>
</table>
</form>
</div>
<br clear="all">
<?php
if (file_exists("../".get_phurl_option('theme_path') . "footer.php")) {
        include ("../".get_phurl_option('theme_path') . "footer.php");
} else {
        die ("<h2>Could not load theme</h2>");
}
?>
