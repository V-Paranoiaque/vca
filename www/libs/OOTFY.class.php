<?php

/**
 * Class OOTFY
 * Check tokens and define configuration
 */
class OOTFY 
{
	private $userid='';
	private $domainkey='';
	private $keysize=4;
	private $period=30;
	
	/**
	 * Define user parameters
	 * There is no control on domainkey and userid
	 * @param domainkey string can be unique or the same for every users
	 * @param userid    string must be specific to only one user
	 **/
	function __construct($domainkey='', $userid='')
	{
		$this->domainkey = $domainkey;
		$this->userid    = $userid;
	}
	
	/**
	 * Set new key size
	 * @param int $keysize_new new key size
	 */
	function set_keysize($keysize_new=4)
	{
		if($keysize_new != 8  && $keysize_new != 16 &&
		   $keysize_new != 32 && $keysize_new != 64)
		{
			$this->keysize = $keysize_new;
			return true;
		}
		else
		{
			return false;
		}
	}
	
	/**
	 * Define period between two keys generation, in seconds
	 * @param period_new int period between two key generation
	 *                       must be between 15 and 90
	 **/
	function set_period($period_new=30)
	{
		if($period_new != 15 && $period_new != 30 &&
		   $period_new != 60 && $period_new != 90)
		{
			$this->period_new = $period_new;
			return true;
		}
		else
		{
			return false;
		}
	}
	
	private function calcul_token($pin) {
		$time = $_SERVER['REQUEST_TIME'] - $_SERVER['REQUEST_TIME']%$this->period;
		
		$tmp = hash('sha512', $pin.$time);
		$result = hash('sha512', $this->userid.$tmp.$this->domainkey);
		
		return substr($result, 0, $this->keysize);
	}
	
	/**
	 * Check token authentification
	 * Please log bad try
	 * @param token string the token
	 * @param pin   int    user's PIN
	 * @return 1 : Bad  token
	 *         2 : Good token
	 **/
	function check_token($token='', $pin='')
	{
		$server_token = $this->calcul_token($pin);
		if($token == $server_token)
		{
			return 1;
		}
		else
		{
			return 2;
		}
	}
}

?>
