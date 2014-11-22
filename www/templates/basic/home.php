<?php

$paquet = new Paquet();
$paquet -> add_action('vcaStats');
$paquet -> send_actions();

$smarty->assign('vcastats', $paquet->getAnswer('vcaStats'));

$vca_page_title = _('Dashboard Virtual Control Admin');

?>