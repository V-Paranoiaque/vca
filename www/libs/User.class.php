<?php 

/**
 * User class
 * @author V_paranoiaque
 */
class User extends Guest {
	
	private $id;
	private $name;
	private $rank;
	private $mail;
	private $nbVps;
	private $language;
	private $bkppass;
	private $dropbox;
	
	/*** Get/Set ***/
	
	/**
	 * Constructor
	 * @param number $id user's id
	 * @param string $name user's name
	 */
	function __construct($id, $name='') {
		$this->id   = $id;
		$this->name = $name;
	}
	
	/**
	 * Return the user's ID
	 */
	function getId() {
		return $this->id;
	}
	
	/**
	 * Return the user's name
	 */
	function getName() {
		return $this->name;
	}
	
	/**
	 * Set the user's name
	 * @param string $name the name
	 */
	function setName($name) {
		$this->name = $name;
	}
	
	/**
	 * Return the user's rank
	 */
	function getRank() {
		return $this->rank;
	}
	
	/**
	 * Set the user's rank
	 * @param number $rank the rank
	 */
	function setRank($rank) {
		$this->rank = $rank;
	}
	
	/**
	 * Return the user's email
	 */
	function getMail() {
		return $this->mail;
	}
	
	/**
	 * Set the user's email
	 * @param string $email user email
	 */
	function setMail($email) {
		$this->mail = $email;
	}
	
	/**
	 * Return the number of user's VPS
	 */
	function getNbVps() {
		return $this->nbVps;
	}
	
	/**
	 * Set the number of user's VPS
	 * @param number $nb number of VPS
	 */
	function setNbVps($nb) {
		$this->nbVps = $nb;
	}
	
	/**
	 * Return the user's language
	 */
	function getLanguage() {
		return $this->language;
	}
	
	/**
	 * Set the user's language
	 */
	function setLanguage($language) {
		$this->language = $language;
	}
	
	/**
	 * Get Backup password
	 */
	function getBkppass() {
		return $this->bkppass;
	}
	
	/**
	 * Set Backup password
	 * @param string $pass Backup password
	 */
	function setBkppass($pass) {
		$this->bkppass = $pass;
	}
	
	/**
	 * Get Dropbox token
	 */
	function getDropbox() {
		return $this->dropbox;
	}
	
	/**
	 * Set Dropbox token
	 * @param string $token Dropbox identication token
	 */
	function setDropbox($token) {
		$this->dropbox = $token;
	}
	
	/*** VCA ***/
	
	/**
	 * VCA stats
	 */
	function vcaStats() {
		$link = Db::link();
			
		$sql = 'SELECT count(vps_id) as nb
		        FROM vps
		        WHERE vps_owner= :user_id AND nproc>0';
		$req = $link->prepare($sql);
		$req->bindValue(':user_id', $this->getId(), PDO::PARAM_INT);
		$req->execute();
		$do = $req->fetch(PDO::FETCH_OBJ);
		$nbVpsRun = $do->nb;
	
		$sql = 'SELECT count(vps_id) as nb
		        FROM vps
		        WHERE vps_owner= :user_id AND nproc=0';
		$req = $link->prepare($sql);
		$req->bindValue(':user_id', $this->getId(), PDO::PARAM_INT);
		$req->execute();
		$do = $req->fetch(PDO::FETCH_OBJ);
		$nbVpsStop = $do->nb;
		
		$sql = 'SELECT count(request_topic_id) as nb
		        FROM request_topic
		        WHERE request_topic_author= :user_id AND request_topic_resolved=0';
		$req = $link->prepare($sql);
		$req->bindValue(':user_id', $this->getId(), PDO::PARAM_INT);
		$req->execute();
		$do = $req->fetch(PDO::FETCH_OBJ);
		$request = $do->nb;
	
		return array(
				'nbVps'    => $nbVpsRun+$nbVpsStop,
				'nbVpsRun' => $nbVpsRun,
				'nbVpsStop'=> $nbVpsStop,
				'nbServer' => 0,
				'nbUser'   => 0,
				'nbIp'     => 0,
				'request'  => $request
		);
	}
	
	/**
	 * Define the Token configuration
	 * @param string $domainkey shared domain key
	 * @param number $key_size generated code size
	 * @param timestamp $validity generated code validity
	 */
	function configurationDefine($domainkey, $key_size,$validity) {
		
	}
	
	/*** Users ***/
	
	/**
	 * Update activity
	 */
	function update() {
		$link = Db::link();
		
		$sql = 'UPDATE uservca
		        SET user_activity= :user_activity
		        WHERE user_id= :user_id';
		$req = $link->prepare($sql);
		$req->bindValue(':user_activity', $_SERVER['REQUEST_TIME'], PDO::PARAM_INT);
		$req->bindValue(':user_id', $this->getId(), PDO::PARAM_INT);
		$req->execute();
	}
	
