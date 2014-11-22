<?php

include('../../config.php');
include('../../functions.php');
include('../../libs/Paquet.class.php');

if(!empty($_GET['vps']) && !empty($_GET['os'])) {
	$paquet = new Paquet();
	$paquet -> add_action('vpsReinstall', array($_GET['vps'], $_GET['os']));
	$paquet -> send_actions();
}

?>