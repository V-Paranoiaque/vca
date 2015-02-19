<?php

function protect($var) {
	return addslashes(trim(htmlentities($var, ENT_NOQUOTES, 'UTF-8')));
}

function redirect($url='/', $time=0) {

	echo'<meta http-equiv="refresh" content="'.$time.'; url='.$url.'" />';

	if(empty($time)) {
		exit();
	}
}

function alerte($message) {
	echo '<script type=\'text/javascript\'>
	      alert(\''.addslashes($message).'\');
	      </SCRIPT>';
}

function nbf($n, $nb=2) {
	if(is_numeric($n)) {
		if($n == round($n)) {
			return number_format($n , 0, ',', ' ');
		}
		else {
			if($nb == 0) {
				$n = floor($n);
			}
			return number_format($n , $nb, ',', ' ');
		}
	}
}

function errorToText($erreur_no) {
	
	$text = '';
	
	switch($erreur_no) {
		case 1:
			$text = _('Bad information'); 
		break;
		
		case 2:
			$text = _('An user already exist with this username');
		break;
		
		case 3:
			$text = _('This user does not exist');
		break;
		
		case 4:
			$text = _('Error during account update');
		break;
		
		case 5:
			$text = _('Account updated');
		break;
		
		case 6:
			$text = _('You can\'t delete this account');
		break;
		
		case 7:
			$text = _('Account deleted');
		break;
		
		case 8:
			$text = _('New account created');
		break;
		
		case 9:
			$text = _('Invalid email');
		break;
		
		case 10:
			$text = _('Account username must be between 5 and 25 caracteres');
		break;
	}
	
	return $text;
}

/*** Format numbers ***/
function numberDiskSpace($size) {
	if($size < 500) {
		return $size.' '._('KB');
	}
	elseif($size < 1000000) {
		return (ceil($size/10.24)/100).' '._('MB');
	}
	else {
		return (ceil($size/10485.76)/100).' '._('GB');
	}
}

function numberRamSize($size) {
	if($size == 0) {
		return _('unlimited');
	}
	else {
		return $size.' '._('MB');
	}
}

function numberRamSizeCurrent($size) {
	$size = $size/1024;
	
	if($size < 1024) {
		return ceil($size).' '._('MB');
	}
	else {
		return (ceil($size/10.24)/100).' '._('GB');
	}
}

function numberSwapSize($size) {
	if($size == 0) {
		return _('unlimited');
	}
	else {
		return ceil($size/1024).' '._('MB');
	}
}

function numberOrUnlimited($num) {
	if($num == 0) {
		return _('unlimited');
	}
	else {
		return nbf($num);
	}
}

function checkHostname($name) {
	//Numeric
	if(is_numeric($name)) {
		return false;
	}
	//Size
	elseif(strlen($name) < 3 or strlen($name) > 64) {
		return false;
	}
	//Caracters
	elseif(!preg_match('/^[a-zA-Z0-9\-]+$/', $name)) {
		return false;
	}
	else {
		return true;
	}
}

function checkValideName($name) {
	//Numeric
	if(is_numeric($name)) {
		return false;
	}
	//Size
	elseif(strlen($name) < 3 or strlen($name) > 64) {
		return false;
	}
	//Caracters
	elseif(!preg_match('/^[a-zA-Z0-9\-_.]+$/', $name)) {
		return false;
	}
	else {
		return true;
	}
}

function tsdate($time, $type=1) {
	switch (LANGUAGE) {
		case 'fr_FR':
			switch($type) {
				case 1:
					return utf8_encode(strftime('%A %d %B', $time)).' à '.
					strftime('%H:%M', $time);
				break;
			}
		break;
		
		default:
			switch($type) {
				case 1:
					return strftime('%A %d %B', $time).' at '.
					strftime('%H:%M', $time);
				break;
			}
		break;
	}
}

?>