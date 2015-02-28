<?php 

$vca_page_title = _('Virtual server');

$paquet = new Paquet();
$paquet -> add_action('vpsList');
$paquet -> add_action('vpsBackup', array($_GET['vps']));
$paquet -> send_actions();

$vpsList = $paquet->getAnswer('vpsList');
$smarty->assign('vps', $vpsList->$_GET['vps']);

if(!empty($paquet->getAnswer('vpsBackup'))) {
	$smarty->assign('nbCurrent', sizeof((array) $paquet->getAnswer('vpsBackup')));
}
else {
	$smarty->assign('nbCurrent', 0);
}

?>