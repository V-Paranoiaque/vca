<?php

include('../../config.php');
include('../../functions.php');
include('../../libs/Paquet.class.php');

if(!empty($_GET['server']) && !empty($_GET['old']) && !empty($_GET['name'])) {
	$paquet = new Paquet();
	$paquet -> add_action('serverTemplateRename', array($_GET['server'], $_GET['old'], $_GET['name']));
	$paquet -> send_actions();
}

?>
