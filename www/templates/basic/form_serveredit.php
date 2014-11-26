<?php

include('../../config.php');
include('../../functions.php');
include('../../libs/Paquet.class.php');

if(!empty($_GET['server']) && !empty($_GET['name']) && !empty($_GET['address']) && 
   !empty($_GET['key'])  && !empty($_GET['description'])) {
	
	$para = array(
		'name'    => $_GET['name'],
		'address' => $_GET['address'],
		'key'     => $_GET['key'],
		'description' => $_GET['description']
	);
	
	$paquet = new Paquet();
	$paquet -> add_action('serverUpdate', array($_GET['server'], $para));
	$paquet -> send_actions();
}

?>
