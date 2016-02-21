<?php

include('header.php');

if(!empty($_GET['key_size']) && !empty($_GET['validity'])) {
	if(empty($_GET['domainkey'])) {
		$_GET['domainkey'] = '';
	}
	
	$paquet = new Paquet();
	$paquet -> add_action('configurationDefine', 
	                      array($_GET['domainkey'],$_GET['key_size'],$_GET['validity']));
	$paquet -> send_actions();
}

?>
