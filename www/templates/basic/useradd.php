<?php 

$vca_page_title = _('Add an new User Virtual Control Admin');

if(!empty($_POST['name']) && !empty($_POST['mail'])) {
	$paquet = new Paquet();
	$paquet -> add_action('setUserNew',
	                      array($_POST['name'],$_POST['mail']));
	$paquet -> send_actions();
}

$smarty->assign('userAdd', errorToText($paquet->getAnswer('setUserNew')));

?>