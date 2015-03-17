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

if(!empty($_GET['id']) && !empty($_GET['name']) && !empty($_GET['ipv4'])) {
	$paquet = new Paquet();
	$paquet -> add_action('vpsClone', array($_GET['id'], $_GET['ipv4'], $_GET['name']));
	$paquet -> send_actions();
}

?>
