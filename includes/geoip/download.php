<?php

include "../config.php";
include "../functions.php";
db_connect();
require_admin();

if (!is_writeable(".")) {
	die("Error! The folder includes/geoip must be chmod 0777 (Writable by all)");
}

//ini_set('display_errors', '0');
//error_reporting(E_ALL);

function gzfile_get_contents ($filename, $use_include_path=0){
	$file = @gzopen($filename, 'rb', $use_include_path) or die("failed gzopen");
	if ($file) {
		$data = '';
		while (!gzeof($file)) {
			$data .= gzread($file, 1024);
		}
		gzclose($file);
		return $data;
	}
}

$countryDbIPv6 = "http://geolite.maxmind.com/download/geoip/database/GeoIPv6.dat.gz";

echo "Downloading Maxmind GeoIP Lite Country database\n<br /><br />\n\n";
$dbipv6 = file_get_contents($countryDbIPv6) or die("<br />\n\nError downloading IPv6 Database. Exiting\n");
file_put_contents("geo-ipv6.dat.gz", $dbipv6) or die("<br />\n\nError writing gzfile.");
echo "Downloaded IPv6 Database.\n<br />\n";

$datipv6 = gzfile_get_contents("geo-ipv6.dat.gz", 0) or die("<br />\n\nError extracting IPv6 Database. Exiting\n");
file_put_contents("geo-ipv6.dat", $datipv6) or die("<br />\n\nError writing dat file.");
echo "Extracted IPv6 Database.\n<br />\n";



?>
