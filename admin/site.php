<?php
ini_set('display_errors', '1');
error_reporting(E_ALL);

// Define PHURL to allow includes
define('PHURL', true);

require_once("../includes/config.php");
require_once("../includes/functions.php");
db_connect();

require_admin();
$WORKING_DIR = '../';
if (file_exists("../".get_phurl_option('theme_path') . "header.php")) {
        include ("../".get_phurl_option('theme_path') . "header.php");

} else {
        die ("<h2>Could not load theme</h2>");
}
$shortCodeType = array('r'=>'random', 'c'=>'consecutive');
$db_result = mysql_query("SELECT * FROM ".DB_PREFIX."options");
while ($db_row = mysql_fetch_assoc($db_result)) {
	$options[$db_row['option']] = $db_row['value'];
}
if (isset($_POST['form']) && isset($_POST['data'])) {
	$form = mysql_real_escape_string(trim(strtolower($_POST['form'])));
	$data = mysql_real_escape_string(trim($_POST['data']));
	if (in_array($form, array('theme_path','phurl_version', 'phurl_numericalversion'))) {
		$_ERROR[] = "The options you tried to edit is restricted on this panel. If you need to edit one of these, please do it manually.<br />";
	} elseif (!in_array($form, array('shortcode_type', 'site_url', 'site_title', 'site_slogan', 'api_limit', 'pr_email', 'user_sett'))) {
		$_ERROR[] = "The option you tried to edit is unknown.<br />";
	} elseif ($data == $options[$form]) {
		$_ERROR[] = "No changes were made<br />";
	} elseif ($form == "shortcode_type" && $data != "r" && $data != "c") {
		$_ERROR[] = "Not a valid shortcode type<br />";
	} elseif ($form == "pr_email" && !filter_var($data, FILTER_VALIDATE_EMAIL)) {
		$_ERROR[] = "Not a valid email!<br />";
	} elseif ($form == "user_sett" && !in_array($data, array('00', '01', '10', '11'))) {
		$_ERROR[] = "Unknown user setting.<br />";
	}
	if (count($_ERROR) == 0) {
		$db_result = mysql_query("UPDATE ".DB_PREFIX."options SET value='".$data."' WHERE ".DB_PREFIX."options.option='".$form."'") or die(mysql_error());
		$_ERROR[] = $form." has been updated. You may need to refresh to see changes.<br />";
		$options[$form] = $data;
	}
}
?>
<div id="panel">
<h3>Site admin</h3>
<?php
/*$updateurl = "http://liveupdate.hencogroup.co.uk/os/phurl/latest.txt";
$fh = fopen($updateurl, 'r');
$version = fread($fh, 3);
fclose($fh);
$current = get_phurl_option('phurl_numericalversion');
if ($version > $current && $version !== $current) {
echo "<center><p style=\"color:green;\">A new version of Phurl is available! Download it at <a href=\"http://code.google.com/p/phurl/downloads/list\">http://code.google.com/p/phurl/downloads/list</a></p></center><hr/>";
} 
elseif ($version < $current && $version !== $current) {
echo "<center><p style=\"color:blue;\">It seems you are running a prerelease version of Phurl. Expect Bugs!</p></center><hr/>";
}*/
print_errors();
?>
<form method="post" action="admin/site.php">
Site title: <input type="text" name="data" value="<?php echo $options['site_title']; ?>" size="32"><br />
<input type="submit" name="submit" value="Update">
<input type="hidden" name="form" value="site_title">
</form>
<br />
<form method="post" action="admin/site.php">
Site slogan: <input type="text" name="data" value="<?php echo $options['site_slogan']; ?>" size="32"><br />
<input type="submit" name="submit" value="Update">
<input type="hidden" name="form" value="site_slogan">
</form>
<br />
<form method="post" action="admin/site.php">
Site url: <input type="text" name="data" value="<?php echo $options['site_url']; ?>" size="32"><br />
<input type="submit" name="submit" value="Update">
<input type="hidden" name="form" value="site_url">
</form>
<br />
<form method="post" action="admin/site.php">
Theme path: <input type="text" name="data" value="<?php echo $options['theme_path']; ?>" size="32" disabled="disabled"><br />
<input type="submit" name="submit" value="Update" disabled="disabled">
<input type="hidden" name="form" value="theme_path">
</form>
<br />
<form method="post" action="admin/site.php">
Short url type: 
<select name="data">
<?php
foreach ($shortCodeType as $value => $name) {
	$selected = "";
	if ($value == $options['shortcode_type']) {
		$selected = "selected=\"selected\" ";
	}
	echo "<option value=\"".$value."\"".$selected.">".$name."</option>\n";
}
?>
</select><br />
<input type="submit" name="submit" value="Update">
<input type="hidden" name="form" value="shortcode_type">
</form>
<br />
<form method="post" action="admin/site.php">
API Hourly limit: <input type="text" name="data" value="<?php echo $options['api_limit']; ?>" size="32"><br />
<input type="submit" name="submit" value="Update">
<input type="hidden" name="form" value="api_limit">
<small>Setting to 0 will disable api limiting. This is not recommened.</small>
</form>
<br />

<form method="post" action="admin/site.php">
Password reset From email: <input type="text" name="data" value="<?php echo $options['pr_email']; ?>" size="32"><br />
<input type="submit" name="submit" value="Update">
<input type="hidden" name="form" value="pr_email">
<?php
$site_host = $options['site_url'];
$site_host = str_replace("http://", "", $site_host);
$site_host = str_replace("https://", "", $site_host);
?>
<small>Must be a valid email, default is no-reply@<?php echo $site_host; ?></small>
</form>
<br />

<form method="post" action="admin/site.php">
User settings: 
<select name="data">
<?php
$userSett = array(
'11'=>'User sign up and public api enabled',
'10'=>'User sign up enabled, public api disabled',
'01'=>'User sign up disabled, public api enabled',
'00'=>'User sign up and public api disabled',);
foreach ($userSett as $value => $name) {
	$selected = "";
	if ($value == $options['user_sett']) {
		$selected = "selected=\"selected\" ";
	}
	echo "<option value=\"".$value."\"".$selected.">".$name."</option>\n";
}
?>
</select><br />
<input type="submit" name="submit" value="Update">
<input type="hidden" name="form" value="user_sett">
</form>
<br />


</div>
<?php
if (file_exists("../".get_phurl_option('theme_path') . "footer.php")) {
        include ("../".get_phurl_option('theme_path') . "footer.php");
} else {
        die ("<h2>Could not load theme</h2>");
}
?>
