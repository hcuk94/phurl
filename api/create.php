<?php
require_once("../includes/config.php");
require_once("../includes/functions.php");
ini_set('display_errors', 0);
$prefix[0] = '';
db_connect();
$responce = "text";
if (isset($_GET['responce']) && ($_GET['responce'] == "json" || $_GET['responce'] == "text")) {
	$responce = mysql_real_escape_string(trim($_GET['responce']));
} 
if (isset($_GET['apiKey']) && isset($_GET['url'])) {
	if (isset($_GET['a'])) {
		$alias = mysql_real_escape_string(trim($_GET['a']));
	} 
	$apiKey = mysql_real_escape_string(trim($_GET['apiKey']));
	$url = mysql_real_escape_string(trim($_GET['url']));
    
    if (!preg_match("/^(".URL_PROTOCOLS.")\:\/\//i", $url)) {
		$prefix = explode(":", $url);
		if ($prefix[0] == 'mailto') {
			$url = $url;
		} else {
        $url = "http://".$url;
		}
    }

    $last = $url[strlen($url) - 1];

    if ($last == "/") {
        $url = substr($url, 0, -1);
    }

    $data = @parse_url($url);
		if ($prefix[0] == 'mailto') {
			$data['scheme'] = 'mailto';
			$data['host'] = 'none';
		}
$db_result = mysql_query("SELECT id,apiKey FROM ".DB_PREFIX."users");
while ($db_row = mysql_fetch_assoc($db_result)) {
        $validApiKey[$db_row['apiKey']] = '1';
}

    if (strlen($url) == 0) {
        $_ERROR[] = "01";
    }
    else if (empty($data['scheme']) || empty($data['host'])) {
        $_ERROR[] = "02";
    }
    else if (!isset($validApiKey[$apiKey]) || $validApiKey[$apiKey] != 1) {
	$_ERROR[] = "08";
    }
    else {
	$blcheck = file_get_contents("http://gsb.phurlproject.org/lookup.php?url=$url");
	if (trim($blcheck) == "1") {
 	     $_ERROR[] = "03";
	}
        $hostname = get_hostname();
        $domain   = get_domain();
        if (preg_match("/($hostname|$domain)/i", $data['host'])) {
            $_ERROR[] = "04";
        }
    }

    if (strlen($alias) > 0) {
        if (!preg_match("/^[a-zA-Z0-9_-]+$/", $alias)) {
            $_ERROR[] = "05";
        }
        else if (code_exists($alias) || alias_exists($alias)) {
            $_ERROR[] = "06";
        }
    }
//	print_errors();
    if (count($_ERROR) == 0) {
        $create = true;

        if (($url_data = url_exists($url))) {
            $create    = false;
            $id        = $url_data[0];
            $code      = $url_data[1];
            $old_alias = $url_data[2];

            if (strlen($alias) > 0) {
                if ($old_alias != $alias) {
                    $create = true;
                }
            }
        }

        if ($create) {
            do {
				$sctype = get_phurl_option('shortcode_type');
					if ($sctype = "r") {
						$code = generate_code_rand();
					} else {
						$code = generate_code(get_last_number());

						if (!increase_last_number()) {
							die("System error!");
						}
					}
                if (code_exists($code) || alias_exists($code)) {
                    continue;
                }

                break;
            } while (1);

            $id = insert_url($url, $code, $alias, $apiKey);
        }

        if (strlen($alias) > 0) {
            $code = $alias;
        }

        $short_url = get_phurl_option('site_url')."/".$code;

//        $_GET['url']   = "";
//        $_GET['alias'] = "";
	if ($responce == "json") {
		$json = array('code'=>'200', 'request'=>$_GET, 'url'=>$short_url);
		echo json_encode($json, JSON_FORCE_OBJECT);
		exit();
	}
	if ($responce == "text") {
		echo "$short_url\n";
		exit();
	}
    }
}
if (!isset($_GET['apiKey'])) {
	$_ERROR[] = "07";
}
if (!isset($_GET['url'])) {
	$_ERROR[] = "01";
}
if ($responce == "json") {
	echo json_encode(array('code'=>'400', 'error'=>$_ERROR), JSON_FORCE_OBJECT);
}
if ($responce == "text") {
	foreach ($_ERROR as $errorCode) {
		echo "error: ".$errorCode."\n";
	}
}
