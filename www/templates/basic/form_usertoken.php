<?php

include('header.php');

if(empty($_GET['activated'])) {
	$_GET['activated'] = 0;
}
if(empty($_GET['pin'])) {
	$_GET['pin'] = '';
}

$paquet = new Paquet();
$paquet -> add_action('userToken', array($_GET['pin'], $_GET['activated']));
$paquet -> send_actions();

?>
