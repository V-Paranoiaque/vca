<?php 

$vca_page_title = _('Panel users');

$paquet = new Paquet();
$paquet -> add_action('userProfile');
$paquet -> add_action('languageList');
$paquet -> add_action('bkppassStatus');
$paquet -> add_action('dropboxPossible');
$paquet -> add_action('dropboxStatus');
$paquet -> add_action('configuration');
$paquet -> send_actions();

$smarty->assign('userInfo',     $paquet->getAnswer('userProfile'));
$smarty->assign('languageList', $paquet->getAnswer('languageList'));
$smarty->assign('language',     $paquet -> getLanguage());
$smarty->assign('bkppassStatus', $paquet->getAnswer('bkppassStatus'));
$smarty->assign('dropboxPossible', $paquet->getAnswer('dropboxPossible'));
$smarty->assign('dropboxStatus', $paquet->getAnswer('dropboxStatus'));
$smarty->assign('configuration', $paquet->getAnswer('configuration'));

?>