	/**
	 * User's profile information
	 */
	function userProfile() {
		$link = Db::link();
		
		$sql = 'SELECT user_id, user_name, user_rank, user_mail, v.nb,
		               user_strongauth, user_tokenid
		        FROM uservca
		        LEFT JOIN (SELECT vps_owner, count(vps_owner) as nb
		                   FROM vps GROUP BY vps_owner) v
		        ON v.vps_owner=uservca.user_id
		        WHERE user_id=:user_id';
		$req = $link->prepare($sql);
		$req->bindValue(':user_id', $this->getId(), PDO::PARAM_INT);
		$req->execute();
		$do = $req->fetch(PDO::FETCH_OBJ);
		
		if(empty($do->nb)) {
			$do->nb = 0;
		}
		
		return $do;
	}
	
	/**
	 * Return all users
	 */
	function userList() { return null; }
	
	/**
	 * Update user information
	 * @param number $id user id
	 * @param string $user_name user name
	 * @param string $user_mail user mail
	 * @param string $language user language
	 * @param number $rank user rank (not used)
	 * @return error id
	 */
	function userUpdate($id, $user_name='', $user_mail='', $language='', $rank=-1) {
		$link = Db::link();
		$id = $this->getId();
		
		if(empty($user_name) or empty($user_mail)) {
			return 1;
		}
		elseif(!filter_var($user_mail, FILTER_VALIDATE_EMAIL)) {
			return 9;
		}
		elseif(strlen($user_name) < 4 or strlen($user_name) > 25) {
			return 10;
		}
		
		$languageList = self::languageList();
		
		if(empty($language) or empty($languageList[$language])) {
			$language = $this->language;
		}
		
		$sql = 'UPDATE uservca
		        SET user_name= :user_name,
		            user_mail= :user_mail,
		            user_language=:user_language
		        WHERE user_id= :user_id';
		$req = $link->prepare($sql);
		$req->bindValue(':user_name', $user_name, PDO::PARAM_STR);
		$req->bindValue(':user_mail', $user_mail, PDO::PARAM_STR);
		$req->bindValue(':user_language', $language, PDO::PARAM_STR);
		$req->bindValue(':user_id', $id, PDO::PARAM_INT);
		$req->execute();
		
		return 5;
	}
	
	/**
	 * Update password
	 * @param string $password new password
	 * @param number $id user id
	 */
	function userDefinePassword($password, $id=0) {}
	
	/**
	 * Define user token information
	 * @param number $tokenId user token id
	 * @param number $pin user pin
	 * @param boolean $activated activated or not
	 * @param number $userId user id
	 */
	function userDefineToken($tokenId, $pin, $activated, $userId) {}
	
	/**
	 * Update password
	 * @param string $old old password
	 * @param string $new new password
	 */
	function userPassword($old, $new) {
		$link = Db::link();
		
		$sql = 'SELECT user_id, user_password
		        FROM uservca
		        WHERE user_id=:user_id';
		$req = $link->prepare($sql);
		$req->bindValue(':user_id', $this->getId(), PDO::PARAM_INT);
		$req->execute();
		$do = $req->fetch(PDO::FETCH_OBJ);
		
		if($do->user_password == hash('sha512', $this->getId().$old)) {
			$sql = 'UPDATE uservca
			        SET user_password=:user_password
			        WHERE user_id=:user_id';
			$req = $link->prepare($sql);
			$req->bindValue(':user_password', hash('sha512', $this->getId().$new), PDO::PARAM_STR);
			$req->bindValue(':user_id', $this->getId(), PDO::PARAM_INT);
			$req->execute();
			
			return 13;
		}
		else {
			return 11;
		}
	}
	
	/**
	 * Define user token information
	 * @param number $pin user pin
	 * @param boolean $activated activated or not
	 */
	function userToken($pin, $activated) {
		$link = Db::link();
		
		$sql = 'UPDATE uservca
		        SET user_strongauth=:user_strongauth
		        WHERE user_id=:user_id';
		$req = $link->prepare($sql);
		$req->bindValue(':user_strongauth', $activated, PDO::PARAM_INT);
		$req->bindValue(':user_id', $this->getId(), PDO::PARAM_INT);
		$req->execute();
		
		if(!empty($pin)) {
			$sql = 'UPDATE uservca
			        SET user_pin=:user_pin
			        WHERE user_id=:user_id';
			$req = $link->prepare($sql);
			$req->bindValue(':user_pin', $pin, PDO::PARAM_STR);
			$req->bindValue(':user_id', $this->getId(), PDO::PARAM_INT);
			$req->execute();
		}
		
		return 14;
	}
	
	/**
	 * Create a new user
	 * @param string $user_name user name
	 * @param string $user_mail user mail
	 * @param string $user_password user password
	 * @return number error
	 */
	function userNew($user_name='', $user_mail='', $user_password='') { return null; }

	/**
	 * Delete an user
	 * @param number $id user id
	 */
	function userDelete($id) { return null; }

	/**
	 * Return the current user VPS list
	 * @param number $id user id (not used)
	 */
	function userVps($id) { return $this->vpsList(); }
	
