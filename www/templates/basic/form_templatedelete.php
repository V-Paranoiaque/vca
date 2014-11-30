<?php

include('../../config.php');
include('../../functions.php');
include('../../libs/Paquet.class.php');

if(!empty($_GET['server']) && !empty($_GET['name'])) {
	$paquet = new Paquet();
	$paquet -> add_action('serverTemplateDelete', array($_GET['server'], $_GET['name']));
	$paquet -> send_actions();
}

?>
