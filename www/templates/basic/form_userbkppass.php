<?php

include('header.php');

if(!empty($_GET['password'])) {
	$paquet = new Paquet();
	$paquet -> add_action('bkppassDefine', $_GET['password']);
	$paquet -> send_actions();
}

?>