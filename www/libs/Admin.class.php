<?php 

class Admin extends User {

	/*** VCA ***/

	/**
	 * VCA stats
	 */
	function vcaStats() {
		$link = Db::link();
	
		$sql = 'SELECT count(user_id) as nb
		        FROM user';
		$req = $link->prepare($sql);
		$req->execute();
		$do = $req->fetch(PDO::FETCH_OBJ);
		$nbUser = $do->nb;
	
		$sql = 'SELECT count(server_id) as nb
		        FROM server';
		$req = $link->prepare($sql);
		$req->execute();
		$do = $req->fetch(PDO::FETCH_OBJ);
		$nbServer = $do->nb;
	
		$sql = 'SELECT count(vps_id) as nb
		        FROM vps
		        WHERE nproc>0';
		$req = $link->prepare($sql);
		$req->execute();
		$do = $req->fetch(PDO::FETCH_OBJ);
		$nbVpsRun = $do->nb;
	
		$sql = 'SELECT count(vps_id) as nb
		        FROM vps
		        WHERE nproc=0';
		$req = $link->prepare($sql);
		$req->execute();
		$do = $req->fetch(PDO::FETCH_OBJ);
		$nbVpsStop = $do->nb;
	
		return array(
				'nbVps'    => $nbVpsRun+$nbVpsStop,
				'nbVpsRun' => $nbVpsRun,
				'nbVpsStop'=> $nbVpsStop,
				'nbServer' => $nbServer,
				'nbUser'   => $nbUser
		);
	}
	
	/**
	 * Next free ID, for a new VPS
	 * @return ID
	 */
	static function vpsNextId() {
		$link = Db::link();
		$list = array();
		$vps_new = 0;
	
		$sql = 'SELECT MAX(vps_id) as max
		        FROM vps';
		$req = $link->prepare($sql);
		$req->execute();
		$do = $req->fetch(PDO::FETCH_OBJ);
	
		$vps_max = $do->max;
	
		$sql = 'SELECT Count(vps_id) as nb
		        FROM vps';
		$req = $link->prepare($sql);
		$req->execute();
		$do = $req->fetch(PDO::FETCH_OBJ);
	
		$vps_nb = $do->nb;
	
		if($vps_nb == $vps_max) {
			$vps_new = $vps_max+1;
		}
		else {
			$sql = 'SELECT vps_id
		        FROM vps
			    ORDER BY vps_id ASC';
			$req = $link->prepare($sql);
			$req->execute();
			while ($do = $req->fetch(PDO::FETCH_OBJ)) {
				$list[] = $do->vps_id;
			}
	
			for ($i=1; $i<=$vps_max;$i++) {
				if(!in_array($i, $list)) {
					$vps_new = $i;
					break;
				}
			}
		}
	
		return $vps_new;
	}
	
	/*** Users ***/

	/**
	 * Return all users
	 */
	function userList() {
		$link = Db::link();
		$list = array();
			
		$sql = 'SELECT user_id, user_name, user_rank, user_mail,
		               if(v.nb IS NULL, 0, v.nb) as nb
		        FROM user
		        LEFT JOIN (SELECT vps_owner, count(vps_owner) as nb
		                   FROM vps GROUP BY vps_owner) v
		        ON v.vps_owner=user.user_id';
		$res = $link->query($sql);
		while($do = $res->fetch(PDO::FETCH_OBJ)) {
			$list[$do->user_id] = array(
					'user_id'   => $do->user_id,
					'user_name' => $do->user_name,
					'user_rank' => $do->user_rank,
					'user_mail' => $do->user_mail,
					'nb_vps'    => $do->nb
			);
		}
		
		return $list;
	}
		
