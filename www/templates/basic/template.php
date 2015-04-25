<?php 

$vca_page_title = _('Template management');

$paquet = new Paquet();
$paquet -> add_action('serverTemplate', array($_GET['server']));
$paquet -> send_actions();

$serverTemplate = $paquet->getAnswer('serverTemplate');
$smarty->assign('server', $_GET['server']);

if(!empty($serverTemplate)) {
	$smarty->assign('serverTemplate', $paquet->getAnswer('serverTemplate'));
}
else {
	$smarty->assign('serverTemplate', '');
}

?>
