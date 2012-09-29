<?php

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

//$countryDbIPv4 = "http://geolite.maxmind.com/download/geoip/database/GeoLiteCountry/GeoIP.dat.gz";
$countryDbIPv6 = "http://geolite.maxmind.com/download/geoip/database/GeoIPv6.dat.gz";

echo "Downloading Maxmind GeoIP Lite Country database\n\n";
//$dbipv4 = file_get_contents($countryDbIPv4) or die("Error downloading IPv4 Database. Exiting\n");
$dbipv6 = file_get_contents($countryDbIPv6) or die("Error downloading IPv6 Database. Exiting\n");
//file_put_contents("geo-ipv4.dat.gz", $dbipv4);
file_put_contents("geo-ipv6.dat.gz", $dbipv6);

//$datipv4 = gzfile_get_contents("geo-ipv4.dat.gz", 0);
$datipv6 = gzfile_get_contents("geo-ipv6.dat.gz", 0);
//file_put_contents("geo-ipv4.dat", $datipv4);
file_put_contents("geo-ipv6.dat", $datipv6);



?>
