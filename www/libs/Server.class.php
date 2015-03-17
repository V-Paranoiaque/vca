<?php 

class Server {
	
	private $id;
	private $name;
	private $address;
	private $port;
	private $description;
	private $nbVps;
	private $vps;
	private $key;
	
	/*** Get/Set ***/
	
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
	
	function getAddress() {
		return $this->address;
	}
	
	function setAddress($address) {
		$this->address = $address;
	}
	
	function getDescription() {
		return $this->description;
	}
	
	function setDescription($description) {
		$this->description = $description;
	}
	
	function setNbVps($nb) {
		$this->nbVps = $nb;
	}
	
	function getNbVps() {
		return $this->nbVps;
	}
	
	function vpsList() {
		
	}
	
	function getKey() {
		return $this->key;
	}
	
	function setKey($key) {
		$this->key = $key;
	}
	
	function getPort() {
		return $this->port;
	}
	
	function setPort($port) {
		$this->port = $port;
	}
	
	/*** Server functions ***/
	
	/**
	 * Reload all Vps information
	 */
	function vpsReload() {
		$link = Db::link();
		$connect = new Socket($this->address, $this->port, $this->key);
		$connect -> write('VpsList');
		$data = json_decode($connect -> read());
		
		if(!empty($data)) {
			foreach ($data as $vps) {
				
				if(empty($vps->diskspace) or !preg_match('`[0-9]`', $vps->diskspace)) {
					$diskspace = 0;
				}
				else {
					$diskspace = $vps->diskspace;
				}
				
				//unlimited == 0 memory
				if(empty($vps->ram) or !preg_match('`[0-9]`', $vps->ram)) {
					$ram = 0;
				}
				else {
					$ram = $vps->ram;
				}
				
				if(empty($vps->swappages) or !preg_match('`[0-9]`', $vps->swappages)) {
					$swappages = 0;
				}
				else {
					$swappages = $vps->swappages;
				}
				
				if(empty($vps->ostemplate)) {
					$ostemplate = '';
				}
				else {
					$ostemplate = $vps->ostemplate;
				}
				
				if(empty($vps->origin_sample)) {
					$origin_sample = '';
				}
				else {
					$origin_sample = $vps->origin_sample;
				}
				
				$tmp = explode(':', $vps->diskinodes);
				$diskinodes = $tmp[0];
				
				$sql = 'UPDATE vps
				        SET vps_name= :vps_name,
				            vps_ipv4= :vps_ipv4,
				            server_id= :server_id,
				            last_maj= :last_maj,
				            vps_cpulimit= :vps_cpulimit,
				            vps_cpus= :vps_cpus,
				            vps_cpuunits= :vps_cpuunits,
				            ostemplate= :ostemplate,
				            origin_sample= :origin_sample,
				            onboot= :onboot,
				            quotatime= :quotatime,
							diskspace= :diskspace,
				            ram= :ram,
				            ram_current= :ram_current,
				            swap= :swap,
				            diskinodes= :diskinodes,
				            nproc= :nproc,
				            loadavg= :loadavg,
				            diskspace_current= :diskspace_current
				        WHERE vps_id= :vps_id';
				$req = $link->prepare($sql);
				$req->bindValue(':vps_id', $vps->_id, PDO::PARAM_INT);
				$req->bindValue(':vps_name', $vps->hostname, PDO::PARAM_STR);
				$req->bindValue(':vps_ipv4', $vps->ip, PDO::PARAM_STR);
				$req->bindValue(':server_id', $this->id, PDO::PARAM_INT);
				$req->bindValue(':last_maj', $_SERVER['REQUEST_TIME'], PDO::PARAM_INT);
				$req->bindValue(':vps_cpulimit', $vps->cpulimit, PDO::PARAM_INT);
				$req->bindValue(':vps_cpus', $vps->cpus, PDO::PARAM_INT);
				$req->bindValue(':vps_cpuunits', $vps->cpuunits, PDO::PARAM_INT);
				$req->bindValue(':ostemplate', $ostemplate, PDO::PARAM_STR);
				$req->bindValue(':origin_sample', $origin_sample, PDO::PARAM_STR);
				$req->bindValue(':onboot', $vps->onboot, PDO::PARAM_INT);
				$req->bindValue(':quotatime', $vps->quotatime, PDO::PARAM_INT);
				$req->bindValue(':diskspace', $diskspace, PDO::PARAM_INT);
				$req->bindValue(':ram', $ram, PDO::PARAM_INT);
				$req->bindValue(':ram_current', $vps->ram_current, PDO::PARAM_INT);
				$req->bindValue(':swap', $swappages, PDO::PARAM_INT);
				$req->bindValue(':diskinodes', $diskinodes, PDO::PARAM_INT);
				$req->bindValue(':nproc', $vps->nproc, PDO::PARAM_INT);
				$req->bindValue(':loadavg', $vps->loadavg, PDO::PARAM_STR);
				$req->bindValue(':diskspace_current', $vps->diskspace_current, PDO::PARAM_INT);
				$req->execute();
				
				if($req->rowCount() == 0) {
					$sql = 'INSERT INTO vps
					        (vps_id, vps_name, vps_ipv4, server_id, 
					         last_maj, vps_cpulimit, vps_cpus, vps_cpuunits,
					         ostemplate, origin_sample, onboot, quotatime,
					         diskspace, ram, ram_current, swap, diskinodes,
					         nproc, loadavg, diskspace_current)
					        VALUES
					        (:vps_id, :vps_name, :vps_ipv4, :server_id, 
					         :last_maj, :vps_cpulimit, :vps_cpus, :vps_cpuunits,
					         :ostemplate, :origin_sample, :onboot, :quotatime,
					         :diskspace, :ram, :ram_current, :swap, :diskinodes,
					         :nproc, :loadavg, :diskspace_current)';
					$req = $link->prepare($sql);
					$req = $link->prepare($sql);
					$req->bindValue(':vps_id', $vps->_id, PDO::PARAM_INT);
					$req->bindValue(':vps_name', $vps->hostname, PDO::PARAM_STR);
					$req->bindValue(':vps_ipv4', $vps->ip, PDO::PARAM_STR);
					$req->bindValue(':server_id', $this->id, PDO::PARAM_INT);
					$req->bindValue(':last_maj', $_SERVER['REQUEST_TIME'], PDO::PARAM_INT);
					$req->bindValue(':vps_cpulimit', $vps->cpulimit, PDO::PARAM_INT);
					$req->bindValue(':vps_cpus', $vps->cpus, PDO::PARAM_INT);
					$req->bindValue(':vps_cpuunits', $vps->cpuunits, PDO::PARAM_INT);
					$req->bindValue(':ostemplate', $ostemplate, PDO::PARAM_STR);
					$req->bindValue(':origin_sample', $origin_sample, PDO::PARAM_STR);
					$req->bindValue(':onboot', $vps->onboot, PDO::PARAM_INT);
					$req->bindValue(':quotatime', $vps->quotatime, PDO::PARAM_INT);
					$req->bindValue(':diskspace', $diskspace, PDO::PARAM_INT);
					$req->bindValue(':ram', $ram, PDO::PARAM_INT);
					$req->bindValue(':ram_current', $vps->ram_current, PDO::PARAM_INT);
					$req->bindValue(':swap', $swappages, PDO::PARAM_INT);
					$req->bindValue(':diskinodes', $diskinodes, PDO::PARAM_INT);
					$req->bindValue(':nproc', $vps->nproc, PDO::PARAM_INT);
					$req->bindValue(':loadavg', $vps->loadavg, PDO::PARAM_STR);
					$req->bindValue(':diskspace_current', $vps->diskspace_current, PDO::PARAM_INT);
					$req->execute();
				}
			}
		}
	}
	
