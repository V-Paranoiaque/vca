<?php 

$vca_page_title = _('Panel users');

$paquet = new Paquet();

if(!empty($_GET['user'])) {
	$paquet -> add_action('userVps', array($_GET['user']));
	if(!empty($_POST['name']) && !empty($_POST['mail'])) {
		$paquet -> add_action('userUpdate',
				array($_GET['user'],$_POST['name'],$_POST['mail']));
	}
}

$paquet -> add_action('userList');
$paquet -> send_actions();

if(!empty($paquet->getAnswer('userList'))) {
	$userList = $paquet->getAnswer('userList');
}
else {
	$userList = null;
}

$smarty->assign('userList', $userList);

if(!empty($_GET['user'])) {
	$smarty->assign('userInfo', $paquet->getAnswer('userList')->$_GET['user']);
	$smarty->assign('userVps',  $paquet->getAnswer('userVps'));
}
else {
	$smarty->assign('userInfo', null);
	$smarty->assign('userVps',  null);
}

$smarty->assign('userUpdate', errorToText($paquet->getAnswer('setUserUpdate')));

?>