	/**
	 * Modify the user's language
	 * @param string $lang new language
	 */
	function userLanguage($lang) {
		$link = Db::link();
		$langList = self::languageList();
		
		if(!empty($langList[$lang])) {
			$sql = 'UPDATE uservca
			        SET user_language= :user_language
			        WHERE user_id= :user_id';
			$req = $link->prepare($sql);
			$req->bindValue(':user_language', $lang, PDO::PARAM_STR);
			$req->bindValue(':user_id', $this->getId(), PDO::PARAM_INT);
			$req->execute();
		}
	}
	
	/*** Language ***/
	
	/**
	 * List all available languages
	 * @return array language list
	 */
	static function languageList() {
		return array(
			'en_GB' => 'English',
			'es_ES' => 'Español',
			'fr_FR' => 'Français'
		);
	}
	
	/*** Requests ***/
	
	/**
	 * List all user's requests
	 */
	function requestList() {
		$list = array();
	
		$link = Db::link();
		$sql = 'SELECT request_topic_id, request_topic_title, request_topic_created,
		               request_topic_resolved
		        FROM request_topic
		        WHERE request_topic_author=:author
		        ORDER BY request_topic_id DESC';
		$req = $link->prepare($sql);
		$req->bindValue(':author', $this->getId(), PDO::PARAM_INT);
		$req->execute();
		
		while ($do = $req->fetch(PDO::FETCH_OBJ)) {
			$list[$do->request_topic_id] = array(
				'topic'    => $do->request_topic_id,
				'title'    => $do->request_topic_title,
				'created'  => $do->request_topic_created,
				'resolved' => $do->request_topic_resolved
			);
		}
	
		return $list;
	}
	
	/**
	 * Send a new request to the panel's admin
	 * @param string $title the title of the message
	 * @param string $message the message
	 */
	function requestNew($title, $message) {
		$link = Db::link();
		
		$sql = 'INSERT INTO request_topic
		        (request_topic_title, request_topic_created, request_topic_author)
		        VALUES
		        (:title, :created, :author)';
		$req = $link->prepare($sql);
		$req->bindValue(':title', ucfirst($title), PDO::PARAM_STR);
		$req->bindValue(':created', $_SERVER['REQUEST_TIME'], PDO::PARAM_INT);
		$req->bindValue(':author', $this->getId(), PDO::PARAM_INT);
		$req->execute();
		
		if(DB_TYPE == 'PGSQL') {
			$sql = 'SELECT currval(\'request_topic_request_topic_id_seq\') as request_id
			        FROM request_topic';
			$req = $link->prepare($sql);
			$req->execute();
			$do = $req->fetch(PDO::FETCH_OBJ);
			
			$requestId = $do->request_id;
		}
		else {
			$requestId = $link->lastInsertId();
		}
		
		$sql = 'INSERT INTO request_message
		        (request_topic, request_message, request_message_date, request_message_user)
		        VALUES
		        (:topic, :message, :date, :user)';
		$req = $link->prepare($sql);
		$req->bindValue(':topic', $requestId, PDO::PARAM_INT);
		$req->bindValue(':message', $message, PDO::PARAM_STR);
		$req->bindValue(':date', $_SERVER['REQUEST_TIME'], PDO::PARAM_INT);
		$req->bindValue(':user', $this->getId(), PDO::PARAM_INT);
		$req->execute();
	}
	
	/**
	 * Get all information for a request
	 * @param number $request request id
	 */
	function requestInfo($request) {
		$link = Db::link();
		$requestList = $this->requestList();
		
		if(empty($requestList[$request])) {
			return null;
		}
		
		$currentRequest = $requestList[$request];
		$messages = array();

		$sql2 = 'UPDATE request_message
		         SET request_message_read= :time
		         WHERE request_message_id= :id';
		$req2 = $link->prepare($sql2);
		$req2->bindValue(':time', $_SERVER['REQUEST_TIME'], PDO::PARAM_INT);
		$req2->bindParam(':id', $messagesId, PDO::PARAM_INT);
		
		$sql = 'SELECT request_message, request_message_date,
		               user_name, request_message_user,
		               request_message_read, request_message_id
		        FROM request_message
		        JOIN uservca ON request_message_user=user_id
		        WHERE request_topic=:topic
		        ORDER BY request_message_id DESC';
		$req = $link->prepare($sql);
		$req->bindValue(':topic', $request, PDO::PARAM_INT);
		$req->execute();
		
		while ($do = $req->fetch(PDO::FETCH_OBJ)) {
			$messages[] = array(
				'message'  => $do->request_message,
				'date'     => $do->request_message_date,
				'user_id'  => $do->request_message_user,
				'user_name'=> $do->user_name
			);
			
			//Unread message
			if($do->request_message_read == 0 && $do->request_message_user != $this->getId()) {
				$messagesId = $do->request_message_id;
				$req2->execute();
			}
		}
				
		return array(
			'id'       => $request,
			'title'    => $currentRequest['title'],
			'resolved' => $currentRequest['resolved'],
			'messages' => $messages
		);
	}
	
