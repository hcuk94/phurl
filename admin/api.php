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
?>
<div id="panel">
<h2>Developer options</h2>
Your api key: <?php echo $_USER['apiKey']; ?><br />
<h3>GET <?php echo get_phurl_option('site_url')."/api/create.php"; ?></h3>
<blockquote>
url - The url to be shortend (Required) <br />
apiKey - The API key to be used (Required) <br />
a - Alias if desired (Optional) <br />
responce - Text/Json (Optional) Default is 'Text'<br />
</blockquote>
<h3>Examples:</h3>
<strong>GET <?php echo get_phurl_option('site_url'); ?>/api/create.php?responce=json&apiKey=<?php echo $_USER['apiKey']; ?>&url=phurlproject.org</strong><br />
<blockquote>
Result:<br />
{"code":"200",<br/>
"request":{"responce":"json",<br />
"apiKey":"CI7uqnhvF0V7URl2",<br />
"url":"www.phurlproject.org"},<br />
"url":"http:\/\/phurl3.lo\/PSgCD"}
</blockquote>
<strong>GET <?php echo get_phurl_option('site_url'); ?>/api/create.php?responce=text&apiKey=<?php echo $_USER['apiKey']; ?>&url=phurlproject.org</strong><br />
<blockquote>
Result:<br />
http://phurl3.lo/PSgCD
</blockquote>
<h3>Errors codes:</h3>
01 - Long url not set<br />
02 - Long url not valid<br />
03 - Listed in url blacklist<br />
04 - Link to current phurl install (Could cause redirect loops)<br />
05 - Invalid alias <br />
06 - Alias already used<br />
07 - No API given<br />
08 - Invalid API Key<br /><br />
<strong>JSON Status Codes</strong><br />
200 - Success, Short url to follow to follow<br />
400 - General error code, specific error to follow.<br />
<hr />
</div>
<?php
if (file_exists("../".get_phurl_option('theme_path') . "footer.php")) {
        include ("../".get_phurl_option('theme_path') . "footer.php");
} else {
        die ("<h2>Could not load theme</h2>");
}
?>
