<?php
session_start();
$mysql = array();

$_CACHE = array();
$_CACHE['option'] = array();

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
	global $_CACHE;
	if (isset($_CACHE['option'][$option])) {
		return $_CACHE['option'][$option];
	} else {
		$db_result	=	mysql_query("SELECT `value` FROM `".DB_PREFIX."options` WHERE `option` = '$option'") or db_die(__FILE__, __LINE__, mysql_error());
		$db_row		=	mysql_fetch_row($db_result);
		$_CACHE['option'][$option] = $db_row[0];
		return $db_row[0];
	}
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

function insert_url($url, $code, $alias, $apiKey) {
    mysql_query("INSERT INTO ".DB_PREFIX."urls (url, code, alias, date_added, api) VALUES ('$url', '$code', '$alias', NOW(), '$apiKey')") or db_die(__FILE__, __LINE__, mysql_error());

    return mysql_insert_id();
}

function update_url($id, $alias) {
    mysql_query("UPDATE ".DB_PREFIX."urls SET alias = '$alias' WHERE id = '$id'") or db_die(__FILE__, __LINE__, mysql_error());
}

function get_url($alias) {
    $db_result = mysql_query("SELECT url FROM ".DB_PREFIX."urls WHERE BINARY code = '$alias' OR alias = '$alias'") or db_die(__FILE__, __LINE__, mysql_error());
    redirect_expired($alias);
    if (mysql_num_rows($db_result) > 0) {
        $db_row = mysql_fetch_row($db_result);

        return $db_row[0];
    }

    return false;
}
function check_expire($alias) {
	$db_result = mysql_query("SELECT expire_date FROM ".DB_PREFIX."urls WHERE BINARY code = '$alias' OR alias = '$alias'") or db_die(__FILE__, __LINE__, mysql_error());
	$db_row = mysql_fetch_assoc($db_result);
	$expire_date = $db_row['expire_date'];
	if (strtotime($expire_date) < time() && $expire_date != "0000-00-00 00:00:00") {
		mysql_query("DELETE FROM ".DB_PREFIX."urls WHERE BINARY code='$alias' OR alias='$alias'");
		return true;
	}
}
function redirect_expired($alias) {
	if (check_expire($alias) == true) {
		header('Location: '.get_phurl_option('site_url'));
		die();
	}
}
function get_hostname() {
    $data = parse_url(get_phurl_option('site_url'));

    return $data['host'];
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
function hashPassword ($password, $customSalt) {
	$password = hash('sha256', hash('sha256', SALT2.$password.hash('sha1',SALT1.$password).passwordSalt($customSalt)).SALT3);
	return $password;
}
function is_admin_login() {
	is_login();
	global $_USER;
	if (is_login() && $_USER['type'] == 'a') {
		return true;
	} else {
		return false;
	}
}
function require_admin() {
	if (is_admin_login() == false) {
		if (!is_login()) {
			header("Location: ".get_phurl_option("site_url")."/admin/login.php");
		} else {
			header("Location: ".get_phurl_option("site_url")."/admin/");
		}
		exit();
	} else {
		return true;
	}
}
function is_login() {
	if (isset($_SESSION[base64_encode('user')])) {
		$session = $_SESSION[base64_encode('user')];
		$session = mysql_real_escape_string(trim($session));
		clean_old_sessions();
		$db_result = mysql_query("SELECT uId,ip,time FROM ".DB_PREFIX."session WHERE session='".$session."'");
		if (mysql_num_rows($db_result) != 1) {
			// User's session has expired.
			return false;
			session_destroy();
		} else {
			$db_row = mysql_fetch_assoc($db_result);
			$db_result = mysql_query("SELECT * FROM ".DB_PREFIX."users WHERE id='".$db_row['uId']."'");
			$db_row = mysql_fetch_assoc($db_result);
			global $_USER;
			$_USER = array();
			$_USER['id'] = $db_row['id'];
			$_USER['uname'] = $db_row['uname'];
			$_USER['fname'] = $db_row['fname'];
			$_USER['lname'] = $db_row['lname'];
			$_USER['email'] = $db_row['email'];
			$_USER['type'] = $db_row['type'];
			$_USER['apiKey'] = $db_row['apiKey'];
			return true;
		}
	} else {
		// No client side session
		return false;
	}
}
function require_login() {
	if (is_login() == false) {
		header("Location: ".get_phurl_option("site_url")."/admin/login.php");
		exit();
	} else {
		return true;
	}
}
function clean_old_sessions() {
	mysql_query("DELETE FROM ".DB_PREFIX."session WHERE time<='".strtotime("-1 week")."'");
}
function logout() {
	if (isset($_SESSION[base64_encode('user')])) {
		$session = $_SESSION[base64_encode('user')];
		$session = mysql_real_escape_string(trim($session));
		mysql_query("DELETE FROM ".DB_PREFIX."session WHERE session='".$session."'");
		clean_old_sessions();
		session_destroy();
		header("Location: ".get_phurl_option('site_url'));
	}
}
function apiKeyGen($len=16) {
        $key = "";
        $numbers = range(0,9);
	$lcchars = range('a','z');
	$ucchars = range('A','Z');
        while ($len > strlen($key)) {
                $rand = rand(1,3);
                switch ($rand) {
                        case 1: $key .= $numbers[array_rand($numbers)]; break;
                        case 2: $key .= $lcchars[array_rand($lcchars)]; break;
                        case 3: $key .= $ucchars[array_rand($ucchars)]; break;
                }
        }
        return $key;
}
function currentApiKey() {
	if (is_login()) {
		global $_USER;
		return $_USER['apiKey'];
	} else {
		$db_result = mysql_query("SELECT apiKey FROM ".DB_PREFIX."users WHERE id='1'");
		$db_row = mysql_fetch_assoc($db_result);
		return $db_row['apiKey'];
	}
}

if ($_ENABLE_GEO == true) {
if (!file_exists("includes/geoip/geo-ipv6.dat")) die("ERROR! Please run includes/geoip/download.php before continuing.\n");
function maxmind_geoip($ipaddr) {
	if (filter_var($ipaddr, FILTER_VALIDATE_IP)) {
		$gi = geoip_open("includes/geoip/geo-ipv6.dat",GEOIP_STANDARD);
		if (filter_var($ipaddr, FILTER_FLAG_IPV4)) {
			$ipaddr = "::".$ipaddr;
		}
		$cc = geoip_country_code_by_addr_v6($gi, $ipaddr);
		if ($cc == '') {
			$cc = "Unknown";
		}
		return $cc;
	}
}
}
function generate_salt($len) {
	$salt = "";
	$numbers = array("0","1","2","3","4","5","6","7","8","9");
	$lcchars = array("a","b","c","d","e","f","g","h","i","j","k","l","m","n","o","p","q","r","s","t","u","v","w","x","y","z");
	$ucchars = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
	$symbols = array('!','@','#','$','%','^','&','*','(',')','-','~','+','=','|','/','{','}',':',';',',','.','?','<','>','[');
	while ($len > strlen($salt)) {
		$rand = rand(1,5);
		switch ($rand) {
			case 1:
				$salt .= $numbers[array_rand($numbers)];
				break;
			case 2:
				$salt .= $lcchars[array_rand($lcchars)];
				break;
			case 3:
				$salt .= $ucchars[array_rand($ucchars)];
				break;
			case 4:
				$salt .= $symbols[array_rand($symbols)];
				break;
			case 5:
				$salt .= $symbols[array_rand($symbols)];
				break;

		}
	}
	return $salt;
}
function passwordSalt ($custom) {
	$string = sha1($custom.SALT2);

	$i = 0;
	while ($i < strlen($custom) && $i < 5) {
		$char = $custom[0];
		$custom = substr($custom, 1);
		$no = (ord($char) % 4);
		$modifier[$i] = $no+1;
		$i++;
	}

	$salt = "";
	$numbers = array("0","1","2","3","4","5","6","7","8","9","0","1","2","3","4","5","6","7","8","9","0","1","2","3","4","5");
	$lcchars = array("a","b","c","d","e","f","g","h","i","j","k","l","m","n","o","p","q","r","s","t","u","v","w","x","y","z");
	$ucchars = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
	$symbols = array('!','@','#','$','%','^','&','*','(',')','-','~','+','=','|','/','{','}',':',';',',','.','?','<','>','[');

	$i = 0;
	while (strlen($string) > 0) {
		$char = $string[0];
		$no = ord($char) % 26;
		switch ($modifier[$i]) {
			case 1:
				$salt .= $numbers[$no];
				break;
			case 2:
				$salt .= $lcchars[$no];
				break;
			case 3:
				$salt .= $ucchars[$no];
				break;
			case 4:
				$salt .= $symbols[$no];
				break;
		}
		$i++;
		if ($i == 5) {
			$i = 0;
		}
		$string = substr($string, 1);
	}
	return $salt;
}

?>
