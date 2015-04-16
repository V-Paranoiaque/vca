<?php

include('header.php');

if(!empty($_GET['server']) && !empty($_GET['template']) && !empty($_GET['template'])) {
	$paquet = new Paquet();
	$paquet -> add_action('serverTemplateAdd', array($_GET['server'], $_GET['template']));
	$paquet -> send_actions();
}

?>
