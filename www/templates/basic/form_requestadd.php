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

if(!empty($_POST['subject']) && !empty($_POST['message'])) {
	$paquet = new Paquet();
	$paquet -> add_action('requestAdd', array($_POST['subject'],$_POST['message']));
	$paquet -> send_actions();
}

?>
