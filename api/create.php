<?php
require_once("../includes/config.php");
require_once("../includes/functions.php");
ini_set('display_errors', 0);
$prefix[0] = '';
db_connect();
if (count($_GET) > 0) {
	if (count($_GET) > 1) {
		$alias = mysql_real_escape_string(trim($_GET['a']));
	}
	
	$url   = mysql_real_escape_string(trim($_GET['url']));
    
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
    if (strlen($url) == 0) {
        $_ERROR[] = "01";
    }
    else if (empty($data['scheme']) || empty($data['host'])) {
        $_ERROR[] = "02";
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
	print_errors();
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

            $id = insert_url($url, $code, $alias);
        }

        if (strlen($alias) > 0) {
            $code = $alias;
        }

        $short_url = get_phurl_option('site_url')."/".$code;

        $_GET['url']   = "";
        $_GET['alias'] = "";
	echo "$short_url\n";
        exit();
    }
}
