<?php


/**
 * FloodGuard is a class that helps you to protect your web application from sh*ty information flood
 *
 * Example usage:
 * $fg = new FloodGuard("localhost",11211,2,2);
 *
 * if($fg->checkPermissionToProceed()) {
 *	echo "yes! you can proceed!";
 * } else {
 *	echo "no! you shall not pass!";
 * }

 *
 * @package  FloodGuard
 * @author   Daniel Stoinov <daniel.stoinov@gmail.com>
 * @version  $Revision: 0.02 $
 * @access   public
 * @see      http://github.com/ctmnz/
 */

class FloodGuard
{
	/**
	 * returns true or false
	 *
	 * @param  string  $sample the sample data
	 * @return boolean   all of the exciting sample options
	 * @access private
	 */
	
	private static $mcache;
	private $fsec;
	private $ftimes;
	private $raddress;
	private $addressPrefix = 'classfloodprotect';
	private $mcachevarname;
	
	
	function __construct($mcacheAddr,$mcachePort,$floodsec,$floodtimes) {
		$this->mcache = new Memcache();
		$this->mcache->connect($mcacheAddr,$mcachePort);
		$this->fsec = $floodsec;
		$this->ftimes = $floodtimes;
		
		if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR']) {
			$this->raddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else {
			$this->raddress = $_SERVER['REMOTE_ADDR'];
		}
		
		$this->mcachevarname = $this->addressPrefix.$this->raddress;	
		
	}
	
	public function checkPermissionToProceed()
	{
		if($this->mcache->get($this->mcachevarname)) {
			// echo "It was set";
		
			$tmpSetVar = $this->mcache->get($this->mcachevarname);
			
			
			
			if ($tmpSetVar>$this->ftimes) {
				// echo "Nope... TMI (too much information). Your request wont be processed! ";
				$this->mcache->set($this->mcachevarname, $tmpSetVar , MEMCACHE_COMPRESSED, $this->fsec);
				// STOP THE PROCESS
				return false;
			} else {
		
				$tmpSetVar = $tmpSetVar + 1;
				$this->mcache->set($this->mcachevarname, $tmpSetVar , MEMCACHE_COMPRESSED, $this->fsec);
				//echo $this->mcache->get($mcachevarname);
				return true;
			}
		
		}
		
		else {
			$this->mcache->set($this->mcachevarname, '1', MEMCACHE_COMPRESSED, $this->fsec);
		
			// echo "initial process for this IP address";
			return true;
		
		
		}
		
	}
	
	
	public function dumpInfo(){
		echo "<br>";
		echo "raddress = " . $this->raddress . "<br>";
		echo "mcachevarname = " . $this->mcachevarname . "<br>";
		echo "raddress = " . $this->raddress . "<br>";
		echo "<br>";
		
	}
	
	
	
}



$fg = new FloodGuard("localhost",11211,10,10);

if($fg->checkPermissionToProceed()) {
	echo "yes! you can proceed!";
} else {
	echo "no! you shall not pass!";
}


?>
