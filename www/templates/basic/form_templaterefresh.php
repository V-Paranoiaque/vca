<?php

include('header.php');

if(!empty($_GET['server'])) {
	$paquet = new Paquet();
	$paquet -> add_action('serverTemplateRefresh', array($_GET['server']));
	$paquet -> send_actions();
}

?>
