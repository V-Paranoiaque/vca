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
			$text = _('Bad informations'); 
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
			$text = _('new account added');
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
	return ceil($size/1024).' '._('MB');
}

function numberSwapSize($size) {
	if($size == 0) {
		return _('unlimited');
	}
	else {
		return ceil($size/1024).' '._('MB');
	}
}

?>