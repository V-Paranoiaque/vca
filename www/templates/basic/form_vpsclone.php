<?php

include('header.php');

if(!empty($_GET['id']) && !empty($_GET['name']) && !empty($_GET['ipv4'])) {
	$paquet = new Paquet();
	$paquet -> add_action('vpsClone', array($_GET['id'], $_GET['ipv4'], $_GET['name']));
	$paquet -> send_actions();
}

?>
