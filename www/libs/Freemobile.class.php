<?php 

class Freemobile extends Sms {
	function __construct($user, $password) {
		$this->url = 'https://smsapi.free-mobile.fr/sendmsg';
		parent::__construct($user, $password);
	}
	
	function send($message, $destination='') {
		if(!parent::send($message)) {
			return false;
		}
		
		$var = array(
			'user' => $this->user,
			'pass' => $this->password,
			'msg'  => $message
		);
		
		$getvar = http_build_query($var);
		$opts = array('http' =>
					array(
						'method'  => 'GET',
						'header'  => 'Content-type: application/x-www-form-urlencoded',
						'content' => $getvar
					)
		);
		
		$context = stream_context_create($opts);
		$data    = file_get_contents($this->url, false, $context);
		
		return $data;
	}
}

?>