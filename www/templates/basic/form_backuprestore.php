<?php

include('header.php');

if(!empty($_GET['vps']) && !empty($_GET['name'])) {
	$paquet = new Paquet();
	$paquet -> add_action('vpsBackupRestore', array($_GET['vps'],$_GET['name']));
	$paquet -> send_actions();
}

?>