<?php 

$vca_page_title = _('Virtual Control Admin');

$smarty->assign('Page_Title', _('Virtual Control Admin'));
$smarty->assign('Login', _('Login'));
$smarty->assign('Password', _('Password'));
$smarty->assign('Sign_in', _('Sign in'));
$smarty->assign('Close', _('Close'));

if(!empty($_POST['login']) && !empty($_POST['password'])) {
	$paquet = new Paquet();
	$paquet -> add_action('connect', array($_POST['login'],$_POST['password']));
	$paquet -> send_actions();
	redirect();
}

?>
