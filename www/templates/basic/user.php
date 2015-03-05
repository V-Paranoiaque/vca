<?php 

$vca_page_title = _('Panel users');

$paquet = new Paquet();

if(!empty($_GET['user'])) {
	$paquet -> add_action('userVps', array($_GET['user']));
	if(!empty($_POST['name']) && !empty($_POST['mail'])) {
		$paquet -> add_action('userUpdate',
		                      array($_GET['user'],$_POST['name'],$_POST['mail']));
	}
	
	if(!empty($_POST['password']) && !empty($_POST['confirm']) && 
	   $_POST['password'] == $_POST['confirm']) {
		$paquet -> add_action('userDefinePassword',
		                      array($_POST['password'],$_GET['user']));
	}
}

$paquet -> add_action('userList');
$paquet -> send_actions();

$smarty->assign('userList', $paquet->getAnswer('userList'));

if(!empty($_GET['user'])) {
	$smarty->assign('userInfo', $paquet->getAnswer('userList')->$_GET['user']);
	$smarty->assign('userVps',  $paquet->getAnswer('userVps'));
}
else {
	$smarty->assign('userInfo', null);
	$smarty->assign('userVps',  null);
}

$smarty->assign('userUpdate', errorToText($paquet->getAnswer('userDelete')));

?>
