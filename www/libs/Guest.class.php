<?php 

class Guest {

	/**
	 * Connection
	 * @param user login
	 * @param user password
	 * @return user id
	 */
	static function connect($login, $password) {
		$sql = 'SELECT user_id, user_password
		        FROM uservca
		        WHERE user_name= :user_name';
		$link = Db::link();
		$req = $link->prepare($sql);
		$req->bindValue(':user_name', $login, PDO::PARAM_STR);
		$req->execute();
		$do = $req->fetch(PDO::FETCH_OBJ);

		//If user does not exist or the passwords missmatch
		if(!empty($do) && hash('sha512', $do->user_id.$password) == $do->user_password) {
			return $do->user_id;
		}
		else {
			return 0;
		}
	}
	
	/**
	 * Generate a new token
	 * @param user id
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
	 * @param user token
	 * @return user (NULL, User, Admin, SuperAdmin)
	 */
	static function loadUser($token) {
		$sql = 'SELECT user_id, user_rank, user_language, user_bkppass
		        FROM uservca
		        WHERE user_token= :user_token';
		$link = Db::link();
		$req = $link->prepare($sql);
		$req->bindValue(':user_token', $token, PDO::PARAM_STR);
		$req->execute();
		$do = $req->fetch(PDO::FETCH_OBJ);
	
		if(empty($do)) {
			$user = null;
		}
		elseif($do->user_rank == 0) {
			$user = new User($do->user_id);
			$user->setRank($do->user_rank);
			$user->setLanguage($do->user_language);
			$user->setBkppass($do->user_bkppass);
		}
		elseif($do->user_rank == 1) {
			$user = new Admin($do->user_id);
			$user->setRank($do->user_rank);
			$user->setLanguage($do->user_language);
			$user->setBkppass($do->user_bkppass);
		}
		else {
			exit();
		}
		
		return $user;
	}
}

?>
