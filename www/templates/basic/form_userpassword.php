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

if(!empty($_GET['current']) && !empty($_GET['new']) && !empty($_GET['confirm'])) {
	if($_GET['new'] == $_GET['confirm']) {
		$paquet = new Paquet();
		$paquet -> add_action('userPassword',
		                      array($_GET['current'],$_GET['new']));
		$paquet -> send_actions();
		
		if(!empty($paquet->getAnswer('userPassword'))) {
			$error = $paquet->getAnswer('userPassword');
		}
	}
	else {
		$error = 12;
	}
}

if(empty($error)) {
	$error = 4;
}

echo errorToText($error);

?>
