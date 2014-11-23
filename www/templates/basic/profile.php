<?php 

$vca_page_title = _('Panel users');

$paquet = new Paquet();
$paquet -> add_action('userProfile');
$paquet -> send_actions();

$smarty->assign('userInfo', $paquet->getAnswer('userProfile'));

?>
