<?php 

$vca_page_title = _('Virtual Control Admin');

if(!empty($_POST['login']) && !empty($_POST['password'])) {
	$paquet = new Paquet();
	$paquet -> add_action('connect', array($_POST['login'],$_POST['password']));
	$paquet -> send_actions();
	redirect();
}

?>
