<?php 

$vca_page_title = _('VPS Virtual Control Admin');

$paquet = new Paquet();

if(!empty($_GET['server'])) {
	$paquet -> add_action('vpsList', array($_GET['server']));
	$paquet -> add_action('serverList', array($_GET['server']));
}
else {
	$paquet -> add_action('vpsList');
}

$paquet -> send_actions();

$vps = $paquet->getAnswer('vpsList');

if(!empty($vps)) {
	$smarty->assign('vpsList', $vps);
}

if(!empty($_GET['server'])) {
	$smarty->assign('serverCurrent', $_GET['server']);
	$smarty->assign('Title', $paquet->getAnswer('serverList')->list->$_GET['server']->name.
	                         ' <a onclick="popupServerReload('.$paquet->getAnswer('serverList')->list->$_GET['server']->id.');" title="'._('Reload the server').'" href="#"><span class="glyphicon glyphicon-refresh"></span></a>');
}
else {
	$smarty->assign('serverCurrent', 0);
	$smarty->assign('Title', _('Vps list'));
}

?>