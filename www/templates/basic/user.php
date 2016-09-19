<?php 

$vca_page_title = _('Panel users');

$paquet = new Paquet();

if(!empty($_GET['user'])) {
	$paquet -> add_action('userVps', array($_GET['user']));
	if(!empty($_POST['name']) && !empty($_POST['mail'])) {
		$paquet -> add_action('userUpdate',
		                      array($_GET['user'],$_POST['name'],
		                            $_POST['mail'],$_POST['language'],
		                            $_POST['rank']));
	}
	
	if(!empty($_POST['password']) && !empty($_POST['confirm']) && 
	   $_POST['password'] == $_POST['confirm']) {
		$paquet -> add_action('userDefinePassword',
		                      array($_POST['password'],$_GET['user']));
	}
	
	if(!empty($_POST['usertokenid'])) {
		if(empty($_POST['useractivated'])) {
			$useractivated = 0;
		}
		else {
			$useractivated = 1;
		}
		if(empty($_POST['userpin'])) {
			$_POST['userpin'] = '';
		}
		
		$paquet -> add_action('userDefineToken',
		                      array($_POST['usertokenid'],$_POST['userpin'],
		                            $useractivated,$_GET['user']));
	}
}

$paquet -> add_action('userList');
$paquet -> add_action('languageList');
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

$smarty->assign('languageList', $paquet->getAnswer('languageList'));
$smarty->assign('userUpdate', errorToText($paquet->getAnswer('userUpdate')));

?>
