<?php

include('header.php');

if(!empty($_GET['vps'])) {
	$paquet = new Paquet();
	$paquet -> add_action('vpsStop',	array($_GET['vps']));
	$paquet -> send_actions();
}

?>