	/**
	 * Anwser to a request
	 * @param number $request request id
	 * @param string $message message
	 */
	function requestAnswer($request, $message) {
		$link = Db::link();
		$requestList = $this->requestList();
		
		if(empty($requestList[$request]) or !empty($requestList[$request]['resolved'])) {
			return null;
		}
		
		$sql = 'INSERT INTO request_message
		        (request_topic, request_message, request_message_date, request_message_user)
		        VALUES
		        (:topic, :message, :date, :user)';
		$req = $link->prepare($sql);
		$req->bindValue(':topic', $request, PDO::PARAM_INT);
		$req->bindValue(':message', $message, PDO::PARAM_STR);
		$req->bindValue(':date', $_SERVER['REQUEST_TIME'], PDO::PARAM_INT);
		$req->bindValue(':user', $this->getId(), PDO::PARAM_INT);
		$req->execute();
	}
	
	/**
	 * Close a request
	 * @param number $request request id
	 */
	function requestClose($request) {
		$link = Db::link();
		$sql = 'UPDATE request_topic
		        SET request_topic_resolved= :resolved
		        WHERE request_topic_id= :request';
		$req = $link->prepare($sql);
		$req->bindValue(':request',  $request, PDO::PARAM_INT);
		$req->bindValue(':resolved', $_SERVER['REQUEST_TIME'], PDO::PARAM_INT);
		$req->execute();
	}
	
	/*** IP ***/

	/**
	 * List available IP
	 */
	function ipFree() { return null; }

	/**
	 * List all IP
	 */
	function ipList() { return null; }

	/**
	 * Add a new IP
	 * @param string $ip IP
	 */
	function ipNew($ip) { return null; }

	/**
	 * Delete an IP
	 * @param string $ip IP
	 */
	function ipDelete($ip) { return null; }
	
	/*** Serveur ***/
	
	/**
	 * Return all physical servers
	 */
	function serverList() { return null; }
	
	/**
	 * Add a new server on the panel
	 * @param string $name name
	 * @param string $address address (name or IP)
	 * @param number $port communication port
	 * @param string $key key
	 * @param string $description description
	 */
	function serverAdd($name,$address,$port,$key,$description='') { return null; }
	
	/**
	 * Remove a server from the panel
	 * @param number $id server id
	 */
	function serverDelete($id) { }
	
	/**
	 * Update server information
	 * @param number $id server id
	 * @param string $var all information
	 */
	function serverUpdate($id, $var)  { return null; }
	
	/**
	 * Reload server information
	 * @param number $id server id, all servers by default
	 */
	function serverReload($id=0) { return null; }
	
	/**
	 * Restart server
	 * @param number $id server id
	 */
	function serverRestart($id=0) { }
	
	/**
	 * Return server template
	 * @param number $id server id
	 * @return template list
	 */
	function serverTemplate($id=0) { return null; }
	
	/**
	 * Rename an OS template
	 * @param number $id server's id
	 * @param string $old old name
	 * @param string $new new name
	 */
	function serverTemplateRename($id, $old, $new) { return null; }
	
	/**
	 * Download/Upload a template
	 * @param number $id server id
	 * @param string $name template name
	 */
	function serverTemplateAdd($id, $name) { return null; }
	
	/**
	 * Delete a template
	 * @param number $id server id
	 * @param string $name template name
	 */
	function serverTemplateDelete($id, $name) { return null; }
	
	/**
	 * Refresh the template list
	 * @param number $server server id
	 */
	function serverTemplateRefresh($server) { return null; }
	
	/**
	 * List VPS backups
	 * @param number $server server id
	 */
	function serverBackup($server) { return null; }
	
	/**
	 * Delete a backup
	 * @param number $server server id
	 * @param number $idVps vps id
	 * @param string $name backup name
	 */
	function serverBackupDelete($server, $idVps, $name) { return null; }
	
	/**
	 * Scan a server with clamav
	 * @param number $server the server's id
	 */
	function serverScan($server) { return null; }
	
	/*** VPS ***/
	
