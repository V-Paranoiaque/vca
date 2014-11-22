<?php 

/**
 * Database class
 * @author mighty
 */
class Db {

	protected static $instance;
	protected $db;

	function __construct() {
		$this->db = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASS, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
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
