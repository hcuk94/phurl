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
?>
<?php

if (isset($_GET['suspend'])) {
	$sId = mysql_real_escape_string(trim($_GET['suspend']));
	if ($sId == $_USER['id']) {
		$_ERROR[] = "You can't suspend yourself!<br />";
	} else {
		$db_result = mysql_query("SELECT id,type FROM ".DB_PREFIX."users WHERE id='".$sId."'");
		if (mysql_num_rows($db_result) != 1) {
			$_ERROR[] = "Users not found.<br />";
		} else {
			$db_row = mysql_fetch_assoc($db_result);
			if ($db_row['type'] == "a") {
				$_ERROR[] = "User is of type admin!<br />";
			} else {
				$_ERROR[] = "User suspened<br />";
				mysql_query("UPDATE ".DB_PREFIX."users SET suspended='1' WHERE id='".$sId."'");
				mysql_query("DELETE FROM ".DB_PREFIX."session WHERE uId='".$sId."'");
				header('Location: '.get_phurl_option('site_url').'/admin/users.php');
			}
		}
	}
} elseif (isset($_GET['unsuspend'])) {
	$sId = mysql_real_escape_string(trim($_GET['unsuspend']));
	if ($sId == $_USER['id']) {
		$_ERROR[] = "You can't unsuspend yourself! Please dont do these hacks! If you were suspended, you wouldn't be logged in!<br />";
	} else {
		$db_result = mysql_query("SELECT id,type FROM ".DB_PREFIX."users WHERE id='".$sId."'");
		if (mysql_num_rows($db_result) != 1) {
			$_ERROR[] = "Users not found.<br />";
		} else {
			$db_row = mysql_fetch_assoc($db_result);
			if ($db_row['type'] == "a") {
				$_ERROR[] = "User is of type admin!<br />";
			} else {
				$_ERROR[] = "User unsuspened<br />";
				mysql_query("UPDATE ".DB_PREFIX."users SET suspended='0' WHERE id='".$sId."'");
				header('Location: '.get_phurl_option('site_url').'/admin/users.php');
			}
		}
	}
}


?>
<div id="panel">
<?php
print_errors();
?>
<h3>Listing all users</h3>
<table>
<tr>
<th>ID</th>
<th>Username</th>
<th>First name</th>
<th>Last name</th>
<th>API Key</th>
<th>User type</th>
<th>Edit</th>

</tr>
<?php
$page = 1;
if (isset($_GET['page'])) {
	$page = (int)mysql_real_escape_string(trim($_GET['page']));
}
if ($page < 1) {
	$page = 1;
}
$db_result = mysql_query("SELECT COUNT(id) FROM ".DB_PREFIX."users") or db_die(__FILE__, __LINE__, mysql_error());
$db_row    = mysql_fetch_row($db_result);
$db_count  = (int) $db_row[0];
$db_start  = ($page - 1) * 25;
$db_pages  = ceil($db_count / 25);

$db_result = mysql_query("SELECT * FROM ".DB_PREFIX."users ORDER BY id ASC LIMIT $db_start, 25");
while ($row = mysql_fetch_assoc($db_result)) {
if ($row['id'] != 1) {
	echo "<tr>";
	echo "<td>".$row['id']."</td>\n";
	echo "<td>".$row['uname']."</td>\n";
	echo "<td>".$row['fname']."</td>\n";
	echo "<td>".$row['lname']."</td>\n";
	echo "<td>".$row['apiKey']."</td>\n";
	if ($row['type'] == "a") {
		echo "<td>Admin</td>\n";
	} else {
		echo "<td>Normal</td>\n";
	}
	if ($row['id'] != $_USER['id'] && $row['suspended'] == 0) {
		echo "<td><a href=\"admin/users.php?suspend=".$row['id']."\">Suspend account</a></td>\n";
	} elseif ($row['id'] != $_USER['id'] && $row['suspended'] == 1) {
		echo "<td><a href=\"admin/users.php?unsuspend=".$row['id']."\">Un-Suspend</a></td>\n";
	} else {
		echo "<td></td>\n";
	}
	echo "</tr>";
}
}

?>
</table>
</div>
<?php
if (file_exists("../".get_phurl_option('theme_path') . "footer.php")) {
        include ("../".get_phurl_option('theme_path') . "footer.php");
} else {
        die ("<h2>Could not load theme</h2>");
}
?>
