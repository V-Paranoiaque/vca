<?php

include('header.php');

if(!empty($_GET['server'])) {
	
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

	if(!empty($_GET['ram'])) {
		$var['ram'] = $_GET['ram'];
	}

	if(!empty($_GET['swap'])) {
		$var['swap'] = $_GET['swap'];
	}

	if(!empty($_GET['diskspace'])) {
		$var['diskspace'] = $_GET['diskspace'];
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
	
	if(!empty($_GET['os'])) {
		$var['os'] = $_GET['os'];
	}
	
	$paquet = new Paquet();
	$paquet -> add_action('vpsAdd',array($_GET['server'], $var));
	$paquet -> send_actions();
}

?>