	/**
	 * Return Vps list
	 * @param number $server server id
	 */
	function vpsList($server=0) {
	
		$list = array();
		$link = Db::link();
	
		$sql = 'SELECT vps_id, vps_name, vps_ipv4, vps_description,
		               user_id, server_id, last_maj, ram,
		               ram_current, ostemplate, diskspace, nproc,
		               vps.server_id, user_name, vps_cpus as cpus,
		               diskspace_current, loadavg,
		               swap, onboot, diskinodes, vps_cpulimit,
				       vps_cpuunits, backup_limit, vps_protected
		        FROM vps
				LEFT JOIN uservca ON vps_owner=user_id
				WHERE vps_owner=:id';
		$req = $link->prepare($sql);
		$req->bindValue(':id', $this->getId(), PDO::PARAM_INT);
		$req->execute();
		
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
				'serverId'    => 0,
				'ownerId'     => $do->user_id,
				'ownerName'   => $do->user_name,
				'loadavg'     => $do->loadavg,
				'cpus'        => $do->cpus,
				'diskspace'   => $do->diskspace,
				'diskspaceCurrent' => $do->diskspace_current,
				'swap'        => $do->swap,
				'onboot'      => $do->onboot,
				'protected'   => $do->vps_protected,
				'diskinodes'  => $do->diskinodes,
				'cpulimit'    => $do->vps_cpulimit,
				'cpuunits'    => $do->vps_cpuunits,
				'backup_limit'=> $do->backup_limit
			);
		}
	
		if(sizeof($list) == 0) {
			return '';
		}
		else {
			return $list;
		}
	}
	
	/**
	 * Set VPS configuration
	 * @param number $id server's id
	 * @param array $var all configuration
	 */
	function vpsAdd($id, $var) {}
	
	/**
	 * Set VPS configuration
	 * @param number $id Vps id
	 * @param array $var Vps parameters
	 */
	function vpsUpdate($id, $var) {
		$link = Db::link();
	
		//Trim
		$var = array_map('trim', $var);
		
		$para = array();
		$vpsList = $this->vpsList();
		$vps = $vpsList[$id];
		
		if(empty($vps)) {
			return null;
		}
		
		if(!empty($var['name']) && $var['name'] != $vps['name'] && checkHostname($var['name'])) {
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
		
		$sql = 'SELECT server.server_id, server_address, vps_id,
		               server_key, server_port
		        FROM vps
		        JOIN server ON vps.server_id=server.server_id
		        WHERE vps_id=:vps_id';
		$req = $link->prepare($sql);
		$req->bindValue(':vps_id', $vps['id'], PDO::PARAM_INT);
		$req->execute();
		$do = $req->fetch(PDO::FETCH_OBJ);
		
		if(!empty($do->vps_id)) {
			$server = new Server($do->server_id);
			$server -> setAddress($do->server_address);
			$server -> setPort($do->server_port);
			$server -> setKey($do->server_key);
			$server -> vpsUpdate($do->vps_id, $para);
		}
	}
	
	/**
	 * Reload server information
	 * @param number $id vps id
	 */
	function vpsReload($id=0) {
	
		$link = Db::link();
		$sql = 'SELECT vps.server_id, server_name, server_address,
		               server_description, server_key, server_port
		        FROM vps
		        JOIN server ON server.server_id=vps.server_id
		        WHERE vps_id= :vps_id';
		$req = $link->prepare($sql);
		$req->bindValue(':vps_id', $id, PDO::PARAM_INT);
		$req->execute();
		
		while($do = $req->fetch(PDO::FETCH_OBJ)) {
			$server = new Server($do->server_id);
			$server -> setAddress($do->server_address);
			$server -> setPort($do->server_port);
			$server -> setKey($do->server_key);
			$server -> vpsReload();
		}
	}
	
	/**
	 * Delete a Vps
	 * @param number $id vps id
	 */
	function vpsDelete($id) {}
	
	/**
	 * Start Vps
	 * @param number $id vps id
	 */
	function vpsStart($id) {
		$link = Db::link();
	
		$sql = 'SELECT vps_id, server.server_id, server_address,
		               server_key, vps_owner, server_port
		        FROM vps
		        JOIN server ON server.server_id=vps.server_id
		        WHERE vps_id= :id';
		$req = $link->prepare($sql);
		$req->bindValue(':id', $id, PDO::PARAM_INT);
		$req->execute();
		$do = $req->fetch(PDO::FETCH_OBJ);
	
		if(!empty($do->server_id) && $do->vps_owner == $this->getId()) {
			$server = new Server($do->server_id);
			$server -> setAddress($do->server_address);
			$server -> setPort($do->server_port);
			$server -> setKey($do->server_key);
			$server -> start($do->vps_id);
		}
	}
	
	/**
	 * Stop Vps
	 * @param number $id vps id
	 */
	function vpsStop($id) {
		$link = Db::link();
	
		$sql = 'SELECT vps_id, server.server_id, server_address,
		               server_key, vps_owner, server_port
		        FROM vps
		        JOIN server ON server.server_id=vps.server_id
		        WHERE vps_id= :id';
		$req = $link->prepare($sql);
		$req->bindValue(':id', $id, PDO::PARAM_INT);
		$req->execute();
		$do = $req->fetch(PDO::FETCH_OBJ);
	
		if(!empty($do->server_id) && $do->vps_owner == $this->getId()) {
			$server = new Server($do->server_id);
			$server -> setAddress($do->server_address);
			$server -> setPort($do->server_port);
			$server -> setKey($do->server_key);
			$server -> stop($do->vps_id);
		}
	}
	
	/**
	 * Restart Vps
	 * @param number $id vps id
	 */
	function vpsRestart($id) {
		$link = Db::link();
	
		$sql = 'SELECT vps_id, server.server_id, server_address,
		               server_key, vps_owner, server_port
		        FROM vps
		        JOIN server ON server.server_id=vps.server_id
		        WHERE vps_id= :id';
		$req = $link->prepare($sql);
		$req->bindValue(':id', $id, PDO::PARAM_INT);
		$req->execute();
		$do = $req->fetch(PDO::FETCH_OBJ);
	
		if(!empty($do->server_id) && $do->vps_owner == $this->getId()) {
			$server = new Server($do->server_id);
			$server -> setAddress($do->server_address);
			$server -> setPort($do->server_port);
			$server -> setKey($do->server_key);
			$server -> restart($do->vps_id);
		}
	}
	
	/**
	 * Clone a VPS
	 * @param number $idVps Vps's id
	 * @param string $ip Ip of the new Vps
	 * @param string $hostname Hostname of the new Vps
	 */
	function vpsClone($idVps, $ip, $hostname) {}
	
	/**
	 * Execute a command on a VPS
	 * @param number $idVps vps id
	 * @param string $command command
	 * @return command answer
	 */
	function vpsCmd($idVps, $command) {}
	
	/**
	 * Modifie Vps root password
	 * @param number $idVps vps id
	 * @param string $password new password
	 */
	function vpsPassword($idVps, $password) {
		$link = Db::link();
	
		$sql = 'SELECT vps_id, server.server_id, server_address,
		               server_key, vps_owner, server_port
		        FROM vps
		        JOIN server ON server.server_id=vps.server_id
		        WHERE vps_id= :id';
		$req = $link->prepare($sql);
		$req->bindValue(':id', $idVps, PDO::PARAM_INT);
		$req->execute();
		$do = $req->fetch(PDO::FETCH_OBJ);
	
		if(!empty($do->server_id) && $do->vps_owner == $this->getId()) {
			$connect = new Socket($do->server_address, $do->server_port, $do->server_key);
			$connect -> write('password', $do->vps_id, $password);
			$data = json_decode($connect -> read());
		}
	}
	
	/**
	 * Return server template
	 * @param number $id server id
	 * @return array template list
	 */
	function vpsTemplate($id=0) {
		$link = Db::link();
		$sql = 'SELECT vps.server_id, server_name, server_address,
		               server_description, server_key, server_port
		        FROM vps
		        JOIN server ON vps.server_id=server.server_id
		        WHERE vps_id= :server_id';
		$req = $link->prepare($sql);
		$req->bindValue(':server_id', $id, PDO::PARAM_INT);
		$req->execute();
		
		$do = $req->fetch(PDO::FETCH_OBJ);
	
		if(!empty($do->server_id)) {
			
			if(apc_exists(CACHE.'_TPL_'.$do->server_id)) {
				return unserialize(apc_fetch(CACHE.'_TPL_'.$do->server_id));
			}
			
			$server = new Server($do->server_id);
			$server -> setAddress($do->server_address);
			$server -> setPort($do->server_port);
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
			
			if(!empty($list) && sizeof($list) > 0) {
				apc_store(CACHE.'_TPL_'.$do->server_id, serialize($list));
			}
			
			return $list;
		}
	
		return null;
	}
	
	/**
	 * Reinstall a VPS
	 * @param number $idVps vps id
	 * @param string $os operating system
	 */
	function vpsReinstall($idVps, $os) {
		$link = Db::link();
	
		$sql = 'SELECT vps_id, server.server_id, server_address,
		               server_key, vps_owner, server_port,
		               vps_protected
		        FROM vps
		        JOIN server ON server.server_id=vps.server_id
		        WHERE vps_id= :id';
		$req = $link->prepare($sql);
		$req->bindValue(':id', $idVps, PDO::PARAM_INT);
		$req->execute();
		$do = $req->fetch(PDO::FETCH_OBJ);
	
		if(!empty($do->server_id) && $do->vps_owner == $this->getId() && empty($do->vps_protected)) {
			$templates = $this->vpsTemplate($do->vps_id);
	
			if(!empty($templates) && in_array($os, $templates)) {
				$connect = new Socket($do->server_address, $do->server_port, $do->server_key);
				$connect -> write('reinstall', $do->vps_id, $os);
				return $data = json_decode($connect -> read());
			}
		}
	
		return null;
	}
	
	/**
	 * Move a VPS
	 * @param number $serverFrom server id source
	 * @param number $vps vps id
	 * @param number $serverDest server id destination
	 */
	function vpsMove($serverFrom, $vps, $serverDest) {
		return null;
	}
	
	/**
	 * Return the number of VPS of the current user
	 */
	function vpsNb() {
		$link = Db::link();
		
		$sql = 'SELECT count(vps_id) as nb
		        FROM vps
		        WHERE vps_owner= :user_id';
		$req = $link->prepare($sql);
		$req->bindValue(':user_id', $this->getId(), PDO::PARAM_INT);
		$req->execute();
		$do = $req->fetch(PDO::FETCH_OBJ);
		
		return $do->nb;
	}
	
	/**
	 * List all the backups of a Vps
	 * @param number $idVps Vps's id
	 */
	static function vpsBackup($idVps) {
		$link = Db::link();
	
		$sql = 'SELECT vps_id, server.server_id, server_address,
		               server_key, vps_owner, server_port
		        FROM vps
		        JOIN server ON server.server_id=vps.server_id
		        WHERE vps_id= :id';
		$req = $link->prepare($sql);
		$req->bindValue(':id', $idVps, PDO::PARAM_INT);
		$req->execute();
		$do = $req->fetch(PDO::FETCH_OBJ);
	
		if(!empty($do->server_id)) {
			$connect = new Socket($do->server_address, $do->server_port, $do->server_key);
			$connect -> write('backupList', $do->vps_id);
			return $data = json_decode($connect -> read());
		}
	}
	
	/**
	 * Create a num backup
	 * @param number $idVps vps id
	 */
	static function vpsBackupAdd($idVps) {
		$link = Db::link();
	
		$sql = 'SELECT vps_id, server.server_id, server_address,
		               server_key, vps_owner, server_port,
		               backup_limit
		        FROM vps
		        JOIN server ON server.server_id=vps.server_id
		        WHERE vps_id= :id';
		$req = $link->prepare($sql);
		$req->bindValue(':id', $idVps, PDO::PARAM_INT);
		$req->execute();
		$do = $req->fetch(PDO::FETCH_OBJ);
	
		if(!empty($do->backup_limit)) {
			$list = self::vpsBackup($do->vps_id);
			if(sizeof($list) >= $do->backup_limit) {
				return null;
			}
		}
		
		if(!empty($do->server_id)) {
			$connect = new Socket($do->server_address, $do->server_port, $do->server_key);
			$connect -> write('backupAdd', $do->vps_id);
			return $data = json_decode($connect -> read());
		}
	}
	
	/**
	 * Restore a backup
	 * @param number $idVps vps id
	 * @param string $name backup name
	 */
	function vpsBackupRestore($idVps, $name) {
		$link = Db::link();
	
		$sql = 'SELECT vps_id, server.server_id, server_address,
		               server_key, vps_owner, server_port
		        FROM vps
		        JOIN server ON server.server_id=vps.server_id
		        WHERE vps_id= :id';
		$req = $link->prepare($sql);
		$req->bindValue(':id', $idVps, PDO::PARAM_INT);
		$req->execute();
		$do = $req->fetch(PDO::FETCH_OBJ);
	
		if(!empty($do->server_id)) {
			$connect = new Socket($do->server_address, $do->server_port, $do->server_key);
			$connect -> write('backupRestore', $do->vps_id, $name);
			return $data = json_decode($connect -> read());
		}
	}
	
	/**
	 * Delete a backup
	 * @param number $idVps vps id
	 * @param string $name backup name
	 */
	function vpsBackupDelete($idVps, $name) {
		$link = Db::link();
	
		$sql = 'SELECT vps_id, server.server_id, server_address,
		               server_key, vps_owner, server_port
		        FROM vps
		        JOIN server ON server.server_id=vps.server_id
		        WHERE vps_id= :id';
		$req = $link->prepare($sql);
		$req->bindValue(':id', $idVps, PDO::PARAM_INT);
		$req->execute();
		$do = $req->fetch(PDO::FETCH_OBJ);
	
		if(!empty($do->server_id)) {
			$connect = new Socket($do->server_address, $do->server_port, $do->server_key);
			$connect -> write('backupDelete', $do->vps_id, $name);
			return $data = json_decode($connect -> read());
		}
	}
	
	/**
	 * Make a backup on Dropbox
	 * @param number  $idVps vps id
	 * @param boolean $protected use a password or not (0: no, 1: yes)
	 * @return unknown
	 */
	function vpsDropboxAdd($idVps, $protected=1) {
		$link = Db::link();
	
		$sql = 'SELECT vps_id, server.server_id, server_address,
		               server_key, vps_owner, server_port
		        FROM vps
		        JOIN server ON server.server_id=vps.server_id
		        WHERE vps_id= :id';
		$req = $link->prepare($sql);
		$req->bindValue(':id', $idVps, PDO::PARAM_INT);
		$req->execute();
		$do = $req->fetch(PDO::FETCH_OBJ);
		
		if(!empty($do->server_id)) {
			$para = array('token' => $this->dropbox, 'pass' => '');
			
			if($protected == 1 && $this->bkppass != '') {
				$para['pass'] = $this->bkppass;
			}
			
			$connect = new Socket($do->server_address, $do->server_port, $do->server_key);
			$connect -> write('backupDropbox', $do->vps_id, $para);
			return $data = json_decode($connect -> read());
		}
	}
	
	/**
	 * List backup scheduling
	 * @param number $idVps vps id
	 */
	function vpsSchedule($idVps) {
		$link = Db::link();
		$list = array();
		
		$sql = 'SELECT schedule_id, name, minute, hour, dayw, dayn, month
		        FROM schedule
		        WHERE schedule_vps= :id';
		$req = $link->prepare($sql);
		$req->bindValue(':id', $idVps, PDO::PARAM_INT);
		$req->execute();
		
		while($do = $req->fetch(PDO::FETCH_OBJ)) {
			$list[$do->schedule_id] = clone $do;
		}
		
		return $list;
	}
	
	/**
	 * Add/Update a scheduled backup
	 * @param number $vps vps id
	 * @param number $save new schedule or update
	 * @param string $name name
	 * @param number $minute minutes
	 * @param number $hour hours
	 * @param number $dayw days of the week
	 * @param number $dayn days of the month
	 * @param number $month months
	 */
	function vpsScheduleAdd($vps, $save, $name, $minute, $hour, $dayw, $dayn, $month) {
		$link = Db::link();
		
		if(empty($save)) {
			$sql = 'INSERT INTO schedule
			        (schedule_vps, name, minute, hour, dayw, dayn, month)
			        VALUES
			        (:vps, :name, :minute, :hour, :dayw, :dayn, :month)';
			$req = $link->prepare($sql);
			$req->bindValue(':vps', $vps, PDO::PARAM_INT);
			$req->bindValue(':name', $name, PDO::PARAM_STR);
			$req->bindValue(':minute', $minute, PDO::PARAM_INT);
			$req->bindValue(':hour', $hour, PDO::PARAM_INT);
			$req->bindValue(':dayw', $dayw, PDO::PARAM_INT);
			$req->bindValue(':dayn', $dayn, PDO::PARAM_INT);
			$req->bindValue(':month', $month, PDO::PARAM_INT);
			$req->execute();
		}
		else {
			$sql = 'UPDATE schedule
			        SET name= :name,
			            minute= :minute,
			            hour= :hour,
			            dayw= :dayw,
			            dayn= :dayn,
			            month= :month
			        WHERE schedule_id=:save AND schedule_vps=:vps';
			$req = $link->prepare($sql);
			$req->bindValue(':save', $save, PDO::PARAM_INT);
			$req->bindValue(':vps', $vps, PDO::PARAM_INT);
			$req->bindValue(':name', $name, PDO::PARAM_STR);
			$req->bindValue(':minute', $minute, PDO::PARAM_INT);
			$req->bindValue(':hour', $hour, PDO::PARAM_INT);
			$req->bindValue(':dayw', $dayw, PDO::PARAM_INT);
			$req->bindValue(':dayn', $dayn, PDO::PARAM_INT);
			$req->bindValue(':month', $month, PDO::PARAM_INT);
			$req->execute();
		}
	}
	
	/**
	 * Delete a VPS schedule backup
	 * @param number $save schedule id
	 */
	function vpsScheduleDelete($save) {
		$link = Db::link();
		
		$sql = 'DELETE FROM schedule
		        WHERE schedule_id=:save';
		$req = $link->prepare($sql);
		$req->bindValue(':save', $save, PDO::PARAM_INT);
		$req->execute();
	}
	
	/*** Backup password ***/
	
	/**
	 * Backup password is defined or not
	 */
	function bkppassStatus() {
		if($this->bkppass == '') {
			return 0;
		}
		else {
			return 1;
		}
	}
	
	/**
	 * Define a backup password
	 * @param string $bkppass backup password
	 */
	function bkppassDefine($bkppass) {
		$link = Db::link();
		
		$sql = 'UPDATE uservca
		        SET user_bkppass= :bkppass
		        WHERE user_id= :user_id';
		$req = $link->prepare($sql);
		$req->bindValue(':bkppass', $bkppass, PDO::PARAM_STR);
		$req->bindValue(':user_id', $this->getId(), PDO::PARAM_INT);
		$req->execute();
		
		$this->bkppass = $bkppass;
	}
	
	/**
	 * Get Dropbox URL for authentication
	 */
	function dropboxGetUrl() {
		$appInfo = new Dropbox\AppInfo(APP_KEY, APP_SECRET);
		$clientIdentifier = "VCA";
		
		$webAuth = new Dropbox\WebAuthNoRedirect($appInfo, $clientIdentifier);
		$authorizeUrl = $webAuth->start();
		
		return $authorizeUrl;
	}
	
	/**
	 * Get Dropbox Token for authentication
	 */
	function dropboxGetToken($authCode) {
		$link = Db::link();
		$appInfo = new Dropbox\AppInfo(APP_KEY, APP_SECRET);
		$clientIdentifier = "VCA";
		
		$webAuth = new Dropbox\WebAuthNoRedirect($appInfo, $clientIdentifier);
		
		list($accessToken, $dropboxUserId) = $webAuth->finish($authCode);
		
		if(!empty($accessToken)) {
			$sql = 'UPDATE uservca
			        SET user_dropbox= :dropbox
			        WHERE user_id= :user_id';
			$req = $link->prepare($sql);
			$req->bindValue(':dropbox', trim($accessToken), PDO::PARAM_STR);
			$req->bindValue(':user_id', $this->getId(), PDO::PARAM_INT);
			$req->execute();
		}
	}
	
	/**
	 * Return if Dropbox is configured or not
	 */
	function dropboxStatus() {
		if($this->dropbox == '') {
			return 0;
		}
		else {
			return 1;
		}
	}
}

?>
