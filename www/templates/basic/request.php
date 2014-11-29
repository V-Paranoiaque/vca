<?php

$paquet = new Paquet();
$paquet -> add_action('requestList');
$paquet -> send_actions();

$vca_page_title = _('Requests');

if(sizeof($paquet->getAnswer('requestList')) > 0) {
	$smarty->assign('requestList', $paquet->getAnswer('requestList'));
}
else {
	$smarty->assign('requestList', '');
}

?>