<?php
include "config.php";
echo passwordSalt("Hello world")."\n";
echo passwordSalt("sdfghjkgfdjgfdajkgsdfjhgfajhsdfgwieugiuy")."\n";

function passwordSalt ($custom) {
	$string = sha1($custom.SALT2);

	$i = 0;
	while ($i < strlen($custom) && $i < 5) {
		$char = $custom[0];
		$custom = substr($custom, 1);
		$no = (ord($char) % 4);
//		echo $no."-";
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
