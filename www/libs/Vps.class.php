<?php 

/**
 * VPS class
 * @author mighty
 * Only get/set functions
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
	
	function setRam($ram) {
		$this->ram = $ram;
	}
	
	function getRamCurrent() {
		return $this->ramCurrent;
	}
	
	function setRamCurrent($ram) {
		$this->ramCurrent = $ram;
	}
	
	function getSwap() {
		return $this->swap;
	}
	
	function setSwap($ram) {
		$this->swap = $ram;
	}
	
	function getDisk() {
		return nbf(floor($this->disk/1024));
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
	
	function getOwner() {
		return $this->owner;
	}
}

?>