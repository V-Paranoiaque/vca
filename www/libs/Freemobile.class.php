<?php 

/**
 * Freemobile class
 * @author V_paranoiaque
 * Used to send SMS by the French operator Free
 */
class Freemobile extends Sms {
	/**
	 * Constructor
	 * @param string $user API username
	 * @param string $password API password
	 */
	function __construct($user, $password) {
		$this->url = 'https://smsapi.free-mobile.fr/sendmsg';
		parent::__construct($user, $password);
	}
	
	/**
	 * Send a SMS
	 * @param string $message message
	 * @param number $destination not used
	 */
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