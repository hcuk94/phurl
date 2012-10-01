<?php

ini_set('display_errors', '1');
error_reporting(E_ALL);

//$custom = generate_salt(64);
//echo $custom."\n\n";
//$custom = implode("", range('a', 'z'));
//$custom = "";
$custom = "f%x;l1c}L;:sj<6!8z[*g9e7G35G[-%y)B{{6872?%mI+2*)6[)04ZB8LdAS5+0~";
//$string = hash('sha512',$custom);
define('SALT4', 'N.O57&Z2.D9/:ibtEWzTL}i-s4)@;q:a7*8?5.7>(^P2PK8<T8M*-0a<v^b==[y[');
$string = sha1($custom.SALT4);
//echo $string."\n\n";

$i = 0;
while ($i < strlen($custom) && $i < 5) {
	$char = $custom[0];
	$custom = substr($custom, 1);
	$no = (ord($char) % 4);
//	echo $no."-";
	$modifier[$i] = $no+1;
	$i++;
}

//print_r($modifier);

//echo "\n\n";

$salt = "";
$numbers = array("0","1","2","3","4","5","6","7","8","9","0","1","2","3","4","5","6","7","8","9","0","1","2","3","4","5");
$lcchars = array("a","b","c","d","e","f","g","h","i","j","k","l","m","n","o","p","q","r","s","t","u","v","w","x","y","z");
$ucchars = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
$symbols = array('!','@','#','$','%','^','&','*','(',')','-','~','+','=','|','/','{','}',':',';',',','.','?','<','>','[');
/*$numbers = array_reverse($numbers);
$lcchars = array_reverse($lcchars);
$ucchars = array_reverse($ucchars);
$symbols = array_reverse($symbols);*/
$i = 0;
while (strlen($string) > 0) {
	$char = $string[0];
	$no = ord($char) % 26;
	/*if (in_array($char, $numbers)) {
		$no = ord($char)-48;
	} elseif (in_array($char, $lcchars)) {
		$no = ord($char)-97;
	} elseif (in_array($char, $symbols)) {
		$no = ord($char)-20;
	}*/
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
//	echo $no."(".$i."-".$modifier[$i].")-".substr($salt, -1)."-\n";
	$i++;
	if ($i == 5) {
		$i = 0;
	}
	$string = substr($string, 1);
}
echo "\n\n";
echo $salt;
echo "\n\n";
//echo count($lcchars)."-".count($numbers)."-".count($ucchars)."-".count($symbols);
function generate_salt($len) {
	$salt = "";
	$numbers = array("0","1","2","3","4","5","6","7","8","9");
	$lcchars = array("a","b","c","d","e","f","g","h","i","j","k","l","m","n","o","p","q","r","s","t","u","v","w","x","y","z");
	$ucchars = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
	$symbols = array('!','@','#','$','%','^','&','*','(',')','-','~','+','=','|','/','{','}',':',';',',','.','?','<','>','[');
	while ($len > strlen($salt)) {
		//$fake_salt = $numbers[array_rand($numbers)] . $lcchars[array_rand($lcchars)] . $ucchars[array_rand($ucchars)] . $symbols[array_rand($symbols)] . $symbols[array_rand($symbols)];
		//$salt = str_shuffle($fake_salt);
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
?>
