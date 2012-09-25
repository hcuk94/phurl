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
response - Text/Json (Optional) Default is 'Text'<br />
</blockquote>
<h3>Examples:</h3>
<strong>GET <?php echo get_phurl_option('site_url'); ?>/api/create.php?response=json&apiKey=<?php echo $_USER['apiKey']; ?>&url=phurlproject.org</strong><br />
<blockquote>
Result:<br />
{"code":"200",<br/>
"request":{"response":"json",<br />
"apiKey":"",<br />
"url":"www.phurlproject.org"},<br />
"url":"<?php echo get_phurl_option('site_url'); ?>/PSgCD"}
</blockquote>
<strong>GET <?php echo get_phurl_option('site_url'); ?>/api/create.php?response=text&apiKey=<?php echo $_USER['apiKey']; ?>&url=phurlproject.org</strong><br />
<blockquote>
Result:<br />
http://phurl3.lo/PSgCD
</blockquote>
<h3>Error codes:</h3>
01 - Long url not set<br />
02 - Long url not valid<br />
03 - Listed in url blacklist<br />
04 - Link to current phurl install (Could cause redirect loops)<br />
05 - Invalid alias <br />
06 - Alias already used<br />
07 - No API Key given<br />
08 - Invalid API Key<br />
11 - API Limit exceeded<br /><br />
<strong>JSON Status Codes</strong><br />
200 - Success, Short url to follow to follow<br />
400 - General error code, specific error to follow.<br />
403 - Forbidden, API Limit hit.<br />
<hr />
<h3>GET <?php echo get_phurl_option('site_url')."/api/stats.php"; ?></h3>
<blockquote>
alias - The url to be shortend (Required) <br />
apiKey - The API key to be used (Required) <br />
locationLimit - Number of country codes to return (Optional) Default is '5'<br />
</blockquote>
<h3>Examples:</h3>
<strong>GET <?php echo get_phurl_option('site_url'); ?>/api/stats.php?&apiKey=<?php echo $_USER['apiKey']; ?>&alias=phurl</strong><br />
<blockquote>
Result:<br />
{"code":"200",<br />
"request":{"apiKey":"",<br />
"alias":"phurl"},<br />
"url":"<?php echo get_phurl_option('site_url'); ?>/a",<br />
"clicks":"3",<br />
"location":{<br />
"0":{"country":"Unknown","clicks":"2"},<br />
"1":{"country":"GB","clicks":"1"}},<br />
"data":{"url":"http:\/\/www.phurlproject.org\/",<br />
"code":"a",<br />
"alias":"phurl",<br />
"date_added":"2012-05-29 12:25:00"}}
</blockquote>
<h3>Error codes:</h3>
05 - Invalid alias <br />
07 - No API Key given<br />
08 - Invalid API Key<br />
09 - Alias not found<br />
10 - No stats avalible yet<br />
11 - API Limit exceeded<br /><br />
<strong>JSON Status Codes</strong><br />
200 - Success, Short url to follow to follow<br />
400 - General error code, specific error to follow.<br />
403 - Forbidden, API Limit hit.<br />
<hr />
</div>
<?php
if (file_exists("../".get_phurl_option('theme_path') . "footer.php")) {
        include ("../".get_phurl_option('theme_path') . "footer.php");
} else {
        die ("<h2>Could not load theme</h2>");
}
?>
