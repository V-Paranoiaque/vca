<?php 

$vca_page_title = _('Template management');

$paquet = new Paquet();
$paquet -> add_action('serverTemplate', array($_GET['server']));
$paquet -> send_actions();

$smarty->assign('server', $_GET['server']);
$smarty->assign('serverTemplate', $paquet->getAnswer('serverTemplate'));

?>
