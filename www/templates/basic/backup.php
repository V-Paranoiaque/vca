<?php 

$vca_page_title = _('Backup list');

$paquet = new Paquet();
$paquet -> add_action('vpsList', array($_GET['server']));
$paquet -> add_action('serverBackup', array($_GET['server']));
$paquet -> send_actions();

$smarty->assign('server', $_GET['server']);
$smarty->assign('serverBackup', $paquet->getAnswer('serverBackup'));
$smarty->assign('vpsList', $paquet->getAnswer('vpsList'));

?>