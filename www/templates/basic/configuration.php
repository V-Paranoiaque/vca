<?php 

$vca_page_title = _('Configuration');

$paquet = new Paquet();
$paquet -> add_action('configuration');
$paquet -> send_actions();

$smarty->assign('configuration', $paquet->getAnswer('configuration'));

?>