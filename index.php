<?php
/**
 * Example usage of FloodGuard Class
 */


include_once("FloodGuard.php");

// FloodGuard("memcache_address","memcache_port","max seconds","max lines")

$fg = new FloodGuard("localhost",11211,10,10);

// You should wrap every project functionallity, like the example below, where you need to use flood limitation

if($fg->checkPermissionToProceed()) {
	echo "yes! you can proceed!";
} else {
	echo "no! you shall not pass!";
}


?>
