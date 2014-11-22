<?php 

/**
 * VPS class
 * @author mighty
 * Only get/set and display functions
 */
class Vps {
	
	private $id;
	private $name;
	private $ipv4;
	private $description;
	private $last_maj;
	private $ostemplate;
	private $ram=0;
	private $ramCurrent;
	private $swap;
	private $disk;
	private $nproc;
	private $loadAvg;
	private $serverId;
	private $diskspaceCurrent;
	private $owner=0;
	
	function __construct($id) {
		$this->id = $id;
	}
	
	function getId() {
		return $this->id;
	}
	
	function getName() {
		return $this->name;
	}
	
	function setName($name) {
		$this->name = $name;
	}
	
	function getIpv4() {
		return $this->ipv4;
	}
	
	function setIpv4($ip) {
		$this->ipv4 = $ip;
	}
	
	function getDescription() {
		return $this->description;
	}
	
	function setDescription($txt) {
		$this->description = $txt;
	}
	
	function getLast_maj() {
		return $this->last_maj;
	}
	
	function setLast_maj($time) {
		$this->last_maj = $time;
	}
	
	function getOstemplate() {
		return $this->ostemplate;
	}
	
	function setOstemplate($os) {
		$this->ostemplate = $os;
	}
	
	function getRam() {
		return $this->ram;
	}
	
	function getDisplayRam() {
		if($this->ram == 0) {
			return _('unlimited');
		}
		else {
			return $this->ram.' MB';
		}
	}
	
	function setRam($ram) {
		$this->ram = $ram;
	}
	
	function getRamCurrent() {
		return $this->ramCurrent;
	}
	
	function getDisplayRamCurrent() {
		return ($this->ramCurrent/1024).' MB';
	}
	
	function setRamCurrent($ram) {
		$this->ramCurrent = $ram;
	}
	
	function getSwap() {
		return $this->swap;
	}
	
	function getDisplaySwap() {
		if($this->swap == 0) {
			return _('unlimited');
		}
		else {
			return ($this->swap/1024).' MB';
		}
	}
	
	function setSwap($ram) {
		$this->swap = $ram;
	}
	
	function getDisk() {
		return nbf(floor($this->disk/1024));
	}
	
	/**
	 * Return disk space with KB/MB/GB
	 */
	function getDisplayDisk() {
		if($this->disk < 500) {
			return $this->disk.' KB';
		}
		elseif($this->disk < 1000000) {
			return (ceil($this->disk/10.24)/100).' MB';
		}
		else {
			return (ceil($this->disk/10485.76)/100).' GB';
		}	
	}
	
	function setDisk($disk) {
		$this->disk = $disk;
	}
	
	function getNproc() {
		return $this->nproc;
	}
	
	function setNproc($n) {
		$this->nproc = $n;
	}
	
	function getLoadAvg() {
		return $this->loadAvg;
	}
	
	function setLoadavg($avg) {
		$this->loadAvg = $avg;
	}
	
	function getServerId() {
		return $this->serverId;
	}
	
	function setServerId($id) {
		$this->serverId = $id;
	}
	
	function setDiskspaceCurrent($disk) {
		$this->diskspaceCurrent = $disk;
	}
	
	/**
	 * Return disk space used, with KB/MB/GB
	 */
	function getDiskspaceCurrent() {
		if($this->diskspaceCurrent < 500) {
			return $this->diskspaceCurrent.' KB';
		}
		elseif($this->diskspaceCurrent < 1000000) {
			return (ceil($this->diskspaceCurrent/10.24)/100).' MB';
		}
		else {
			return (ceil($this->diskspaceCurrent/10485.76)/100).' GB';
		}		
		
		return $this->diskspaceCurrent;
	}
	
	function getOwner() {
		return $this->owner;
	}
	
	function setOwner($owner) {
		$this->owner = $owner;
	}
}

?>