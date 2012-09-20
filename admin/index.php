<?php
session_start();
require_once("../config.php");
require_once("../functions.php");

if (!is_admin_login()) {
    header("Location: login.php", true, 301);
    exit();
}

require_once("header.php");

db_connect();



$delete_id = (int) @$_GET['delete_id'];

if ($delete_id > 0) {
    mysql_query("DELETE FROM ".DB_PREFIX."urls WHERE id = '$delete_id'") or db_die(__FILE__, __LINE__, mysql_error());
}

$page = (int) @$_GET['page'];

if ($page < 1) {
    $page = 1;
}

$db_query = "1 AND ";

$search_alias = mysql_real_escape_string(@$_GET['search_alias']);
$search_url   = mysql_real_escape_string(@$_GET['search_url']);

if (!empty($search_alias)) {
    $db_query .= "(code = '$search_alias' OR alias = '$search_alias') AND ";
}

if (!empty($search_url)) {
    $db_query .= "url LIKE '%$search_url%' AND ";
}

$db_query  = substr($db_query, 0, -5);

$db_result = mysql_query("SELECT COUNT(id) FROM ".DB_PREFIX."urls WHERE $db_query") or db_die(__FILE__, __LINE__, mysql_error());
$db_row    = mysql_fetch_row($db_result);
$db_count  = (int) $db_row[0];
$db_start  = ($page - 1) * 25;
$db_pages  = ceil($db_count / 25);

$db_result = mysql_query("SELECT * FROM ".DB_PREFIX."urls WHERE $db_query ORDER BY date_added DESC LIMIT $db_start, 25") or db_die(__FILE__, __LINE__, mysql_error());

echo "<table id=\"url_list\">\n";
    echo "<tr>\n".
         "<td><u>ID</td></u>\n".
         "<td><u>Code</u></td>\n".
         "<td><u>Alias</u></td>\n".
         "<td><u>Long URL</u></td>\n".
         "<td><u>Date Added</u></td>\n".
         "<td><u>Delete</u></td>\n".
	  "</tr>\n";

while ($db_row = mysql_fetch_assoc($db_result)) {
    $db_row = array_filter($db_row, "stripslashes");

    extract($db_row, EXTR_OVERWRITE|EXTR_PREFIX_ALL, "u");

    if (empty($u_alias)) {
        $u_alias = "";
    }

    echo 
	  "<tr>\n".
	  "<td>$u_id</td>\n".
         "<td>$u_code</td>\n".
         "<td>" . htmlentities($u_alias) . "</td>\n".
         "<td>" . htmlentities($u_url) . "</td>\n".
         "<td>$u_date_added</td>\n".
         "<td><a href=\"javascript:delete_url($u_id);\">Delete</a></td>\n".
         "</tr>\n";
unset($u_id, $u_code, $u_alias, $u_url, $u_date_added);
}

echo "</table>\n";

if ($db_count > 25) {
    echo "<p>\n";

    if ($page > 1) {
        echo "<a href=\"index.php?page=".($page - 1)."\">&laquo; Prev</a> ";
    }

    if ($page < $db_pages) {
        echo "<a href=\"index.php?page=".($page + 1)."\">Next &raquo;</a>";
    }

    echo "</p>\n";
}

require_once("footer.php");

