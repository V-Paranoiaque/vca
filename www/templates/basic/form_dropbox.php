<?php

include('header.php');

if(!empty($_GET['vps'])) {
	$paquet = new Paquet();
	if(!empty($_GET['password'])) {
		$paquet -> add_action('vpsDropboxAdd', array($_GET['vps'], 1));
	}
	else {
		$paquet -> add_action('vpsDropboxAdd', array($_GET['vps'], 0));
	}
	$paquet -> send_actions();
}

?>