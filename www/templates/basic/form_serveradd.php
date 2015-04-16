<?php

include('header.php');

if(!empty($_GET['name']) && !empty($_GET['address']) && 
   !empty($_GET['port']) && !empty($_GET['key'])) {
	
	if(empty($_GET['description'])) {
		$_GET['description'] = '';
	}
	
	$paquet = new Paquet();
	$paquet -> add_action('serverAdd', 
	                      array($_GET['name'], $_GET['address'], $_GET['port'], $_GET['key'], $_GET['description']));
	$paquet -> send_actions();
}

?>