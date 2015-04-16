<?php

include('header.php');

if(!empty($_GET['server']) && !empty($_GET['vps']) && !empty($_GET['name'])) {
	$paquet = new Paquet();
	$paquet -> add_action('serverBackupDelete', array($_GET['server'],$_GET['vps'],$_GET['name']));
	$paquet -> send_actions();
}

?>