<?php
ini_set('display_errors', '1');
error_reporting(E_ALL);
echo generate_salt(32)."\n";

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
exit();
/*
Created by PatPatrson
Minor contributions by TechnoBulldog
Released under the UNLICENSE (as described in COPYING)
*/

// The one and only function to generate a salt
function saltgen($length) {
// We have to declare $salt first, lest we get errors
$salt = "";
	// Fills $salt with as many uniqid's as we need
	while(strlen($salt) < $length) {
	// Adds a uniqid to $salt
	$salt = $salt . uniqid(uniqid(mt_rand(1000,9999),true), true);
	}

// Do I really need to explain what $i does?
$i = 0;
// $salt2 is declared for the same reason as $salt, and
// we use it to hold the value of salt while we shorten
// it.
$salt2 = "";

	// The above while() might make our salt a bit too
	// long, so this is necessary to shorten it
	while($i < $length) {
	// Adds one character from $salt to $salt2 each
	// time it's run
	$salt2 = $salt2 . $salt[$i];
	// Increments $i, duh
	$i++;
	}

// Returns the salt
return $salt2;
}
?>
