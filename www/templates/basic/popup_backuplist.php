<?php

include('header.php');

if(!empty($_GET['vps']) && is_numeric($_GET['vps'])) {
	$paquet = new Paquet();
	$paquet -> add_action('vpsBackup', array($_GET['vps']));
	$paquet -> add_action('vpsList');
	$paquet -> add_action('bkppassStatus');
	$paquet -> send_actions();
	
	$backupList = $paquet->getAnswer('vpsBackup');
	$vpsList = $paquet->getAnswer('vpsList');
	$bkppassStatus = $paquet->getAnswer('bkppassStatus');
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
echo '<div class="btn-group">'.
      '<button class="btn btn-danger" type="button" onclick="formBackupAdd('.$_GET['vps'].');">'._('Create a new backup').'</button>'.
      '<button aria-expanded="false" data-toggle="dropdown" class="btn btn-danger dropdown-toggle" type="button">'.
        '<span class="caret"></span>'.
      '</button>'.
      '<ul role="menu" class="dropdown-menu dropdown-menu-danger">';

		if($bkppassStatus == 1) {
			echo '<li><a href="#" onclick="formDropbox('.$_GET['vps'].', 1)">'._('Save on Dropbox with password').'</a></li>';
		}
		
		echo 
        '<li><a href="#" onclick="formDropbox('.$_GET['vps'].', 0)">'._('Save on Dropbox without password').'</a></li>'.
      '</ul>'.
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
		'<a href="#" title="'._('Restore').'" onclick="popupBackupRestore('.$_GET['vps'].', '.$info[2].');">'.
			'<button class="btn btn-info" type="button">'.
				'<span class="glyphicon glyphicon-hdd" aria-hidden="true"></span>'.
			'</button>'.
		'</a> '.
		'<a href="#" title="'._('Remove').'" onclick="popupBackupDelete('.$_GET['vps'].', '.$info[2].');">'.
			'<button class="btn btn-danger" type="button">'.
				'<span class="glyphicon glyphicon-remove"></span>'.
			'</button>'.
		'</a>'.
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
