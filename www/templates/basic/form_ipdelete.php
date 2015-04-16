<?php

include('header.php');

if(!empty($_GET['ip'])) {
	$paquet = new Paquet();
	$paquet -> add_action('ipDelete',	array($_GET['ip']));
	$paquet -> send_actions();
}

?>