	/**
	 * Update user informations
	 * @param user id
	 * @param user name
	 * @param user mail
	 * @return error
	 */
	function userUpdate($id, $user_name='', $user_mail='') {
	
		if(empty($id)) {
			$id = $this->getId();
		}
		
		if(empty($user_name) or empty($user_mail)) {
			return 1;
		}
		elseif(!filter_var($user_mail, FILTER_VALIDATE_EMAIL)) {
			return 9;
		}
		elseif(strlen($user_name) < 4 or strlen($user_name) > 25) {
			return 10;
		}
		
		$link = Db::link();
		$sql = 'SELECT user_id
		        FROM user
		        WHERE user_name= :user_name';
		$req = $link->prepare($sql);
		$req->execute(array('user_name' => $user_name));
		$do = $req->fetch(PDO::FETCH_OBJ);
	
		if(!empty($do->user_id)) {
			return 2;
		}
		
		$sql = 'UPDATE user
		        SET user_name= :user_name,
		            user_mail= :user_mail
		        WHERE user_id= :user_id';
		$req = $link->prepare($sql);
		$req->execute(array(
				'user_name' => $user_name,
				'user_mail' => $user_mail,
				'user_id'   => $id
		));
	
		return 5;
	}
	
	/**
	 * Create a new user
	 * @param user name
	 * @param user mail
	 * @return error
	 */
	function userNew($user_name='', $user_mail='') {
	
		if(empty($user_name) or empty($user_mail)) {
			return 1;
		}
	
		$link = Db::link();
		$sql = 'SELECT user_id
		        FROM user
		        WHERE user_name= :user_name';
		$req = $link->prepare($sql);
		$req->execute(array('user_name' => $user_name));
		$do = $req->fetch(PDO::FETCH_OBJ);
	
		if(!empty($do->user_id)) {
			return 2;
		}
	
		$sql = 'INSERT INTO user
		        (user_name, user_mail)
		        VALUES
		        (:user_name, :user_mail)';
		$req = $link->prepare($sql);
		$req->execute(array(
				'user_name' => $user_name,
				'user_mail' => $user_mail
		));
	
		return 8;
	}
	
	/**
	 * Delete an user
	 * @param user id
	 * @return error number
	 */
	function userDelete($id) {
		if($id == $this->getId()) {
			return 6;
		}
	
		$link = Db::link();
	
		$sql = 'UPDATE vps
		        SET vps_owner=0
		        WHERE vps_owner= :user_id';
		$req = $link->prepare($sql);
		$req->execute(array('user_id' => $id));
		
		$sql = 'DELETE FROM user
		        WHERE user_id= :user_id';
		$req = $link->prepare($sql);
		$req->execute(array('user_id' => $id));
	
		if($req->rowCount() == 0) {
			return 3;
		}
		else {
			return 7;
		}
	}
	
	/**
	 * Return User Vps
	 * @param user id
	 */
	function userVps($id) {
		$user = new User($id);
		return $user-> vpsList();
	}
	
	/*** IP ***/
	
	/**
	 * Return Free IP
	 * @return IP list
	 */
	function ipFree() {
		$link = Db::link();
	
		$ipList = $this->ipList();
		$ipUsed = array();
		$ipFree = array();
	
		$sql = 'SELECT vps_ipv4
		        FROM vps';
		$req = $link->prepare($sql);
		$req->execute();
		while ($do = $req->fetch(PDO::FETCH_OBJ)) {
			$ipUsed[] = $do->vps_ipv4;
		}
	
		foreach($ipList as $ip) {
			if(!in_array($ip['ip'], $ipUsed)) {
				$ipFree[] = $ip['ip'];
			}
		}
	
		return $ipFree;
	}
	
