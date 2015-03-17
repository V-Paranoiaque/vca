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

if(!empty($_GET['server']) && !empty($_GET['template']) && !empty($_GET['template'])) {
	$paquet = new Paquet();
	$paquet -> add_action('serverTemplateAdd', array($_GET['server'], $_GET['template']));
	$paquet -> send_actions();
}

?>
