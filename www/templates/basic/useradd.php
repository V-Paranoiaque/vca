<?php 

$vca_page_title = _('Add an new User Virtual Control Admin');

if(!empty($_POST['name']) && !empty($_POST['mail'])) {
	$paquet = new Paquet();
	$paquet -> add_action('setUserNew',
	                      array($_POST['name'],$_POST['mail']));
	$paquet -> send_actions();
}

$smarty->assign('Name', _('Name'));
$smarty->assign('Mail', _('Mail'));
$smarty->assign('Save', _('Save'));
$smarty->assign('Close', _('Close'));
$smarty->assign('Addanewuser', _('Add a new user'));

$smarty->assign('userAdd', errorToText($paquet->getAnswer('setUserNew')));

?>