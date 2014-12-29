<?php

class Sms {
	private $url;
	private $user;
	private $password;
	
	function __construct($user, $password) {
		$this->user = $user;
		$this->password=$password;
	}
	
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