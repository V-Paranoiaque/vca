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
	 * @param string $domainkey can be unique or the same for every users
	 * @param string $userid    must be specific to only one user
	 **/
	function __construct($domainkey='', $userid='')
	{
		$this->domainkey = $domainkey;
		$this->userid    = $userid;
	}
	
	/**
	 * Set new key size
	 * @param number $keysize_new new key size
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
	 * @param number $period_new period between two key generation
	 *                           must be between 15 and 90
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
	
	/**
	 * Calcul token
	 * @param string $pin user pin
	 * @return string token
	 */
	private function calcul_token($pin) {
		$time = $_SERVER['REQUEST_TIME'] - $_SERVER['REQUEST_TIME']%$this->period;
		
		$tmp = hash('sha512', $pin.$time);
		$result = hash('sha512', $this->userid.$tmp.$this->domainkey);
		
		return substr($result, 0, $this->keysize);
	}
	
	/**
	 * Check token authentification
	 * Please log bad try
	 * @param string $token the token
	 * @param number $pin   user's PIN
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
