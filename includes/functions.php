<?php
$mysql = array();

function db_die($filename, $line, $message) {
    die("File: $filename<br />Line: $line<br />Message: $message");
}

function db_ins_die($filename, $line, $message) {
    die('<p style="color:red;">Phurl Installation Wizard failed to connect to the database using the specified credentials. Please go back and try again.</p>');
}

function db_connect() {
 global $mysql;
     $mysql['connection'] = mysql_connect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD) or db_die(__FILE__, __LINE__, mysql_error());

 if (!$mysql['connection']) {
  db_die(__FILE__, __LINE__, mysql_error());
 }

     $mysql['database'] = mysql_select_db(DB_NAME) or db_die(__FILE__, __LINE__, mysql_error());

 if (!$mysql['database']) {
  db_die(__FILE__, __LINE__, mysql_error());
 }
}

function db_ins_connect() {
    mysql_connect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD) or db_ins_die(__FILE__, __LINE__, mysql_error());
    mysql_select_db(DB_NAME) or db_ins_die(__FILE__, __LINE__, mysql_error());
}

function get_last_number() {
    $db_result = mysql_query("SELECT last_number FROM ".DB_PREFIX."settings") or db_die(__FILE__, __LINE__, mysql_error());
    $db_row    = mysql_fetch_row($db_result);

    return $db_row[0];
}

function get_phurl_option($option) {
	$db_result	=	mysql_query("SELECT `value` FROM `".DB_PREFIX."options` WHERE `option` = '$option'") or db_die(__FILE__, __LINE__, mysql_error());
	$db_row		=	mysql_fetch_row($db_result);
	return $db_row[0];
}

function increase_last_number() {
    mysql_query("UPDATE ".DB_PREFIX."settings SET last_number = (last_number + 1)") or db_die(__FILE__, __LINE__, mysql_error());

    return (mysql_affected_rows() > 0) ? true : false;
}

function code_exists($code) {
    $db_result = mysql_query("SELECT COUNT(id) FROM ".DB_PREFIX."urls WHERE BINARY code = '$code'") or db_die(__FILE__, __LINE__, mysql_error());
    $db_row    = mysql_fetch_row($db_result);

    return ($db_row[0] > 0) ? true : false;
}

function alias_exists($alias) {
    $db_result = mysql_query("SELECT COUNT(id) FROM ".DB_PREFIX."urls WHERE BINARY alias = '$alias'") or db_die(__FILE__, __LINE__, mysql_error());
    $db_row    = mysql_fetch_row($db_result);

    return ($db_row[0] > 0) ? true : false;
}

function url_exists($url) {
    $db_result = mysql_query("SELECT id, code, alias FROM ".DB_PREFIX."urls WHERE url LIKE '$url'") or db_die(__FILE__, __LINE__, mysql_error());

    if (mysql_num_rows($db_result) > 0) {
        return mysql_fetch_row($db_result);
    }

    return false;
}

function generate_code($number) {
    $out   = "";
    $codes = "abcdefghjkmnpqrstuvwxyz23456789ABCDEFGHJKMNPQRSTUVWXYZ";

    while ($number > 53) {
        $key    = $number % 54;
        $number = floor($number / 54) - 1;
        $out    = $codes{$key}.$out;
    }

    return $codes{$number}.$out;
}

function generate_code_rand() {
$len = 5;
$short = "";
$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
$charslen = strlen($chars);
for ($i=0; $i<$len; $i++)
{
        $rnd = rand(0, $charslen);
        $short .= substr($chars, $rnd, 1);
}
return $short;
}

function insert_url($url, $code, $alias) {
    mysql_query("INSERT INTO ".DB_PREFIX."urls (url, code, alias, date_added) VALUES ('$url', '$code', '$alias', NOW())") or db_die(__FILE__, __LINE__, mysql_error());

    return mysql_insert_id();
}

function update_url($id, $alias) {
    mysql_query("UPDATE ".DB_PREFIX."urls SET alias = '$alias' WHERE id = '$id'") or db_die(__FILE__, __LINE__, mysql_error());
}

function get_url($alias) {
    $db_result = mysql_query("SELECT url FROM ".DB_PREFIX."urls WHERE BINARY code = '$alias' OR alias = '$alias'") or db_die(__FILE__, __LINE__, mysql_error());

    if (mysql_num_rows($db_result) > 0) {
        $db_row = mysql_fetch_row($db_result);

        return $db_row[0];
    }

    return false;
}

function get_hostname() {
    $data = parse_url(get_phurl_option('site_url'));

    return $data['host'];
}

function get_domain() {
    $hostname = get_hostname();

    preg_match("/\.([^\/]+)/", $hostname, $domain);

    return $domain[1];
}

function print_errors() {
    global $_ERROR;

    if (count($_ERROR) > 0) {
        echo "<span id=\"error\">\n";

        foreach ($_ERROR as $key => $value) {
            echo "$value\n";
        }

        echo "</span>\n";
    }
}
function is_admin_login() {
    if (@$_SESSION['admin'] == 1) {
        return true;
    }

    return false;
    }
function is_login() {
return false;
}
