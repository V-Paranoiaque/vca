<?php

include('../../config.php');
include('../../functions.php');
include('../../libs/Paquet.class.php');

if(!empty($_GET['name']) && !empty($_GET['mail'])) {
	if(empty($_GET['user'])) {
		$_GET['user'] = 0;
	}
	
	$paquet = new Paquet();
	$paquet -> add_action('userUpdate',
			array($_GET['user'],$_GET['name'],$_GET['mail']));
	$paquet -> send_actions();
}


?>
