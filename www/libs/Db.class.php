<?php 

/**
 * Database class
 * @author mighty
 */
class Db {

	protected static $instance;
	protected $db;

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
	
	static function link() {
		//If link to database does not exist
		if(!self::$instance) {
			self::$instance = new Db();
		}
		
		return self::$instance->db;
	}
}

?>
