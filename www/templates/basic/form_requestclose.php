<?php

include('../../config.php');
include('../../functions.php');
include('../../libs/Paquet.class.php');

if(!empty($_GET['request'])) {
	$paquet = new Paquet();
	$paquet -> add_action('requestClose', $_GET['request']);
	$paquet -> send_actions();
}

?>