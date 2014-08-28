<?php

include_once("FloodGuard.php");


$fg = new FloodGuard("localhost",11211,10,10);

if($fg->checkPermissionToProceed()) {
	echo "yes! you can proceed!";
} else {
	echo "no! you shall not pass!";
}


?>
