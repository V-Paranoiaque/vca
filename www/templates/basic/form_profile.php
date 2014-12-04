<?php

include('../../config.php');
include('../../functions.php');
include('../../libs/Paquet.class.php');

if(!empty($_GET['name']) && !empty($_GET['mail'])) {
	if(empty($_GET['user'])) {
		$_GET['user'] = 0;
	}
	
	if(empty($_GET['language'])) {
		$_GET['language'] = '';
	}
	
	$paquet = new Paquet();
	$paquet -> add_action('userUpdate',
			array($_GET['user'],$_GET['name'],$_GET['mail'],$_GET['language']));
	$paquet -> send_actions();
}


?>
