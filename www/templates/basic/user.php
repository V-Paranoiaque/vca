<?php 

$vca_page_title = _('Users Virtual Control Admin');

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

$smarty->assign('Userlist', _('User list'));
$smarty->assign('Modify', _('Modify'));
$smarty->assign('Vps', _('Vps'));
$smarty->assign('Name', _('Name'));
$smarty->assign('Mail', _('Mail'));
$smarty->assign('Save', _('Save'));
$smarty->assign('Close', _('Close'));
$smarty->assign('Novirtualserver', _('No virtual server'));
$smarty->assign('Userinformations', _('User informations'));
$smarty->assign('Userpassword', _('User password'));
$smarty->assign('Newpassword', _('New password'));
$smarty->assign('Confirm', _('Confirm'));
$smarty->assign('Informations', _('Informations'));
$smarty->assign('userUpdate', errorToText($paquet->getAnswer('setUserUpdate')));

?>
