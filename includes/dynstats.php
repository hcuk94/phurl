<?php
require ("isoregion.php");
require ("config.php");
require ("functions.php");
db_connect();
$alias = $_GET['alias'];
$id = $alias;
$result = mysql_query("SELECT SUM(clicks) AS clicks FROM ".DB_PREFIX."stats WHERE BINARY alias='$alias'");
$total=mysql_fetch_assoc($result);
?>
<br/><b>Clicks: </b>
<?php
$curtot = $total['clicks'];
if ($curtot == "1337") {
	$curtot = "leet";
} 

echo $curtot;
?>
</td>
</tr>
</table><br/>
<b>Regional statistics for <?php echo get_phurl_option('site_url'); ?>/<?php echo $alias ?> (Total <?php echo $curtot; ?> clicks)</b><br />
<?php
$result = mysql_query("SELECT country, clicks FROM ".DB_PREFIX."stats WHERE BINARY alias='$alias' GROUP BY country ORDER BY clicks DESC");
$countries = "";
$numbers = "";
echo "<div id=\"flags\">";
while($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
$countries .= $row['country'] . "|";
$numbers .= $row['clicks'] . ",";
$country = $row['country'];
//echo '<div class="flags"><img src="../flags/png/' . strtolower($iso[$country]) . '.png"> ' . $country . ' ' . $row['count'] . ' click(s) </div>';
}
echo "</div>";
echo "<img src=\"http://chart.apis.google.com/chart?chf=bg,t,bebebe&chco=233a4b%2C5ba1d2&cht=p3&chd=t:" . mb_substr($numbers,0, -1) . "&chs=500x200&chl=" . mb_substr($countries, 0,-1) . "\" />";
$result = mysql_query("SELECT country, clicks FROM ".DB_PREFIX."stats WHERE BINARY alias='$alias' GROUP BY country ORDER BY clicks DESC");
$countries = "";
$numbers = "";
while($row = mysql_fetch_assoc($result)) {
$country = $row['country'];
$countries .= $row['country'] . "|";
$numbers .= $row['clicks'] . ",";
//echo $iso['$country'];
}
echo "<img src=\"http://chart.apis.google.com/chart?chf=bg,lc,bebsbe&cht=t&chs=440x220&chd=t:" . mb_substr($numbers,0, -1) . "&chtm=world&chld=" . mb_substr($countries, 0,-1) . "\" float=\"right\" />";
$result = mysql_query("SELECT * FROM ".DB_PREFIX."urls WHERE BINARY code = '$alias'");
$row = mysql_fetch_array($result);
?>
<br/>
<b>Top 5 countries for this URL:</b><br/>
<?php
$db_result = mysql_query("SELECT * FROM ".DB_PREFIX."stats WHERE BINARY alias='$alias' ORDER BY clicks DESC LIMIT 0, 5") or db_die(__FILE__, __LINE__, mysql_error());
echo "<table style=\"padding: 3px;\" align=\"center\" id=\"url_list\">\n";
while ($db_row = mysql_fetch_assoc($db_result)) {
    $db_row = array_filter($db_row, "stripslashes");
    extract($db_row, EXTR_OVERWRITE|EXTR_PREFIX_ALL, "u");
$lowercountry = strtolower($u_country);
    echo  "<td><img src=\"images/flags/$lowercountry.png\" /> <b>$u_country</b> $u_clicks</td>\n";
unset($u_country, $u_alias, $u_clicks);
}
echo "</table>\n";
?>
