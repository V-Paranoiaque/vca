<?php 

include('../../config.php');
include('../../functions.php');
include('../../libs/Paquet.class.php');

include('../../libs/Db.class.php');
include('../../libs/Socket.class.php');
include('../../libs/Vps.class.php');
include('../../libs/Server.class.php');
include('../../libs/Guest.class.php');
include('../../libs/User.class.php');
include('../../libs/Admin.class.php');

if(!empty($_GET['vps']) && is_numeric($_GET['vps'])) {
	$paquet = new Paquet();
	$paquet -> add_action('vpsBackup', array($_GET['vps']));
	$paquet -> add_action('vpsList');
	$paquet -> send_actions();
	
	$backupList = $paquet->getAnswer('vpsBackup');
	$vpsList = $paquet->getAnswer('vpsList');
	$server     = $vpsList->$_GET['vps'];
	$nbCurrent  = sizeof((array) $backupList);
	
	if($server->backup_limit > 0 && $nbCurrent >= $server->backup_limit) {
		echo 
'<div role="alert" class="alert alert-danger">'.
'<span aria-hidden="true" class="glyphicon glyphicon-exclamation-sign"></span>';
printf(_('You can\'t have more than %s backups'), $server->backup_limit);
echo '</div>';
	}
	else {
		echo 
'<div class="row center">';
		if($server->backup_limit > 0) {
			printf(_('You can\'t have more than %s backups'), $server->backup_limit);
			echo '<br/>';
		}
echo 
'<button class="btn btn-danger" type="button" onclick="formBackupAdd('.$_GET['vps'].');">'._('Create a new backup').'</button>'.
'</div>';
	}
	
	if(!empty($backupList) && $nbCurrent > 0) {
		echo 
'<table class="table table-striped">'.
'<thead>'.
	'<tr>'.
		'<th>'._('Date').'</th>'.
		'<th></th>'.
	'</tr>'.
'</thead><tbody>';
foreach ($backupList as $template) {
	$info = explode('.',$template);
echo 
'<tr>'.
	'<td>'.tsdate($info[2]).'</td>'.
	'<td>'.
		'<a href="#" title="'._('Restore').'" onclick="popupBackupRestore('.$_GET['vps'].', '.$info[2].');"><span class="glyphicon glyphicon-hdd" aria-hidden="true"></span></a> '.
		'<a href="#" title="'._('Remove').'"  onclick="popupBackupDelete('.$_GET['vps'].', '.$info[2].');"><span class="glyphicon glyphicon-remove"></span></a>'.
	'</td>'.
'</tr>';
}
echo '</tbody></table>';
	}
}

echo '<script type="text/javascript">'.
		'$("#popupTitle").html("'._('Backups management').'");'.
		'</script>';
?>
