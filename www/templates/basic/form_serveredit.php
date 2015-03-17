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

if(!empty($_GET['server']) && !empty($_GET['name']) && !empty($_GET['address']) && 
   !empty($_GET['port']) && !empty($_GET['key']) && !empty($_GET['description'])) {
	
	$para = array(
		'name'    => $_GET['name'],
		'address' => $_GET['address'],
		'port'    => $_GET['port'],
		'key'     => $_GET['key'],
		'description' => $_GET['description']
	);
	
	$paquet = new Paquet();
	$paquet -> add_action('serverUpdate', array($_GET['server'], $para));
	$paquet -> send_actions();
}

?>
