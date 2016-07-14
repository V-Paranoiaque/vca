<?php

include('header.php');

if(!empty($_GET['current']) && !empty($_GET['new']) && !empty($_GET['confirm'])) {
	if($_GET['new'] == $_GET['confirm']) {
		$paquet = new Paquet();
		$paquet -> add_action('userPassword',
		                      array($_GET['current'],$_GET['new']));
		$paquet -> send_actions();
		
		$error = $paquet->getAnswer('userPassword');
	}
	else {
		$error = 12;
	}
}

if(empty($error)) {
	$error = 4;
}

echo errorToText($error);

?>
