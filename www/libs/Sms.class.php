<?php

/**
 * SMS class
 * @author V_paranoiaque
 */
class Sms {
	private $url;
	private $user;
	private $password;
	
	/**
	 * Constructor
	 * @param string $user API username
	 * @param string $password API password
	 */
	function __construct($user, $password) {
		$this->user = $user;
		$this->password=$password;
	}
	
	/**
	 * Send a SMS
	 * @param string $message message
	 * @param number $destination
	 */
	function send($message, $destination='') {
		if(empty($this->url) or empty($this->user) or 
		   empty($this->password) or empty($message)) {
			return false;
		}
		else {
			return true;
		}
	}
}

?>