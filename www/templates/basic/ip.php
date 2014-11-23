<?php 

$paquet = new Paquet();
$paquet -> add_action('ipList');
$paquet -> send_actions();

$vca_page_title = _('IP management');
$ipList = $paquet->getAnswer('ipList');

if(!empty($ipList) && sizeof($ipList) > 0) {
	$smarty->assign('ipList', $paquet->getAnswer('ipList'));
}
else {
	$smarty->assign('ipList', null);
}

?>