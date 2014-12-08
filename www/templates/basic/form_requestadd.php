<?php

include('../../config.php');
include('../../functions.php');
include('../../libs/Paquet.class.php');

if(!empty($_POST['subject']) && !empty($_POST['message'])) {
	$paquet = new Paquet();
	$paquet -> add_action('requestAdd', array($_POST['subject'],$_POST['message']));
	$paquet -> send_actions();
}

?>
