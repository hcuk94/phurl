<?php
header("HTTP/1.0 200 OK");
require_once("includes/config.php");
require_once("includes/functions.php");
require_once ("includes/isoregion.php");
db_connect();

$getalias = trim(mysql_real_escape_string($_SERVER['REQUEST_URI']));
$alias = substr($getalias, 1, strlen($getalias));

if (preg_match("/^[a-zA-Z0-9_-]+\-$/", $alias)) {
  define('PHURL', true);
  include "includes/themes/default/header.php";
  include "includes/stats.php";
  include "includes/themes/default/footer.php";
  die();
} elseif (!preg_match("/^[a-zA-Z0-9_]+$/", $alias)) {
  header("Location: ".SITE_URL, true, 301);
  exit();
} else {

 if (($url = get_url($alias))) {
 $blcheck = file_get_contents("http://gsb.phurlproject.org/lookup.php?url=$url");
	if (trim($blcheck) == "1") {
		define('PHURL', true);
		include "includes/themes/default/header.php";
 	     echo "<div align=\"center\"><div class=\"noooo\"><h2>Blacklisted URL Blocked</h2><p>The page you requested has been identified as malicious. As a result of this, we regret that we can't forward you there.</p><p>Sorry about that.</p></div></div><br/>";
		include "includes/themes/default/footer.php";
		die();
	} else {
    $ipad=$_SERVER['REMOTE_ADDR'];
    $country = file_get_contents("http://api.hostip.info/country.php?ip=$ipad");
    if ($country == 'XX') {
     $country = 'Unknown';
    }
    $result=mysql_query("SELECT count(*) as numrecords FROM ".DB_PREFIX."stats WHERE BINARY alias='$alias' and country='$country'") or die ('An error was encountered. Please refer to phurl support for more info. :('); 
    $row=mysql_fetch_assoc($result);
    if ($row['numrecords'] >= 1){
mysql_query("UPDATE `".DB_PREFIX."stats` SET `clicks` = clicks+1 WHERE `alias` = '$alias' and `country` = '$country';");    
} else {
mysql_query("INSERT INTO ".DB_PREFIX."stats (alias, country, clicks) VALUES ('$alias', '$country', '1');");
} 
header("Location: $url", true, 301);
       exit();
}
}
}
 header("Location: ".SITE_URL, true, 301);
?>
