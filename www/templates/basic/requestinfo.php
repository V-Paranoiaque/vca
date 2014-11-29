<?php

$paquet = new Paquet();
if(!empty($_GET['request'])) {
	$paquet -> add_action('requestInfo', $_GET['request']);
}
$paquet -> send_actions();

$vca_page_title = _('Requests');

if(empty($paquet->getAnswer('requestInfo'))) {
	redirect('/request');
}

$smarty->assign('requestInfo', $paquet->getAnswer('requestInfo'));

?>