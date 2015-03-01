<?php

include('../../config.php');
include('../../functions.php');
include('../../libs/Paquet.class.php');

if(!empty($_GET['saveId']) && !empty($_GET['vps'])) {
	$paquet = new Paquet();
	$paquet -> add_action('vpsScheduleDelete', array($_GET['vps'], $_GET['saveId']));
	$paquet -> send_actions();
}

?>