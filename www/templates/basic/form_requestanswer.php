<?php

include('header.php');

if(!empty($_POST['request']) && !empty($_POST['message'])) {
	$paquet = new Paquet();
	$paquet -> add_action('requestAnswer', array($_POST['request'],$_POST['message']));
	$paquet -> send_actions();
}

?>
