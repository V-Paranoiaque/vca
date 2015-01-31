<?php

include('../../config.php');
include('../../functions.php');
include('../../libs/Paquet.class.php');

if(!empty($_GET['vps']) && !empty($_GET['name']) && 
   !empty($_GET['dayw']) && !empty($_GET['dayn'])&& 
   !empty($_GET['month'])) {
	
	if(empty($_GET['save'])) { $_GET['save'] = 0; }
  if(empty($_GET['minute'])) { $_GET['minute'] = 0; }
  if(empty($_GET['hour'])) { $_GET['hour'] = 0; }
	
	$paquet = new Paquet();
	$paquet -> add_action('vpsScheduleAdd', 
	                      array($_GET['vps'], $_GET['save'], $_GET['name'], 
	                            $_GET['minute'], $_GET['hour'], $_GET['dayw'],
	                            $_GET['dayn'], $_GET['month']));
	$paquet -> send_actions();
}

?>
