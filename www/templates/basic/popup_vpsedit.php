<?php

include('../../config.php');
include('../../functions.php');
include('../../libs/Paquet.class.php');

$paquet = new Paquet();
$paquet -> add_action('getVpsInfo', array($_GET['vps']));
$paquet -> add_action('ipFree');
$paquet -> add_action('userList');
$paquet -> add_action('vpsList');
$paquet -> send_actions();

$vpsList = $paquet->getAnswer('vpsList');

$users  = $paquet->getAnswer('userList');
$ips    = $paquet->getAnswer('ipFree');
$server = $vpsList->$_GET['vps'];

echo 
'<div class="panel panel-danger">'.
	'<div class="panel-heading">'.
		'<h3 class="panel-title">'._('General').'</h3>'.
	'</div>'.
	'<div class="panel-body">'.
		'<div class="row">'.
			'<div class="col-sm-6">'.
_('Vps name').
			'</div>'.
			'<div class="col-sm-6">'.
'<input type="text" class="form-control" name="vps_name" id="vps_name" value="'.$server->name.'" placeholder="'._('Vps name').'">'.
			'</div>'.
		'</div>';
	if($server->serverId > 0) {
echo 
	'<div class="row">'.
			'<div class="col-sm-6">'.
				_('Owner').
			'</div>'.
			'<div class="col-sm-6"><select name="owner" id="owner" class="form-control"><option value="0"></option>';
			foreach ($users as $user) {
				if(!empty($server->ownerId) && $server->ownerId == $user->user_id) {
					echo '<option value="'.$user->user_id.'" selected="selected">'.$user->user_name.'</option>';
				}
				else {
					echo '<option value="'.$user->user_id.'">'.$user->user_name.'</option>';
				}
			}
echo
			'</select></div>'.
		'</div>';
	}
echo 
		'<div class="row">'.
			'<div class="col-sm-6">'.
_('Start on Boot').
			'</div>'.
			'<div class="col-sm-6">'.
'<input type="checkbox" class="form-control" name="onboot" id="onboot" '.(($server->onboot == 1)?'checked="checked"':'').'>'.
			'</div>'.
		'</div>';
	if($server->serverId > 0) {
echo 
	'<div class="row">'.
			'<div class="col-sm-6">'.
_('Vps IPv4').
			'</div>'.
			'<div class="col-sm-6"><select name="vps_ipv4" id="vps_ipv4" class="form-control">';

			if(!empty($server->ipv4)) {
				echo '<option value="'.$server->ipv4.'" selected="selected">'.$server->ipv4.'</option>';
			}
			else {
				echo '<option value=""></option>';
			}
			
			if(!empty($ips) && sizeof($ips) >= 1) {
				foreach ($ips as $ip) {
					echo '<option value="'.$ip.'">'.$ip.'</option>';
				}
			}
echo
			'</select></div>'.
		'</div>';
	}

echo '</div>';
if($server->serverId > 0) {
echo 
	'<div class="panel-heading">'.
		'<h3 class="panel-title">'._('Limits').'</h3>'.
	'</div>'.
	'<div class="panel-body">'.
		'<div class="row">'.
			'<div class="col-sm-6">'.
_('Ram (MB or GB)').
			'</div>'.
			'<div class="col-sm-6">'.
'<input type="text" class="form-control" name="ram" id="ram" value="'.numberRamSize($server->ram).'">'.
			'</div>'.
		'</div>'.
		'<div class="row">'.
			'<div class="col-sm-6">'.
_('Swap (MB or GB)').
			'</div>'.
			'<div class="col-sm-6">'.
'<input type="text" class="form-control" name="swap" id="swap" value="'.numberSwapSize($server->swap).'">'.
			'</div>'.
		'</div>'.
		'<div class="row">'.
			'<div class="col-sm-6">'.
_('Diskspace (MB or GB)').
			'</div>'.
			'<div class="col-sm-6">'.
'<input type="text" class="form-control" name="diskspace" id="diskspace" value="'.numberDiskSpace($server->diskspace).'">'.
			'</div>'.
		'</div>'.
		'<div class="row">'.
			'<div class="col-sm-6">'.
_('Diskinodes').
			'</div>'.
			'<div class="col-sm-6">'.
'<input type="text" class="form-control" name="diskinodes" id="diskinodes" value="'.$server->diskinodes.'">'.
			'</div>'.
		'</div>'.
		'<div class="row">'.
			'<div class="col-sm-6">'.
_('Number of CPUs').
			'</div>'.
			'<div class="col-sm-6">'.
'<input type="text" class="form-control" name="vps_cpus" id="vps_cpus" value="'.$server->cpus.'">'.
			'</div>'.
		'</div>'.
		'<div class="row">'.
			'<div class="col-sm-6">'.
_('CPU limit (100% = 1 CPU)').
			'</div>'.
			'<div class="col-sm-6">'.
'<input type="text" class="form-control" name="vps_cpulimit" id="vps_cpulimit" value="'.$server->cpulimit.'">'.
			'</div>'.
		'</div>'.
		'<div class="row">'.
			'<div class="col-sm-6">'.
_('CPU units').
			'</div>'.
			'<div class="col-sm-6">'.
'<input type="text" class="form-control" name="vps_cpuunits" id="vps_cpuunits" value="'.$server->cpuunits.'">'.
			'</div>'.
		'</div>'.
		'<div class="row">'.
			'<div class="col-sm-6">'.
				_('Backup max number').
			'</div>'.
			'<div class="col-sm-6">'.
'<input type="text" class="form-control" name="backup_limit" id="backup_limit" value="'.$server->backup_limit.'">'.
			'</div>'.
		'</div>'.
	'</div>';
}
echo 
'</div><div class="center">
	 <button onclick="popupclose()" type="button" class="btn btn-danger" data-toggle="dropdown">'._('Cancel').'</button> '.
	'<button onclick="formVpsEdit('.$server->id.')" type="button" class="btn btn-success" data-toggle="dropdown">'._('Confirm').'</button>'.
'</div>';
echo '<script type="text/javascript">'.
		'$("#popupTitle").html("'._('Edit a Vps').'");'.
		'</script>';
?>
