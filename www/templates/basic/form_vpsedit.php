<?php

include('../../config.php');
include('../../functions.php');
include('../../libs/Paquet.class.php');

if(!empty($_GET['vps'])) {
	
	$var = array();
	
	if(!empty($_GET['name'])) {
		$var['name'] = $_GET['name'];
	}

	if(!empty($_GET['onboot'])) {
		$var['onboot'] = 1;
	}
	else {
		$var['onboot'] = 0;
	}

	if(!empty($_GET['ipv4'])) {
		$var['ipv4'] = $_GET['ipv4'];
	}

	if(isset($_GET['ram'])) {
		$var['ram'] = $_GET['ram'];
	}

	if(isset($_GET['swap'])) {
		$var['swap'] = $_GET['swap'];
	}

	if(!empty($_GET['diskspace'])) {
		$var['diskspace'] = $_GET['diskspace'];
	}

	if(!empty($_GET['diskinodes'])) {
		$var['diskinodes'] = $_GET['diskinodes'];
	}

	if(!empty($_GET['cpus'])) {
		$var['cpus'] = $_GET['cpus'];
	}
	
	if(!empty($_GET['cpulimit'])) {
		$var['cpulimit'] = $_GET['cpulimit'];
	}

	if(!empty($_GET['cpuunits'])) {
		$var['cpuunits'] = $_GET['cpuunits'];
	}
	
	if(!empty($_GET['backup_limit'])) {
		$var['backup_limit'] = $_GET['backup_limit'];
	}
	else {
		$var['backup_limit'] = 0;
	}
	
	if(!empty($_GET['owner'])) {
		$var['owner'] = $_GET['owner'];
	}
	
	$paquet = new Paquet();
	$paquet -> add_action('vpsUpdate',array($_GET['vps'], $var));
	$paquet -> send_actions();
}

?>

