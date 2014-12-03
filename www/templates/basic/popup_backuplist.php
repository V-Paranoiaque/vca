<?php 

include('../../config.php');
include('../../functions.php');
include('../../libs/Paquet.class.php');

if(!empty($_GET['vps']) && is_numeric($_GET['vps'])) {
	$paquet = new Paquet();
	$paquet -> add_action('vpsBackup', array($_GET['vps']));
	$paquet -> send_actions();
	
	$backupList = $paquet->getAnswer('vpsBackup');
	
	echo 
'<div class="row center">'.
'<button class="btn btn-danger" type="button" onclick="formBackupAdd('.$_GET['vps'].');">'._('Create a new backup').'</button>'.
'</div>';
	
	if(!empty($backupList) && sizeof($backupList) > 0) {
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
