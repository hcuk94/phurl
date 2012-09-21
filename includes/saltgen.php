<?php
echo generate_salt(64)."\n";
echo generate_salt(64)."\n";
echo generate_salt(64)."\n";

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
?>
