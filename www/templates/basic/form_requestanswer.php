<?php

include('../../config.php');
include('../../functions.php');
include('../../libs/Paquet.class.php');

if(!empty($_POST['request']) && !empty($_POST['message'])) {
	$paquet = new Paquet();
	$paquet -> add_action('requestAnswer', array($_POST['request'],$_POST['message']));
	$paquet -> send_actions();
}

?>
