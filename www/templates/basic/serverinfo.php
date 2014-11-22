<?php

$vca_page_title = _('Server Virtual Control Admin');

$paquet = new Paquet();

if(!empty($_GET['server'])) {
	if(!empty($_POST['name'])) {
		$para = array('name', 'address', 'key','description');
		
		if(!empty($_POST['name'])) {
			$para['name'] = $_POST['name'];
		}

		if(!empty($_POST['address'])) {
			$para['address'] = $_POST['address'];
		}

		if(!empty($_POST['key'])) {
			$para['key'] = $_POST['key'];
		}

		if(!empty($_POST['description'])) {
			$para['description'] = $_POST['description'];
		}
		
		$paquet -> add_action('setServerInfo', array($_GET['server'], $para));
	}
	
	$paquet -> add_action('getServerInfo', array($_GET['server']));
}
else {
	redirect();
}

$paquet -> send_actions();

$smarty->assign('serverInfo', $paquet->getAnswer('getServerInfo'));

?>