	/**
	 * Start a Vps
	 * @param vps id
	 * @return unknown
	 */
	function templateList() {
		$connect = new Socket($this->address, $this->port, $this->key);
		$connect -> write('templateList');
		$data = json_decode($connect -> read());
	
		return $data;
	}
	
	/**
	 * Rename a template
	 * @param old name
	 * @param new name
	 */
	function templateRename($old, $new) {
		$para = array('old' => $old, 'new' => $new);
		$connect = new Socket($this->address, $this->port, $this->key);
		$connect -> write('templateRename', 0, $para);
	}
	
	/**
	 * Add a template
	 * @param template name
	 */
	function templateAdd($name) {
		$connect = new Socket($this->address, $this->port, $this->key);
		$connect -> write('templateAdd', 0, $name);
	}
	
	/**
	 * Delete a template
	 * @param template name
	 */
	function templateDelete($name) {
		$connect = new Socket($this->address, $this->port, $this->key);
		$connect -> write('templateDelete', 0, $name);
	}
	
	/*** Vps functions ***/
	
	/**
	 * Create a new Vps
	 * @param Vps id
	 * @param all parameters
	 */
	function setVpsAdd($id, $para) {
		$connect = new Socket($this->address, $this->port, $this->key);
		$connect -> write('create', $id, $para);
		$data = json_decode($connect -> read());
	}
	
	/**
	 * Modifie a Vps
	 * @param Vps id
	 * @param parameters
	 */
	function vpsUpdate($id, $para) {
		$connect = new Socket($this->address, $this->port, $this->key);
		$connect -> write('modConf', $id, $para);
		$data = json_decode($connect -> read());
	}
	
	/**
	 * Start a Vps
	 * @param vps id
	 */
	function start($id=0) {
		$connect = new Socket($this->address, $this->port, $this->key);
		$connect -> write('start', $id);
		$data = json_decode($connect -> read());
	}
	
	/**
	 * Stop a Vps
	 * @param vps id
	 */
	function stop($id=0) {
		$connect = new Socket($this->address, $this->port, $this->key);
		$connect -> write('stop', $id);
		$data = json_decode($connect -> read());
	}
	
	/**
	 * Restart a Vps
	 * @param vps id
	 */
	function restart($id=0) {
		$connect = new Socket($this->address, $this->port, $this->key);
		$connect -> write('restart', $id);
		$data = json_decode($connect -> read());
	}
	
	/**
	 * Delete a Vps
	 * @param vps id
	 */
	function delete($id) {
		$connect = new Socket($this->address, $this->port, $this->key);
		$connect -> write('delete', $id);
		$data = json_decode($connect -> read());
		
		$link = Db::link();
		
		$sql = 'DELETE FROM vps WHERE vps_id= :id';
		$req = $link->prepare($sql);
		$req->bindValue(':id', $id, PDO::PARAM_INT);
		$req->execute();
		
		$sql = 'DELETE FROM schedule WHERE vps_id= :id';
		$req = $link->prepare($sql);
		$req->bindValue(':id', $id, PDO::PARAM_INT);
		$req->execute();
	}
}

?>
