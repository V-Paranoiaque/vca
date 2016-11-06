<?php 

/**
 * Guest class
 * @author V_paranoiaque
 */
class Guest {

	/**
	 * Connection
	 * @param string $login user login
	 * @param string $password user password
	 * @param string $token strong authentication token
	 * @return user id
	 */
	static function connect($login, $password, $token='') {
		$link = Db::link();
		$conf = self::loadConfiguration();
				
		$sql = 'SELECT user_id, user_password, user_strongauth, user_tokenid, 
		               user_pin
		        FROM uservca
		        WHERE user_name= :user_name';
		$req = $link->prepare($sql);
		$req->bindValue(':user_name', $login, PDO::PARAM_STR);
		$req->execute();
		$do = $req->fetch(PDO::FETCH_OBJ);

		//If user does not exist or the passwords missmatch
		if(!empty($do) && hash('sha512', $do->user_id.$password) == $do->user_password) {
			//Don't use token
			if(empty($do->user_strongauth)) {
				return $do->user_id;
			}
			else {
				//Token conf
				$OOTFY = new OOTFY($conf['domain_key'], $do->user_tokenid);
				$OOTFY->set_keysize($conf['key_size']);
				$OOTFY->set_period($conf['key_period']);
				
				if($OOTFY->check_token($token, $do->user_pin) == 1) {
					return $do->user_id;
				}
				else {
					return 0;
				}
			}
		}
		else {
			return 0;
		}
	}
	
	/**
	 * Generate a new token
	 * @param number $id user id
	 * @return new token
	 */
	static function newToken($id) {
		$newToken = hash('sha512', $id.mt_rand());
	
		//Update token
		$sql = 'UPDATE uservca
		        SET user_token= :user_token
		        WHERE user_id= :user_id';
		$link = Db::link();
		$req = $link->prepare($sql);
		$req->bindValue(':user_token', $newToken, PDO::PARAM_STR);
		$req->bindValue(':user_id', $id, PDO::PARAM_INT);
		$req->execute();
		
		return $newToken;
	}
	
	/**
	 * Load user information
	 * @param string $token user token
	 * @return user (NULL, User, Admin, SuperAdmin)
	 */
	static function loadUser($token) {
		$sql = 'SELECT user_id, user_rank, user_language, user_bkppass,
		               user_dropbox
		        FROM uservca
		        WHERE user_token= :user_token';
		$link = Db::link();
		$req = $link->prepare($sql);
		$req->bindValue(':user_token', $token, PDO::PARAM_STR);
		$req->execute();
		$do = $req->fetch(PDO::FETCH_OBJ);
	
		if(empty($do)) {
			return null;
		}
		elseif($do->user_rank == 0) {
			$user = new User($do->user_id);
		}
		elseif($do->user_rank == 1) {
			$user = new Admin($do->user_id);
		}
		else {
			exit();
		}
		
		$user->setRank($do->user_rank);
		$user->setLanguage($do->user_language);
		$user->setBkppass($do->user_bkppass);
		$user->setDropbox($do->user_dropbox);
		
		return $user;
	}
	
	/**
	 * Load VCA configuration from the DB
	 */
	static function loadConfiguration() {
		$link = Db::link();
		$conf = array();
		$sql = 'SELECT conf_index, conf_value 
		        FROM configuration';
		$req = $link->prepare($sql);
		$req->execute();
		while($do = $req->fetch(PDO::FETCH_OBJ)) {
			$conf[$do->conf_index] = $do->conf_value;
		}
		
		return $conf;
	}
}

?>
