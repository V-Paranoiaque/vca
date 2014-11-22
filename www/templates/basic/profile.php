<?php 

$vca_page_title = _('Users Virtual Control Admin');

$paquet = new Paquet();
$paquet -> add_action('userProfile');
$paquet -> send_actions();

$smarty->assign('userInfo', $paquet->getAnswer('userProfile'));

?>
