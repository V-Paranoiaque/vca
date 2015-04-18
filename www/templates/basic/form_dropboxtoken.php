<?php

include('header.php');

if(!empty($_GET['authorization'])) {
	$paquet = new Paquet();
	$paquet -> add_action('dropboxGetToken', array($_GET['authorization']));
	$paquet -> send_actions();
}

?>