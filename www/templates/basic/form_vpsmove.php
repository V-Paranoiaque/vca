<?php

include('header.php');

if(!empty($_GET['server']) && !empty($_GET['vps']) && !empty($_GET['dest'])) {
	$paquet = new Paquet();
	$paquet -> add_action('vpsMove', 
	                      array($_GET['server'], $_GET['vps'], $_GET['dest']));
	$paquet -> send_actions();
}

?>