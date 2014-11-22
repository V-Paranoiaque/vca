<?php 

$vca_page_title = _('Vps Virtual Control Admin');

$paquet = new Paquet();
$paquet -> add_action('vpsList');
$paquet -> send_actions();

$vpsList = $paquet->getAnswer('vpsList');
$smarty->assign('vps', $vpsList->$_GET['vps']);

$smarty->assign('Send', 'Send');
$smarty->assign('Edit', 'Edit');
$smarty->assign('Reinstall', 'Reinstall');
$smarty->assign('Rootpassword', 'Root password');
$smarty->assign('Start', 'Start');
$smarty->assign('Stop', 'Stop');
$smarty->assign('Restart', 'Restart');
$smarty->assign('Reload', 'Reload');
$smarty->assign('Clone', 'Clone');
$smarty->assign('Delete', 'Delete');
$smarty->assign('VpsStatistics', 'Vps Statistics');
$smarty->assign('core', 'core');
$smarty->assign('CPUload', 'CPU load');
$smarty->assign('Diskusage', 'Disk usage');
$smarty->assign('Memoryusage', 'Memory usage');
$smarty->assign('swap', 'swap');
$smarty->assign('Vpsinformation', 'Vps information');
$smarty->assign('Owner', 'Owner');
$smarty->assign('Nobody', 'Nobody');
$smarty->assign('Onboot', 'Onboot');
$smarty->assign('No', 'No');
$smarty->assign('Yes', 'Yes');
$smarty->assign('OSTemplate', 'OS Template');
$smarty->assign('Shell', 'Shell');
$smarty->assign('Command', 'Command');

?>