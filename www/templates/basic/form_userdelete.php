<?php 

if(!empty($_GET['user'])) {
	$paquet = new Paquet();
	$paquet -> add_action('setUserDelete',	array($_GET['user']));
	$paquet -> send_actions();
}

?>