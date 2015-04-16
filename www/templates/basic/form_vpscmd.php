<?php

include('header.php');

if(!empty($_GET['vps']) && !empty($_GET['cmd'])) {
	$paquet = new Paquet();
	$paquet -> add_action('vpsCmd', array($_GET['vps'], $_GET['cmd']));
	$paquet -> send_actions();
	
	echo nl2br($paquet -> getAnswer('vpsCmd'));
}

?>
