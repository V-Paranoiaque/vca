<?php 

$vca_page_title = _('Virtual server');

$paquet = new Paquet();
$paquet -> add_action('vpsList');
$paquet -> add_action('vpsBackup', array($_GET['vps']));
$paquet -> send_actions();

$vpsList = $paquet->getAnswer('vpsList');
$vpsBackup = $paquet->getAnswer('vpsBackup');
$smarty->assign('vps', $vpsList->$_GET['vps']);

if(!empty($vpsBackup)) {
	$smarty->assign('nbCurrent', sizeof((array) $vpsBackup));
}
else {
	$smarty->assign('nbCurrent', 0);
}

?>