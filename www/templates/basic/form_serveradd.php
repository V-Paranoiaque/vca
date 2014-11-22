<?php

include('../../config.php');
include('../../functions.php');
include('../../libs/Paquet.class.php');

if(!empty($_GET['name']) && !empty($_GET['address']) && 
   !empty($_GET['key'])  && !empty($_GET['description'])) {
	
	$paquet = new Paquet();
	$paquet -> add_action('serverAdd', 
	                      array($_GET['name'], $_GET['address'], $_GET['key'], $_GET['description']));
	$paquet -> send_actions();
}

?>