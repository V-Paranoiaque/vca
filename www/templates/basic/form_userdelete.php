<?php

include('../../config.php');
include('../../functions.php');
include('../../libs/Paquet.class.php');

if(!empty($_GET['user'])) {
	$paquet = new Paquet();
	$paquet -> add_action('userDelete',	array($_GET['user']));
	$paquet -> send_actions();
}

?>