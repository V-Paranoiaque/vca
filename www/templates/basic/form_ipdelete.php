<?php

include('../../config.php');
include('../../functions.php');
include('../../libs/Paquet.class.php');

if(!empty($_GET['ip'])) {
	$paquet = new Paquet();
	$paquet -> add_action('ipDelete',	array($_GET['ip']));
	$paquet -> send_actions();
}

?>