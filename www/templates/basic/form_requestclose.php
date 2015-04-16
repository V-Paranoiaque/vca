<?php

include('header.php');

if(!empty($_GET['request'])) {
	$paquet = new Paquet();
	$paquet -> add_action('requestClose', $_GET['request']);
	$paquet -> send_actions();
}

?>