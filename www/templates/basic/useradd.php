<?php 

$vca_page_title = _('Add a new user');

if(!empty($_POST['name']) && !empty($_POST['mail'])) {
	$paquet = new Paquet();
	$paquet -> add_action('userAdd',
	                      array($_POST['name'],$_POST['mail']));
	$paquet -> send_actions();
}

$smarty->assign('userAdd', errorToText($paquet->getAnswer('userAdd')));

?>