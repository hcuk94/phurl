<?php
require_once "config.php";
error_reporting(0);
db_connect();
if (empty($alias)) {
echo 'Please specify an alias.';
die;
}
$alias = str_replace("-","",$alias);
$url   = get_url($alias);
$url   = htmlspecialchars($url);
$result = mysql_query("SELECT * from ".DB_PREFIX."urls WHERE BINARY alias='$alias' OR code='$alias'");
$num_rows = mysql_num_rows($result);
if ($num_rows < 1) {
echo "<div id=\"staterror_title\"><h2>Sad Panda.</h2></div><div id=\"staterror_text\"><p style=\"font-size: 10pt;\">The URL you requested doesn't exist.<br/>So we can't provide any stats for it, sadly.<br/>You could always <a href=\"". get_phurl_option('site_url') ."\" >shorten a new URL</a>.<br/>:(</p></div><br/>";
include get_phurl_option('theme_path').'footer.php';
die();
}
$db_row = mysql_fetch_assoc($result);
$apiKey = $db_row['api'];
if ($db_row['password'] != "" && (!is_login() || $apiKey != $_USER['apiKey'])) {
	header('Location: '.get_phurl_option('site_url'));
	exit();
}
$expire_date = $db_row['expire_date'];
$result = mysql_query("SELECT * from ".DB_PREFIX."stats WHERE BINARY alias='$alias'");
$num_rows = mysql_num_rows($result);
if ($num_rows < 1) {
	echo "<div id=\"staterror_title\"><h2>Not Just Yet.</h2></div><div id=\"staterror_text\"><p style=\"font-size: 10pt;\">This URL exists, but has had no clicks yet.<br/>Share it around, and you'll see stats here shortly after people start clicking.<br/>:)</p></div>";
	include get_phurl_option('theme_path').'footer.php';
	die();
}
?>
<h3>Statistics for <a href="<?php echo get_phurl_option('site_url'); ?>/<?php echo $alias ?>"><?php echo get_phurl_option('site_url'); ?>/<?php echo $alias ?></a></h3>
<?php 
if ($db_row['password'] != "") {
?>
<h4>This url is password protected, only you can view the stats.</h4>
<?php
}
?>
<table width="60%" align="center">
<tr>
<td align="center" width="240" height="180"><img src="http://api1.thumbalizr.com/?width=250&url=<?php echo $url; ?>" /></td>
</tr><tr>
<td align="center">
<b>Page Title: </b><?php $file = file($url);
$file = implode("",$file);
if(preg_match("/<title>(.+)<\/title>/i",$file,$m))
print "$m[1]";
else
print "<i>(title not detected)</i>";
?>
<br/><b>Long URL: </b><a href="<?php echo get_phurl_option('site_url')."/".$alias; ?>"><?php echo $url ?></a>
<div id="dynamicdiv" width="100%">
</div>
<?php 
if (is_login() && $apiKey = $_USER['apiKey']) {
if (isset($_POST['form']) && isset($_POST['expire_date']) && $_POST['form'] == "expire_date") {
$new_expire_date = mysql_real_escape_strin(trim($_POST['expire_date']));
if ($new_expire_date != $expire_date) {
	mysql_query("UPDATE ".DB_PREFIX."urls WHERE alias='$alias' OR code='$alias' SET expire_date='$new_expire_date'");
	$expire_date = $new_expire_date;
}
}
?>
<tr><td>
<form method="post" action="update_url.php">
<h4>Update url expire time</h4>
Expire: <input type="text" value="<?php echo $expire_date; ?>" name="expire_date"><br />
<input type="submit" value="Set expire time" name="submit">
<input type="hidden" name="form" value="expire_date">
<input type="hidden" name="alias" value="<?php echo $alias; ?>">
</form>
</td></tr>
<?php 
if ($db_row['password'] != "") {
?>
<tr><td>
<br />
This url already has a password set, if you change it, the old password will become invalid.
</tr></td>
<?php
}
?>
<tr><td>
<form method="post" action="update_url.php">
<h4>Set/Update url password</h4>
Password: <input type="password" name="password"><br />
<input type="submit" value="Set url password" name="submit">
<input type="hidden" name="form" value="url_password">
<input type="hidden" name="alias" value="<?php echo $alias; ?>">
</form>
<small>*This will disable stats to everyone except you!</small>
</tr></td>
<?php
}
?>
</table>
<?php
check_url($url);
?>
