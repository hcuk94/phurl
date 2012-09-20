<?php
ini_set('display_errors', 0);
?>
<html>
<head>
<title>Phurl Administration</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link rel="stylesheet" type="text/css" href="../assets/admin.css" />
<script type="text/javascript" src="../assets/admin.js"></script>
<style type="text/css">
.style2 {
	font-family: Cambria, Cochin, Georgia, Times, "Times New Roman", serif;
}
</style>
</head>
<body>
<?php if (is_admin_login()): ?>
<img src="http://code.google.com/p/phurl/logo?cct=1278165547" alt="Phurl" height="52" width="116" />

<span class="style2">Administration<hr />
<?php
$updateurl = "http://liveupdate.hencogroup.co.uk/os/phurl/latest.txt";
$fh = fopen($updateurl, 'r');
$version = fread($fh, 3);
fclose($fh);
$current = PHURL_NUMERICVERSION;
if ($version > $current && $version !== $current) {
echo "<center><p style=\"color:green;\">A new version of Phurl is available! Download it at <a href=\"http://code.google.com/p/phurl/downloads/list\">http://code.google.com/p/phurl/downloads/list</a></p></center><hr/>";
} 
elseif ($version < $current && $version !== $current) {
echo "<center><p style=\"color:blue;\">It seems you are running a prerelease version of Phurl. Expect Bugs!</p></center><hr/>";
}
?>
<h2>Search</h2>
<form method="get" action="index.php">
<table id="admin_search">
<tr>
<td><strong>By code or custom alias:</strong></td>
<td><input type="text" name="search_alias" size="30" value="<?php echo @htmlentities($_GET['search_alias']) ?>" /></td>
</tr>
<tr>
<td><strong>By part of URL string:</strong></td>
<td><input type="text" name="search_url" size="30" value="<?php echo @htmlentities($_GET['search_url']) ?>" /></td>
</tr>
<tr>
<td>&nbsp;</td>
<td><input type="submit" value="Search" /></td>
</tr>
</table>
</form>
<hr />
<?php endif; ?>