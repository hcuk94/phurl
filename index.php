<?php
// (C) Phurl Project 2012

// Define PHURL to allow includes
define('PHURL', true);

// No debugging here, oh dear!
ini_set('display_errors', 0);

//Ensure they've deleted the install directory.
$prefix[0] = '';
$filename = 'install';
if (is_dir($filename)) {
    die ("To get Phurl up and running, you first need to go through the <a href=\"install\">installation wizard</a> which will help you set up your new URL shortener in a matter of moments.<br/><br/>If you've already installed Phurl, then you MUST delete the install directory before it will function.");
}

// include the magic
include "includes/config.php";
include "includes/functions.php";

// connect to the database
db_connect();

if (file_exists(get_phurl_option('theme_path') . "header.php")) {
	include (get_phurl_option('theme_path') . "header.php");
} else {
	die ("<h2>Could not load theme</h2>");
}

include (get_phurl_option('theme_path') . "forms/shorten.php");



if (file_exists(get_phurl_option('theme_path') . "footer.php")) {
	include (get_phurl_option('theme_path') . "footer.php");
} else {
	die ("<h2>Could not load theme</h2>");
}
?>
