<?php

include('header.php');

if(!empty($_GET['name']) && !empty($_GET['mail']) && !empty($_GET['password'])) {
	$paquet = new Paquet();
	$paquet -> add_action('userAdd',
	                      array($_GET['name'],$_GET['mail'], $_GET['password']));
	$paquet -> send_actions();
}

?>
