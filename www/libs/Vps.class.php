<?php 

/**
 * VPS class
 * @author V_paranoiaque
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
	
	/**
	 * Constructor
	 * @param number $id Vps's id
	 */
	function __construct($id) {
		$this->id = $id;
	}
	
	/**
	 * Return the Vps id
	 */
	function getId() {
		return $this->id;
	}
	
	/**
	 * Return the Vps hostname
	 */
	function getName() {
		return $this->name;
	}
	
	/**
	 * Set vps hostname
	 * @param string $name vps hostname
	 */
	function setName($name) {
		$this->name = $name;
	}
	
	/**
	 * Return the Vps's ipv4
	 */
	function getIpv4() {
		return $this->ipv4;
	}
	
	/**
	 * Set the Vps's ipv4
	 * @param string $ip the Vps's ipv4
	 */
	function setIpv4($ip) {
		$this->ipv4 = $ip;
	}
	
	/**
	 * Get VPS description
	 */
	function getDescription() {
		return $this->description;
	}
	
	/**
	 * Set VPS description
	 * @param string $txt Description
	 */
	function setDescription($txt) {
		$this->description = $txt;
	}
	
	/**
	 * Get the last update time
	 */
	function getLast_maj() {
		return $this->last_maj;
	}
	
	/**
	 * Set the last update time
	 * @param number $time last update time
	 */
	function setLast_maj($time) {
		$this->last_maj = $time;
	}
	
	/**
	 * Get the vps os template
	 */
	function getOstemplate() {
		return $this->ostemplate;
	}
	
	/**
	 * Set the vps os template
	 * @param string $os os template
	 */
	function setOstemplate($os) {
		$this->ostemplate = $os;
	}
	
	/**
	 * Get the vps memory
	 */
	function getRam() {
		return $this->ram;
	}
	
	/**
	 * Set the vps memory
	 * @param number $ram memory
	 */
	function setRam($ram) {
		$this->ram = $ram;
	}
	
	/**
	 * Get the currently used memory
	 */
	function getRamCurrent() {
		return $this->ramCurrent;
	}
	
	/**
	 * Set the currently used memory
	 * @param number $ram used memory
	 */
	function setRamCurrent($ram) {
		$this->ramCurrent = $ram;
	}
	
	/**
	 * Get the vps swap
	 */
	function getSwap() {
		return $this->swap;
	}
	
	/**
	 * Set the vps swap
	 * @param number $ram swap
	 */
	function setSwap($ram) {
		$this->swap = $ram;
	}
	
	/**
	 * Get the vps space on harddisk
	 */
	function getDisk() {
		return nbf(floor($this->disk/1024));
	}
	
	/**
	 * Set the vps space on harddisk
	 * @param number $disk space
	 */
	function setDisk($disk) {
		$this->disk = $disk;
	}
	
	/**
	 * Get the number of processor
	 */
	function getNproc() {
		return $this->nproc;
	}
	
	/**
	 * Set the number of processor
	 * @param number $n number of processor
	 */
	function setNproc($n) {
		$this->nproc = $n;
	}
	
	/**
	 * Get the vps loadaverage
	 */
	function getLoadAvg() {
		return $this->loadAvg;
	}
	
	/**
	 * Set the vps loadaverage
	 * @param number $avg vps loadaverage
	 */
	function setLoadavg($avg) {
		$this->loadAvg = $avg;
	}
	
	/**
	 * Get Vps physical server id
	 */
	function getServerId() {
		return $this->serverId;
	}
	
	/**
	 * Set Vps physical server id
	 * @param number $id server id
	 */
	function setServerId($id) {
		$this->serverId = $id;
	}
}

?>
