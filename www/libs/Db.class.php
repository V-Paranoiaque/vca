<?php 

/**
 * Database class
 * @author V_paranoiaque
 */
class Db {

	protected static $instance; /*!< Access to the DB */
	protected $db; /*!< Store the DB connection */
	
	/**
	 * Make the connexion to the DB with PDO
	 */
	function __construct() {
		if(DB_TYPE == 'MYSQL') {
			$this->db = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASS, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
		}
		elseif (DB_TYPE == 'PGSQL') {
			$this->db = new PDO('pgsql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASS);
			$this->db ->exec("SET CHARACTER SET utf8");
		}
		else {
			exit();
		}
	}
	
	/**
	 * Return the link to the DB
	 */
	static function link() {
		//If link to database does not exist
		if(!self::$instance) {
			self::$instance = new Db();
		}
		
		return self::$instance->db;
	}
}

?>
