<?php 

/**
 * Physical Server class
 * @author V_paranoiaque
 */
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
	
	/**
	 * Constructor
	 * @param number $id server id
	 */
	function __construct($id) {
		$this->id = $id;
	}
	
	/**
	 * Return server id
	 */
	function getId() {
		return $this->id;
	}
	
	/**
	 * Return server name
	 */
	function getName() {
		return $this->name;
	}
	
	/**
	 * Set server name
	 * @param string $name server name
	 */
	function setName($name) {
		$this->name = $name;
	}
	
	/**
	 * Get server address
	 */
	function getAddress() {
		return $this->address;
	}
	
	/**
	 * Setr server address
	 * @param string $address server address, ip or name
	 */
	function setAddress($address) {
		$this->address = $address;
	}
	
	/**
	 * Get server description
	 */
	function getDescription() {
		return $this->description;
	}
	
	/**
	 * Set server description
	 * @param string $description server description
	 */
	function setDescription($description) {
		$this->description = $description;
	}
	
	/**
	 * Get the number of VPS
	 */
	function getNbVps() {
	 return $this->nbVps;
	}
	
	/**
	 * Set the number of VPS
	 * @param number $nb number of VPS
	 */
	function setNbVps($nb) {
		$this->nbVps = $nb;
	}
	
	/**
	 * Get the server key
	 */
	function getKey() {
		return $this->key;
	}
	
	/**
	 * Set the server key
	 * @param string $key server key
	 */
	function setKey($key) {
		$this->key = $key;
	}
	
	/**
	 * Get the daemon port
	 */
	function getPort() {
		return $this->port;
	}
	
	/** 
	 * Set the server port
	 * @param number $port server oirt
	 */
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
	 * List available templates
	 */
	function templateList() {
		$connect = new Socket($this->address, $this->port, $this->key);
		$connect -> write('templateList');
		$data = json_decode($connect -> read());
	
		return $data;
	}
	
	/**
	 * Rename a template
	 * @param string $old the old name
	 * @param string $new the new name
	 */
	function templateRename($old, $new) {
		$para = array('old' => $old, 'new' => $new);
		$connect = new Socket($this->address, $this->port, $this->key);
		$connect -> write('templateRename', 0, $para);
	}
	
	/**
	 * Add a template
	 * @param string $name template's name
	 */
	function templateAdd($name) {
		$connect = new Socket($this->address, $this->port, $this->key);
		$connect -> write('templateAdd', 0, $name);
	}
	
	/**
	 * Delete a template
	 * @param string $name template's name
	 */
	function templateDelete($name) {
		$connect = new Socket($this->address, $this->port, $this->key);
		$connect -> write('templateDelete', 0, $name);
	}
	
	/*** Vps functions ***/
	
	/**
	 * Create a new Vps
	 * @param number $id Vps id
	 * @param array $para all parameters
	 */
	function setVpsAdd($id, $para) {
		$connect = new Socket($this->address, $this->port, $this->key);
		$connect -> write('create', $id, $para);
		$data = json_decode($connect -> read());
	}
	
	/**
	 * Modifie a Vps
	 * @param number $id Vps id
	 * @param array $para parameters
	 */
	function vpsUpdate($id, $para) {
		$connect = new Socket($this->address, $this->port, $this->key);
		$connect -> write('modConf', $id, $para);
		$data = json_decode($connect -> read());
	}
	
	/**
	 * Start a Vps
	 * @param number $id vps id
	 */
	function start($id=0) {
		$connect = new Socket($this->address, $this->port, $this->key);
		$connect -> write('start', $id);
		$data = json_decode($connect -> read());
	}
	
	/**
	 * Stop a Vps
	 * @param number $id vps id
	 */
	function stop($id=0) {
		$connect = new Socket($this->address, $this->port, $this->key);
		$connect -> write('stop', $id);
		$data = json_decode($connect -> read());
	}
	
	/**
	 * Restart a Vps
	 * @param number $id vps id
	 */
	function restart($id=0) {
		$connect = new Socket($this->address, $this->port, $this->key);
		$connect -> write('restart', $id);
		$data = json_decode($connect -> read());
	}
	
	/**
	 * Delete a Vps
	 * @param number $id vps id
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
	
	/**
	 * Scan server
	 */
	function avScan() {
		$link = Db::link();
		$connect = new Socket($this->address, $this->port, $this->key);
		$connect -> write('avScan');
		$data = json_decode($connect -> read());
		
		$logFile = '/usr/share/vca/www/scanlogs/'.$this->getId().'.log';
		$file = fopen($logFile, 'w+');
		
		if(!empty($data) && sizeof($data) > 0) {
			foreach ($data as $line) {
				$line = preg_replace('/\//', ':', $line, 1);
				
				//Write info
				fputs($file, $line);
				fputs($file, "\n");
			}
		}
		
		fclose($file);
	}
	
	/**
	 * Get Antivirus scan result
	 */
	function scanResult() {
		$logFile = '/usr/share/vca/www/scanlogs/'.$this->getId().'.log';
		
		if(!file_exists($logFile)) {
			return '';
		}
		
		$result = array();
		$file = fopen($logFile, 'r');
		
		while (($buffer = fgets($file, 4096)) !== false) {
			$info = explode(':', $buffer);
			$result[] = array(
				'vps'  => $info[0],
				'msg'  => $info[1],
				'info' => $info[2]
			);
		}
		
		fclose($file);
		
		return $result;
	}
}

?>
