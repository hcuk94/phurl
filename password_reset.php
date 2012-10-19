<?php
define('PHURL', true);
ini_set('display_errors', 0);
include "includes/config.php";
include "includes/functions.php";

db_connect();

if (file_exists(get_phurl_option('theme_path') . "header.php")) {
	include (get_phurl_option('theme_path') . "header.php");
} else {
	die ("<h2>Could not load theme</h2>");
}
?>
<h3>Password reset</h3>
<h4>If you have forgotten your password the reset proceedures are listed below.</h4>
<ol>
<li>You enter you email telling us you have forgotten your email.</li>
<li>We email you a link and a tempory password, to ensure its really you.</li>
<li>Once the link has been clicked, the tempory password will become valid</li>
<li>You can then login, and are recommended to change it back to something memorable.</li>
</ol>
<?php
if (isset($_POST['form']) && $_POST['form'] == "passreset1") {
	$email = trim(mysql_real_escape_string($_POST['email']));
	$db_result = mysql_query("SELECT * FROM ".DB_PREFIX."users WHERE email='".$email."' AND id!='1'");
	if (mysql_num_rows($db_result) != 1) {
		?>
<h4>The email you entered was not found in our database</h4>
		<?php
	} else {
		$db_row = mysql_fetch_assoc($db_result);
		$customSalt = $db_row['salt'];
		$password = apiKeyGen(8);
		$passwordNew = hashPassword($password, $customSalt);
		$hash = hash('sha1', hash('sha256', time().hash('sha1', $db_row['email']).SALT3));
		//echo $password."-".$customSalt."-".$passwordNew;
		$site_url = get_phurl_option('site_url');
		$site_host = str_replace("http://", "", $site_url);
		$site_host = str_replace("https://", "", $site_host);
		$ipAddr = $_SERVER['REMOTE_ADDR'];
		$emailBody = "Hello ".$db_row['fname']." ".$db_row['lname'].", 
We've emailed you because a password reset has been requested 
for your email on the Phurl install at ".$site_host.".
If you did not request it, please ignore this email.

If you did please open the link below to step 3 of the password reset proceedures.
".$site_url."/password_reset.php?rs=".$hash."&e=".hash('sha1', $db_row['email'])."
After you have clicked this link, the password below will be set as your account password, 
you are then advised to change it in the account settings menu (My Account->Account Settings).
--------------------------------------
Tempory password: ".$password."
--------------------------------------
The password above will ONLY become valid after clicking the link above.
This password reset is only valid from ".$ipAddr.".


";

		$fromEmail = get_phurl_option('fromEmail');
		if ($fromEmail == "") {
			$fromEmail = "no-reply@".$site_host;
		}
		mail($db_row['email'], "Phurl Password Reset (".$site_url.")", $emailBody, "From: ".$fromEmail) or die('Fatal Error! Failed to send email! Password will not be reset!');
		mysql_query("DELETE FROM ".DB_PREFIX."passReset WHERE uId='".$db_row['id']."'");
		mysql_query("INSERT INTO ".DB_PREFIX."passReset (uId, temp_pass, create_time, ip, hash) 
VALUES ('".$db_row['id']."', '".$passwordNew."', NOW(), '".$ipAddr."', '".$hash."')");


?>
<h4>Step 2:</h4>
Now you need to check your email and wait for one from us with the subject "Phurl Password Reset (<?php echo $site_url; ?>)"<br />
You may need to check you spam/junk folder.
<?php
if (file_exists(get_phurl_option('theme_path') . "footer.php")) {
	include (get_phurl_option('theme_path') . "footer.php");
} else {
	die ("<h2>Could not load theme</h2>");
}
exit();
}
?>
<?php

} elseif (isset($_GET['rs']) && isset($_GET['e'])) {
	$rs = trim(mysql_real_escape_string($_GET['rs']));
	$emailHash = trim(mysql_real_escape_string($_GET['e']));
	$db_result = mysql_query("SELECT * FROM ".DB_PREFIX."passReset WHERE hash='".$rs."' LIMIT 1");
	$db_row = mysql_fetch_assoc($db_result);
	if (mysql_num_rows($db_result) == 1) {
		$db_result1 = mysql_query("SELECT * FROM ".DB_PREFIX."users WHERE id='".$db_row['uId']."' LIMIT 1");
		$db_row1 = mysql_fetch_assoc($db_result1);
		$email = $db_row1['email'];
	}

	if (hash('sha1', $email) != $emailHash || mysql_num_rows($db_result) != 1 || 
$db_row['ip'] != $_SERVER['REMOTE_ADDR'] || strtotime($db_row['create_time']) < strtotime("-1 day")) {
?>
<h4>Step 3:</h4>
This step has failed!<br />
Possible reasons for this:
<ul>
<li>The password reset session expired.</li>
<li>The password reset hash was malformed.</li>
<li>You did not connect from the same IP address as the reset was requested from.</li>
<ul>
Please return to <a href="password_reset.php">Step 1</a> and try again.
<?php
if (file_exists(get_phurl_option('theme_path') . "footer.php")) {
	include (get_phurl_option('theme_path') . "footer.php");
} else {
	die ("<h2>Could not load theme</h2>");
}
exit();
?>
<?php
	}
	mysql_query("UPDATE ".DB_PREFIX."users SET password='".$db_row['temp_pass']."' WHERE id='".$db_row['uId']."'");
	mysql_query("DELETE FROM ".DB_PREFIX."passReset WHERE uId='".$db_row['uId']."'");
	$site_url = get_phurl_option('site_url');
	$site_host = str_replace("http://", "", $site_url);
	$site_host = str_replace("https://", "", $site_host);
	$ipAddr = $_SERVER['REMOTE_ADDR'];
	$db_row = $db_row1;
	$emailBody = "Hello ".$db_row['fname']." ".$db_row['lname'].", 
Your password has now been reset on the Phurl install at ".$site_host.".
This action was performed by ".$ipAddr."
";
	$fromEmail = get_phurl_option('fromEmail');
	if ($fromEmail == "") {
		$fromEmail = "no-reply@".$site_host;
	}
	mail($db_row['email'], "Phurl Password Reset (".$site_url.")", $emailBody, "From: ".$fromEmail) or die('Fatal Error! Failed to send email! Password will not be reset!');
?>
<h4>Step 4:</h4>
Your password has been reset.<br />
You can now login with the password sent to you in the first email.<br />
We recommend that you change your password  after you have logged in.
<?php
if (file_exists(get_phurl_option('theme_path') . "footer.php")) {
	include (get_phurl_option('theme_path') . "footer.php");
} else {
	die ("<h2>Could not load theme</h2>");
}
exit();
}
?>
<h4>Step 1:</h4>
<form action="password_reset.php" method="post">
Enter your email: <br /><input type="text" name="email"><br /><br />
<input type="submit" value="Reset password">
<input type="hidden" name="form" value="passreset1">
</form>
<br />
<?php
if (file_exists(get_phurl_option('theme_path') . "footer.php")) {
	include (get_phurl_option('theme_path') . "footer.php");
} else {
	die ("<h2>Could not load theme</h2>");
}
?>
