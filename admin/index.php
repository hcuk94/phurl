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
print_errors();
$db_query = "WHERE ";
$list = "";
if (is_admin_login() && isset($_GET['list']) && $_GET['list'] == "all") {
	$db_query = "";
	$user = "everyone";
	$list = "all";
} else {
	$db_query .= "api='".$_USER['apiKey']."'";
	$user = $_USER['uname'];
}
$db_start = 0;
$db_result = mysql_query("SELECT id,uname,apiKey FROM ".DB_PREFIX."users");
while ($db_row = mysql_fetch_assoc($db_result)) {
	$userApiLookup[$db_row['apiKey']] = $db_row['uname'];
	if ($db_row['id'] == 1) {
		$userApiLookup[$db_row['apiKey']] = "User not logged in";
	}
}
?>
<div id="panel">
Listing urls created by <?php echo $user; ?>.<br />
<?php 
$db_result = mysql_query("SELECT * FROM ".DB_PREFIX."urls $db_query ORDER BY date_added DESC LIMIT $db_start, 25") or db_die(__FILE__, __LINE__, mysql_error());

echo "<table id=\"url_list\">\n";
    echo "<tr>\n".
         "<td><u>ID</td></u>\n".
         "<td><u>Code</u></td>\n".
         "<td><u>Alias</u></td>\n".
         "<td><u>Long URL</u></td>\n".
         "<td><u>Date Added</u></td>\n".
         "<td><u>View</u></td>\n";
	if ($list == "all") {
		echo "<td><u>User</u></td>\n";
	}
	echo "</tr>\n";

while ($db_row = mysql_fetch_assoc($db_result)) {
    $db_row = array_filter($db_row, "stripslashes");

	$u_api = "";
    extract($db_row, EXTR_OVERWRITE|EXTR_PREFIX_ALL, "u");

    if (empty($u_alias)) {
        $u_alias = "";
    }

    echo 
	  "<tr>\n";
	if (is_admin_login()) {
	  echo "<td>$u_id</td>\n";
	}
         echo "<td>$u_code</td>\n".
         "<td>" . htmlentities($u_alias) . "</td>\n".
         "<td>" . htmlentities($u_url) . "</td>\n".
         "<td>$u_date_added</td>\n".
         "<td><a href=\"/".$u_code."\" target=\"_blank\">Open</a>&nbsp;|&nbsp;<a href=\"/".$u_code."-\" target=\"_black\">Stats</a></td>\n";
	if ($list == "all") {
         echo "<td>".$userApiLookup[$u_api]."</td>\n";
	}
	
         echo "</tr>\n";
unset($u_id, $u_code, $u_alias, $u_url, $u_date_added, $u_api);
}
echo "</table>\n";
?>
<?php if (is_admin_login() && $list != "all") { ?>
<a href="/admin/?list=all">Show all created urls</a>
<?php } ?>
</div>
<?php
if (file_exists("../".get_phurl_option('theme_path') . "footer.php")) {
        include ("../".get_phurl_option('theme_path') . "footer.php");
} else {
        die ("<h2>Could not load theme</h2>");
}
?>
