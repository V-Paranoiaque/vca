<?php 

$vca_page_title = _('Virtual Control Admin');

if(!empty($_POST['login']) && !empty($_POST['password'])) {
	if(empty($_POST['token'])) {
		$_POST['token'] = '';
	}
	$paquet = new Paquet();
	$paquet -> add_action('connect', array($_POST['login'],$_POST['password'], $_POST['token']));
	$paquet -> send_actions();
	redirect();
}

?>
