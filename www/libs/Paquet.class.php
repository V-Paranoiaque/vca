<?php

class Paquet {
	private $token   = '';
	private $actions = array();
	private $user=null;
	private $answer=null;
	private $rank=0;
	
	function __construct() {
		if(empty($_COOKIE['token'])) {
			$_COOKIE['token'] = '';
			$_COOKIE['temps'] = 0;
		}
		
		if(!empty($_COOKIE['token'])) {
			$this->token = $_COOKIE['token'];
		}
	}
	
	function add_action($action, $para='') {
		if($para == '') {
			$para=array('');
		}
		$this->actions[$action] = $para;
	}
	
	function send_actions() {
		$var = array('token'     => $this->token,
		             'actions'   => $this->actions);
		
		$postvar = http_build_query($var);
		$opts = array('http' =>
		    array(
		        'method'  => 'POST',
		        'header'  => 'Content-type: application/x-www-form-urlencoded',
		        'content' => $postvar
		    )
		);
		
		$context = stream_context_create($opts);
		$data    = json_decode(file_get_contents('http://'.$_SERVER['HTTP_HOST'].'/api.php', false, $context));
		
		if(!empty($data->user)) {
			$this->user = $data->user;
			
			$temps = time()+TEMPS_CO*86400;
			
			if(!empty($this->user->token) && 
			   (empty($_COOKIE['token']) or 
			   $_COOKIE['token'] != $this->user->token)) {
			
				if($_COOKIE['temps'] > $temps) {
					$temps = $_COOKIE['temps'];
				}
	
				setcookie('token', $this->user->token, $temps, '/',
	    				  $_SERVER['HTTP_HOST']);
				setcookie('temps', $temps, $temps, '/',
	    				  $_SERVER['HTTP_HOST']);
			}
			else {			
				if($_COOKIE['temps'] < $temps) {
					setcookie('token', $_COOKIE['token'], $temps, '/',
		    				  $_SERVER['HTTP_HOST']);
					setcookie('temps', $temps, $temps, '/',
		    				  $_SERVER['HTTP_HOST']);
				}
			}
			
			if(!empty($this->user->id)) {
				setcookie('my_id', $this->user->id, $temps, '/',
				          $_SERVER['HTTP_HOST']);
			}
			
			if(!empty($data->answer)) {
				$this->answer = $data->answer;
			}
			
			if(!empty($this->user->language) && !defined('LANGUAGE')) {
				define('LANGUAGE', $this->user->language);
				putenv('LC_ALL='.LANGUAGE);
				setlocale(LC_ALL, LANGUAGE);
				bindtextdomain('messages', './lang');
				textdomain('messages');
			}
		}
	}
	
	function getAnswer($action='', $num=0) {
		if(!empty($this->answer) && !empty($this->answer->$action)) {
			if(!empty($num)) {
				return $this->answer->$action->$num;
			}
			else {
				return $this->answer->$action;
			}
		}
		else {
			return '';
		}
	}
	
	function userInfo($arg) {
			
		if($this->user == null) {
			return null;
		}
	
		return $this->user->$arg;
	}
}

?>