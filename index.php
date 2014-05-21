<?php

$mcache = new Memcache;
$mcache->connect('localhost',11211);


// TODO: MAKE IT LIKE 10:11 SEC:TIMES
$floodsec = "10";
$floodtimes = "10";


//TODO: GET IP BEHIND APACHE PROXY BALANCER
$raddress = $_SERVER['REMOTE_ADDR'];
$addressPrefix = 'floodprotect';
$mcachevarname = $addressPrefix.$raddress;

if($mcache->get('$mcachevarname')) {
       // echo "It was set";

	$tmpSetVar = $mcache->get('$mcachevarname');
	if ($tmpSetVar>$floodtimes) {
		echo "Nope... TMI (too much information). Your request wont be processed! ";
		// STOP THE PROCESS
		die(); 
	} else {

	$tmpSetVar = $tmpSetVar + 1;
	$mcache->set('$mcachevarname', $tmpSetVar , MEMCACHE_COMPRESSED, $floodsec);
	echo $mcache->get('$mcachevarname');
	}

} else {
 $mcache->set('$mcachevarname', '1', MEMCACHE_COMPRESSED, $floodsec);
}


?>
