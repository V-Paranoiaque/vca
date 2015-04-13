<?php 

$vca_page_title = _('Antivirus scan');

$paquet = new Paquet();
$paquet -> add_action('serverScan', array($_GET['server']));
$paquet -> send_actions();

$serverScan = $paquet->getAnswer('serverScan');

if(empty($serverScan) or sizeof($serverScan) == 0) {
	$smarty->assign('serverScan', '');
}
else {
	$smarty->assign('serverScan', $serverScan);
}

?>