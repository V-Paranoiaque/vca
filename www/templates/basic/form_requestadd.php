<?php

include('header.php');

if(!empty($_POST['subject']) && !empty($_POST['message'])) {
	$paquet = new Paquet();
	$paquet -> add_action('requestAdd', array($_POST['subject'],$_POST['message']));
	$paquet -> send_actions();
}

?>