	/**
	 * Return allowed IP
	 * @return Ip list
	 */
	function ipList() {
		$link = Db::link();
		$list = array();
	
		$sql = 'SELECT ip, if(vps_id>0,vps_id,0) as vps_id,
		               if(vps_name IS NULL,\'\', vps_name) as vps_name
				FROM ipv4
		        LEFT JOIN vps ON vps.vps_ipv4=ip
		        ORDER BY INET_ATON(ip)';
		$req = $link->prepare($sql);
		$req->execute();
		while ($do = $req->fetch(PDO::FETCH_OBJ)) {
			$list[$do->ip] = array(
					'ip'   => $do->ip,
					'id'   => $do->vps_id,
					'name' => $do->vps_name
			);
		}
	
		return $list;
	}
	
	/**
	 * Add a new IP
	 * @param IP
	 */
	function ipNew($ip) {
		$link = Db::link();
	
		if(!empty($ip) && filter_var($ip, FILTER_VALIDATE_IP)) {
			$sql = 'SELECT ip FROM ipv4 WHERE ip= :ip';
			$req = $link->prepare($sql);
			$req->execute(array('ip' => $ip));
			$do = $req->fetch(PDO::FETCH_OBJ);
	
			if(empty($do->ip)) {
				$sql = 'INSERT INTO ipv4
				        (ip)
				        VALUES
				        (:ip)';
				$req = $link->prepare($sql);
				$req->execute(array('ip' => $ip));
			}
		}
	}
	
	/**
	 * Delete an IP
	 * @param IP
	 */
	function ipDelete($ip) {
		$link = Db::link();
	
		$list = $this->ipList();
	
		if(!empty($ip) && !empty($list[$ip]) && empty($list[$ip]['id'])) {
			$sql = 'DELETE FROM ipv4
			        WHERE ip= :ip';
			$req = $link->prepare($sql);
			$req->execute(array('ip' => $ip));
		}
	}
	
	/*** Serveur ***/
	
	/**
	 * Return all servers
	 */
	function serverList($id=0) {
		$list = array();
	
		$link = Db::link();
		$sql = 'SELECT server.server_id, server_name, server_address,
		               server_description, if(v.nb IS NULL,0, v.nb) as nb,
		               server_key
		        FROM server
		        LEFT JOIN (SELECT server_id, count(server_id) as nb
		                  FROM `vps` GROUP BY server_id) v
		        ON v.server_id=server.server_id';
		$res = $link->query($sql);
		while($do = $res->fetch(PDO::FETCH_OBJ)) {
			$list[$do->server_id] = array(
					'id'          => $do->server_id,
					'name'        => $do->server_name,
					'address'     => $do->server_address,
					'description' => $do->server_description,
					'nbvps'       => $do->nb,
					'key'         => $do->server_key
			);
		}
	
		return $list;
	}

	/**
	 * Add a new server on the panel
	 * @param name
	 * @param address (name or IP)
	 * @param key
	 * @param description
	 */
	function serverAdd($name,$address,$key,$description) {
		$link = Db::link();
	
		$sql = 'INSERT INTO server
		        (server_name, server_address, server_description, server_key)
		        VALUES
				(:name, :address, :description, :key)';
		$req = $link->prepare($sql);
		$req->execute(array(
				'name'        => $name,
				'address'     => $address,
				'description' => $description,
				'owner'       => $this->getId(),
				'key'         => $key
		));
	
		$this->serverReload($link->lastInsertId());
	}
	
	/**
	 * Remove a server from the panel
	 * @param server id
	 */
	function serverDelete($id) {
		$link = Db::link();
	
		$sql = 'DELETE FROM server
		        WHERE server_id= :server_id';
		$req = $link->prepare($sql);
		$req->execute(array('server_id' => $id));
	
		$sql = 'DELETE FROM vps
		        WHERE server_id= :server_id';
		$req = $link->prepare($sql);
		$req->execute(array('server_id' => $id));
	}
	
	/**
	 * Update server informations
	 * @param server id
	 * @param all informations
	 */
	function serverUpdate($id, $var)  {
		$servers = $this->serversList();
	
		if($servers == null) {
			return null;
		}
	
		$server = $servers[$id];
	
		if(empty($server)) {
			return null;
		}
	
		if(!empty($var['name'])) {
			$serverInfo['name'] = $var['name'];
		}
		else {
			$serverInfo['name'] = $server['name'];
		}
	
		if(!empty($var['address'])) {
			$serverInfo['address'] = $var['address'];
		}
		else {
			$serverInfo['address'] = $server['address'];
		}
	
		if(!empty($var['key'])) {
			$serverInfo['key'] = $var['key'];
		}
		else {
			$serverInfo['key'] = $server['key'];
		}
		
		if(!empty($var['description'])) {
			$serverInfo['description'] = $var['description'];
		}
		else {
			$serverInfo['description'] = $server['description'];
		}
		
		$link = Db::link();
		$sql = 'UPDATE server
		        SET server_name= :server_name,
		            server_address= :server_address,
		            server_description= :server_description,
		            server_key = :server_key
		        WHERE server_id= :server_id';
		$req = $link->prepare($sql);
		$req->execute(array(
				'server_name'    => $serverInfo['name'],
				'server_address' => $serverInfo['address'],
				'server_description' => $serverInfo['description'],
				'server_key'  => $serverInfo['key'],
				'server_id'   => $server['id']
		));
	}
	
	/**
	 * Reload server information
	 * @param number $id
	 */
	function serverReload($id=0) {
	
		$link = Db::link();
		if($id == 0) {
			$sql = 'SELECT server_id, server_name, server_address,
		               server_description, server_key
			        FROM server';
			$req = $link->query($sql);
		}
		else {
			$sql = 'SELECT server_id, server_name, server_address,
		               server_description, server_key
			        FROM server
			        WHERE server_id= :server_id';
			$req = $link->prepare($sql);
			$req->execute(array('server_id' => $id));
		}
	
		while($do = $req->fetch(PDO::FETCH_OBJ)) {
			$server = new Server($do->server_id);
			$server -> setAddress($do->server_address);
			$server -> setKey($do->server_key);
			$server -> vpsReload();
		}
	}

	/**
	 * Restart server
	 * @param server id
	 */
	function serverRestart($id=0) {
	
		$link = Db::link();
		$sql = 'SELECT server_id, server_name, server_address,
		               server_description, server_key
		        FROM server
		        WHERE server_id= :server_id';
		$req = $link->prepare($sql);
		$req->execute(array('server_id' => $id));
	
		$do = $req->fetch(PDO::FETCH_OBJ);
	
		if(!empty($do->server_id)) {
			$server = new Server($do->server_id);
			$server -> setAddress($do->server_address);
			$server -> setKey($do->server_key);
			$server -> restart();
		}
	}
	
	/**
	 * Return server template
	 * @param server id
	 * @return template list
	 */
	function serverTemplate($id=0) {
		$link = Db::link();
		$sql = 'SELECT server_id, server_name, server_address,
		               server_description, server_key
		        FROM server
		        WHERE server_id= :server_id';
		$req = $link->prepare($sql);
		$req->execute(array('server_id' => $id));
	
		$do = $req->fetch(PDO::FETCH_OBJ);
	
		if(!empty($do->server_id)) {
			$server = new Server($do->server_id);
			$server -> setAddress($do->server_address);
			$server -> setKey($do->server_key);
			$list = array();
	
			$tpl_list = $server -> templateList();
	
			if(empty($tpl_list)) {
				return null;
			}
				
			//Get all templates names without extension
			foreach ($tpl_list[2] as $template) {
				if(substr($template, -7) == '.tar.gz') {
					$list[] = substr($template, 0, -7);
				}
				elseif(substr($template, -8) == '.tar.bz2') {
					$list[] = substr($template, 0, -8);
				}
				elseif(substr($template, -7) == '.tar.xz') {
					$list[] = substr($template, 0, -7);
				}
				elseif(substr($template, -4) == '.tar') {
					$list[] = substr($template, 0, -4);
				}
				elseif(substr($template, -3) == '.xz') {
					$list[] = substr($template, 0, -3);
				}
				else {
					$list[] = $template;
				}
			}
	
			return $list;
		}
	
		return null;
	}
	
	/*** VPS ***/
	
	/**
	 * Return Vps list
	 * @param server id
	 */
	function vpsList($server=0) {
	
		$list = array();
		$link = Db::link();
		
		if($server == 0) {
			$sql = 'SELECT vps_id, vps_name, vps_ipv4, vps_description,
			               user_id, server_id, last_maj, ram,
			               ram_current, ostemplate, diskspace, nproc,
			               vps.server_id, user_name, vps_cpus as cpus,
			               diskspace_current, loadavg,
			               swap, onboot, diskinodes, vps_cpulimit,
					       vps_cpuunits
			        FROM vps
					LEFT JOIN user ON vps_owner=user_id';
			$req = $link->prepare($sql);
			$req->execute();
		}
		else {
			$sql = 'SELECT vps_id, vps_name, vps_ipv4, vps_description,
			               user_id, server_id, last_maj, ram,
			               ram_current, ostemplate, diskspace, nproc,
			               vps.server_id, user_name, vps_cpus as cpus,
			               diskspace_current, loadavg,
			               swap, onboot, diskinodes, vps_cpulimit,
					       vps_cpuunits
			        FROM vps
					LEFT JOIN user ON vps_owner=user_id
			        WHERE server_id= :server_id';
			$req = $link->prepare($sql);
			$req->execute(array('server_id' => $server));
		}
	
			while ($do = $req->fetch(PDO::FETCH_OBJ)) {
			$list[$do->vps_id] = array(
				'id'          => $do->vps_id,
				'name'        => $do->vps_name,
				'ipv4'        => $do->vps_ipv4,
				'description' => $do->vps_description,
				'ostemplate'  => $do->ostemplate,
				'ram'         => $do->ram,
				'ramCurrent'  => $do->ram_current,
				'disk'        => $do->diskspace,
				'nproc'       => $do->nproc,
				'serverId'    => $do->server_id,
				'ownerId'     => $do->user_id,
				'ownerName'   => $do->user_name,
				'loadavg'     => $do->loadavg,
				'cpus'        => $do->cpus,
				'diskspace'   => $do->diskspace,
				'diskspaceCurrent' => $do->diskspace_current,
				'swap'        => $do->swap,
				'onboot'      => $do->onboot,
				'diskinodes'  => $do->diskinodes,
				'cpulimit'    => $do->vps_cpulimit,
				'cpuunits'    => $do->vps_cpuunits
			);
		}
	
		return $list;
	}
	
	/**
	 * Set VPS configuration
	 * @param unknown $id
	 * @param unknown $var
	 */
	function vpsAdd($id, $var) {
		$link = Db::link();
		$sql = 'SELECT server_id, server_name, server_address,
		               server_description, server_key
		        FROM server
		        WHERE server_id= :server_id';
		$req = $link->prepare($sql);
		$req->execute(array('server_id' => $id));
	
		$do = $req->fetch(PDO::FETCH_OBJ);
	
		$server = new Server($do->server_id);
		$server -> setAddress($do->server_address);
		$server -> setKey($do->server_key);
	
		//Trim
		$var = array_map('trim', $var);
	
		$para = array();
	
		if(!empty($var['name'])) {
			$para['name'] = $var['name'];
		}
	
		if(!empty($var['onboot'])) {
			$para['onboot'] = 1;
		}
		else {
			$para['onboot'] = 0;
		}
	
		if(!empty($var['ipv4']) && filter_var($var['ipv4'], FILTER_VALIDATE_IP)) {
			$para['ipv4'] = $var['ipv4'];
		}
	
		if (!empty($var['ram'])) {
			$ram = strtolower(str_replace(' ', '', $var['ram']));
	
			//Unlimited
			if($ram == 0 || $ram == 'unlimited') {
				$ram = 0;
			}
			//GB
			elseif(substr($ram, -2) == 'gb') {
				$ram = substr($ram, 0, -2)*1024;
			}
			//MB
			elseif(substr($ram, -2) == 'mb') {
				$ram = substr($ram, 0, -2);
			}
			//MB
			elseif(substr($ram, -1) == 'm') {
				$ram = substr($ram, 0, -1);
			}
	
			if(is_numeric($ram) && $ram >= 0) {
				$para['ram'] = $ram;
			}
		}
	
		if (!empty($var['swap'])) {
			$swap = strtolower(str_replace(' ', '', $var['swap']));
	
			//Unlimited
			if($swap == 0 || $swap == 'unlimited') {
				$swap = 0;
			}
			//GB
			elseif(substr($swap, -2) == 'gb') {
				$swap = substr($swap, 0, -2)*1024;
			}
			//MB
			elseif(substr($swap, -2) == 'mb') {
				$swap = substr($swap, 0, -2);
			}
			//MB
			elseif(substr($swap, -1) == 'm') {
				$swap = substr($swap, 0, -1);
			}
	
			if(is_numeric($swap) && $swap >= 0) {
				$para['swap'] = $swap;
			}
		}
	
		if (!empty($var['diskspace'])) {
			$diskspace = strtolower(str_replace(' ', '', $var['diskspace']));
	
			//Unlimited
			if($diskspace == 0 || $diskspace == 'unlimited') {
				$diskspace = 0;
			}
			//GB
			elseif(substr($diskspace, -2) == 'gb') {
				$diskspace = substr($diskspace, 0, -2)*1024;
			}
			//MB
			elseif(substr($diskspace, -2) == 'mb') {
				$diskspace = substr($diskspace, 0, -2);
			}
			//MB
			elseif(substr($diskspace, -1) == 'm') {
				$diskspace = substr($diskspace, 0, -1);
			}
	
			if(is_numeric($diskspace) && $diskspace >= 0) {
				$para['diskspace'] = $diskspace;
			}
		}
	
		if(!empty($var['diskinodes']) && $var['diskinodes'] > 0) {
			$para['diskinodes'] = $var['diskinodes'];
		}
	
		if(!empty($var['cpus']) && $var['cpus'] > 0) {
			$para['cpus'] = $var['cpus'];
		}
	
		if(!empty($var['cpulimit']) && $var['cpulimit'] > 0) {
			$para['cpulimit'] = $var['cpulimit'];
		}
	
		if(!empty($var['cpuunits']) && $var['cpuunits'] > 0) {
			$para['cpuunits'] = $var['cpuunits'];
		}
	
		if(!empty($var['os'])) {
			$para['os'] = $var['os'];
		}
	
		$vpsId=self::vpsNextId();
	
		if(!empty($server->getId()) && $vpsId > 0) {
			$server -> setVpsAdd($vpsId, $para);
		}
	}
	
	/**
	 * Set VPS configuration
	 * @param unknown $id
	 * @param unknown $var
	 */
	function vpsUpdate($id, $var) {
		$link = Db::link();
	
		//Trim
		$var = array_map('trim', $var);
	
		$para = array();
		$vpsList = $this->vpsList();
		$vps = $vpsList[$id];
	
		if(!empty($var['name']) && $var['name'] != $vps['name']) {
			$para['name'] = $var['name'];
		}
	
		if(isset($var['onboot'])) {
			if(!empty($var['onboot'])) {
				$para['onboot'] = 1;
			}
			else {
				$para['onboot'] = 0;
			}
		}
	
		if(!empty($var['ipv4']) && $var['ipv4'] != $vps['ipv4'] &&
		filter_var($var['ipv4'], FILTER_VALIDATE_IP)) {
			$para['ipv4'] = $var['ipv4'];
		}
	
		if (isset($var['ram'])) {
			$ram = strtolower(str_replace(' ', '', $var['ram']));
	
			//Unlimited
			if($ram == 0 || $ram == 'unlimited') {
				$ram = 0;
			}
			//GB
			elseif(substr($ram, -2) == 'gb') {
				$ram = substr($ram, 0, -2)*1024;
			}
			//MB
			elseif(substr($ram, -2) == 'mb') {
				$ram = substr($ram, 0, -2);
			}
			//MB
			elseif(substr($ram, -1) == 'm') {
				$ram = substr($ram, 0, -1);
			}
	
			if(is_numeric($ram) && $ram >= 0 && $ram*1024 != $vps['ram']) {
				$para['ram'] = $ram;
			}
		}
	
		if (isset($var['swap'])) {
			$swap = strtolower(str_replace(' ', '', $var['swap']));
	
			//Unlimited
			if($swap == 0 || $swap == 'unlimited') {
				$swap = 0;
			}
			//GB
			elseif(substr($swap, -2) == 'gb') {
				$swap = substr($swap, 0, -2)*1024;
			}
			//MB
			elseif(substr($swap, -2) == 'mb') {
				$swap = substr($swap, 0, -2);
			}
			//MB
			elseif(substr($swap, -1) == 'm') {
				$swap = substr($swap, 0, -1);
			}
	
			if(is_numeric($swap) && $swap >= 0 && $swap*1024 != $vps['swap']) {
				$para['swap'] = $swap;
			}
		}
	
		if (!empty($var['diskspace'])) {
			$diskspace = strtolower(str_replace(' ', '', $var['diskspace']));
	
			//Unlimited
			if($diskspace == 0 || $diskspace == 'unlimited') {
				$diskspace = 0;
			}
			//GB
			elseif(substr($diskspace, -2) == 'gb') {
				$diskspace = substr($diskspace, 0, -2)*1024;
			}
			//MB
			elseif(substr($diskspace, -2) == 'mb') {
				$diskspace = substr($diskspace, 0, -2);
			}
			//MB
			elseif(substr($diskspace, -1) == 'm') {
				$diskspace = substr($diskspace, 0, -1);
			}
	
			if(is_numeric($diskspace) && $diskspace >= 0 && $diskspace*1024 != $vps['diskspace']) {
				$para['diskspace'] = $diskspace;
			}
		}
	
		if(!empty($var['diskinodes']) && is_numeric($var['diskinodes']) &&
		$var['diskinodes'] != $vps['diskinodes'] && $var['diskinodes'] > 0) {
			$para['diskinodes'] = $var['diskinodes'];
		}
	
		if(!empty($var['cpus']) && is_numeric($var['cpus']) &&
		$var['cpus'] != $vps['cpus'] && $var['cpus'] > 0) {
			$para['cpus'] = $var['cpus'];
		}
	
		if(!empty($var['cpulimit']) && is_numeric($var['cpulimit']) &&
		$var['cpulimit'] != $vps['cpulimit'] && $var['cpulimit'] > 0) {
			$para['cpulimit'] = $var['cpulimit'];
		}
	
		if(!empty($var['cpuunits']) && is_numeric($var['cpuunits']) &&
		$var['cpuunits'] != $vps['cpuunits'] && $var['cpuunits'] > 0) {
			$para['cpuunits'] = $var['cpuunits'];
		}
	
		if(!empty($vps['serverId'])) {
			$sql = 'SELECT server.server_id, server_address, vps_id,
			               server_key
			        FROM vps
					JOIN server ON vps.server_id=server.server_id
			        WHERE vps_id=:vps_id';
			$req = $link->prepare($sql);
			$req->execute(array('vps_id' => $vps['id']));
			$do = $req->fetch(PDO::FETCH_OBJ);
			
			$server = new Server($do-> server_id);
			$server -> setAddress($do-> server_address);
			$server -> setKey($do->server_key);
			$server -> vpsUpdate($do-> vps_id, $para);
	
			if(!empty($var['owner']) && $var['owner'] > 0) {
				$list = $this->userList();
	
				if(!empty($list[$var['owner']])) {
					$sql = 'UPDATE vps
					        SET vps_owner=:owner
					        WHERE vps_id= :vps';
					$req = $link->prepare($sql);
					$req->execute(array(
						'owner' => $var['owner'],
						'vps'   => $vps['id']
					));
				}
			}
		}
	}
	
	/**
	 * Delete Vps
	 * @param vps id
	 */
	function vpsDelete($id) {
		$link = Db::link();
	
		$sql = 'SELECT vps_id, server.server_id, server_address,
		               server_key
		        FROM vps
		        JOIN server ON server.server_id=vps.server_id
		        WHERE vps_id= :id';
		$req = $link->prepare($sql);
		$req->execute(array('id' => $id));
		$do = $req->fetch(PDO::FETCH_OBJ);
	
		if(!empty($do->server_id)) {
			$server = new Server($do->server_id);
			$server -> setAddress($do->server_address);
			$server -> setKey($do->server_key);
			$server -> delete($do->vps_id);
		}
	}
	
	/**
	 * Start Vps
	 * @param vps id
	 */
	function vpsStart($id) {
		$link = Db::link();
	
		$sql = 'SELECT vps_id, server.server_id, server_address,
		               server_key, vps_owner
		        FROM vps
		        JOIN server ON server.server_id=vps.server_id
		        WHERE vps_id= :id';
		$req = $link->prepare($sql);
		$req->execute(array('id' => $id));
		$do = $req->fetch(PDO::FETCH_OBJ);
	
		if(!empty($do->server_id)) {
			$server = new Server($do->server_id);
			$server -> setAddress($do->server_address);
			$server -> setKey($do->server_key);
			$server -> start($do->vps_id);
		}
	}
	
	/**
	 * Stop Vps
	 * @param vps id
	 */
	function vpsStop($id) {
		$link = Db::link();
	
		$sql = 'SELECT vps_id, server.server_id, server_address,
		               server_key
		        FROM vps
		        JOIN server ON server.server_id=vps.server_id
		        WHERE vps_id= :id';
		$req = $link->prepare($sql);
		$req->execute(array('id' => $id));
		$do = $req->fetch(PDO::FETCH_OBJ);
	
		if(!empty($do->server_id)) {
			$server = new Server($do->server_id);
			$server -> setAddress($do->server_address);
			$server -> setKey($do->server_key);
			$server -> stop($do->vps_id);
		}
	}
	
	/**
	 * Restart Vps
	 * @param vps id
	 */
	function vpsRestart($id) {
		$link = Db::link();
	
		$sql = 'SELECT vps_id, server.server_id, server_address,
		               server_key
		        FROM vps
		        JOIN server ON server.server_id=vps.server_id
		        WHERE vps_id= :id';
		$req = $link->prepare($sql);
		$req->execute(array('id' => $id));
		$do = $req->fetch(PDO::FETCH_OBJ);
	
		if(!empty($do->server_id)) {
			$server = new Server($do->server_id);
			$server -> setAddress($do->server_address);
			$server -> setKey($do->server_key);
			$server -> restart($do->vps_id);
		}
	}
	
	/**
	 *
	 * @param unknown $idVps
	 * @param unknown $ip
	 * @param unknown $hostname
	 */
	function vpsClone($idVps, $ip, $hostname) {
		$link = Db::link();
	
		$sql = 'SELECT vps_id, server.server_id, server_address,
		               server_key
		        FROM vps
		        JOIN server ON server.server_id=vps.server_id
		        WHERE vps_id= :id';
		$req = $link->prepare($sql);
		$req->execute(array('id' => $idVps));
		$do = $req->fetch(PDO::FETCH_OBJ);
	
		if(!empty($do->server_id) && in_array($ip, $this->ipFree())) {
			$vpsNewsId=self::vpsNextId();
	
			$para = array(
					'dest'     => $vpsNewsId,
					'ip'       => $ip,
					'hostname' => $hostname
			);
	
			$connect = new Socket($do->server_address, PORT, $do->server_key);
			$connect -> write('clone', $do->vps_id, $para);
			$data = json_decode($connect -> read());
		}
	}

	/**
	 * Execute a command on a VPS
	 * @param vps id
	 * @param command
	 * @return command answer
	 */
	function vpsCmd($idVps, $command) {
		$link = Db::link();
	
		$sql = 'SELECT vps_id, server.server_id, server_address,
		               server_key
		        FROM vps
		        JOIN server ON server.server_id=vps.server_id
		        WHERE vps_id= :id';
		$req = $link->prepare($sql);
		$req->execute(array('id' => $idVps));
		$do = $req->fetch(PDO::FETCH_OBJ);
	
		if(!empty($do->server_id)) {
			$connect = new Socket($do->server_address, PORT, $do->server_key);
			$connect -> write('cmd', $do->vps_id, $command);
			return $data = json_decode($connect -> read());
		}
		return '';
	}
	
	/**
	 * Modifie Vps root password
	 * @param vps id
	 * @param new password
	 */
	function vpsPassword($idVps, $password) {
		$link = Db::link();
	
		$sql = 'SELECT vps_id, server.server_id, server_address,
		               server_key
		        FROM vps
		        JOIN server ON server.server_id=vps.server_id
		        WHERE vps_id= :id';
		$req = $link->prepare($sql);
		$req->execute(array('id' => $idVps));
		$do = $req->fetch(PDO::FETCH_OBJ);
	
		if(!empty($do->server_id)) {
			$connect = new Socket($do->server_address, PORT, $do->server_key);
			$connect -> write('password', $do->vps_id, $password);
			$data = json_decode($connect -> read());
		}
	}
	
	/**
	 * Reinstall a VPS
	 * @param vps id
	 * @param operating system
	 */
	function vpsReinstall($idVps, $os) {
		$link = Db::link();
	
		$sql = 'SELECT vps_id, server.server_id, server_address,
		               server_key
		        FROM vps
		        JOIN server ON server.server_id=vps.server_id
		        WHERE vps_id= :id';
		$req = $link->prepare($sql);
		$req->execute(array('id' => $idVps));
		$do = $req->fetch(PDO::FETCH_OBJ);
	
		if(!empty($do->server_id)) {
			$templates = $this->getServerTemplate($do->server_id);
	
			if(in_array($os, $templates)) {
				$connect = new Socket($do->server_address, PORT, $do->server_key);
				$connect -> write('reinstall', $do->vps_id, $os);
				return $data = json_decode($connect -> read());
			}
		}
	
		return null;
	}
}

?>