<?php 

$vca_page_title = _('Vps Virtual Control Admin');

$paquet = new Paquet();
$paquet -> add_action('vpsList');
$paquet -> send_actions();

$vpsList = $paquet->getAnswer('vpsList');
$smarty->assign('vps', $vpsList->$_GET['vps']);

?>