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
$result = mysql_query("SELECT * from ".DB_PREFIX."urls WHERE BINARY alias='$alias' OR code='$alias'");
$num_rows = mysql_num_rows($result);
if ($num_rows < 1) {
echo "<div id=\"staterror_title\"><h2>Sad Panda.</h2></div><div id=\"staterror_text\"><p style=\"font-size: 10pt;\">The URL you requested doesn't exist.<br/>So we can't provide any stats for it, sadly.<br/>You could always <a href=\"". get_phurl_option('site_url') ."\" >shorten a new URL</a>.<br/>:(</p></div><br/>";
include get_phurl_option('theme_path').'footer.php';
die();
}
$result = mysql_query("SELECT * from ".DB_PREFIX."stats WHERE BINARY alias='$alias'");
$num_rows = mysql_num_rows($result);
if ($num_rows < 1) {
echo "<div id=\"staterror_title\"><h2>Not Just Yet.</h2></div><div id=\"staterror_text\"><p style=\"font-size: 10pt;\">This URL exists, but has had no clicks yet.<br/>Share it around, and you'll see stats here shortly after people start clicking.<br/>:)</p></div>";
include get_phurl_option('theme_path').'footer.php';
die();
}
?>
<h3>Statistics for <a href="<?php echo get_phurl_option('site_url'); ?>/<?php echo $alias ?>"><?php echo get_phurl_option('site_url'); ?>/<?php echo $alias ?></a></h3>
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
<br/><b>Long URL: </b><a href="<?php echo $url ?>"><?php echo $url ?></a>
<div id="dynamicdiv" width="100%">
</div>
</table>