<?php

include('../../config.php');
include('../../functions.php');
include('../../libs/Paquet.class.php');

include('../../libs/Db.class.php');
include('../../libs/Socket.class.php');
include('../../libs/Vps.class.php');
include('../../libs/Server.class.php');
include('../../libs/Guest.class.php');
include('../../libs/User.class.php');
include('../../libs/Admin.class.php');

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
