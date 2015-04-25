<?php

include('header.php');

if(!empty($_GET['server']) && is_numeric($_GET['server'])) {
	$paquet = new Paquet();
	$paquet -> add_action('serverTemplate', array($_GET['server']));
	$paquet -> add_action('ipFree');
	$paquet -> send_actions();
	
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
					'<input type="text" class="form-control" name="vps_name" id="vps_name" placeholder="'._('Vps name').'">'.
				'</div>'.
			'</div>'.
			'<div class="row">'.
				'<div class="col-sm-6">'.
					_('OS Template').
				'</div>'.
				'<div class="col-sm-6"><select name="os" id="os" class="form-control">';
	
	$templateList = $paquet->getAnswer('serverTemplate');
	if(!empty($templateList)) {
		foreach ($templateList as $template) {
			echo '<option value="'.$template.'">'.$template.'</option>';
		}
	}
	
	echo
				'</select></div>'.
			'</div>'.
			'<div class="row">'.
				'<div class="col-sm-6">'.
					_('Start on Boot').
				'</div>'.
				'<div class="col-sm-6">'.
					'<input type="checkbox" class="form-control" name="onboot" id="onboot">'.
				'</div>'.
			'</div>'.
			'<div class="row">'.
				'<div class="col-sm-6">'.
					_('Vps IPv4').
				'</div>'.
				'<div class="col-sm-6"><select name="vps_ipv4" id="vps_ipv4" class="form-control">';

	$ipFree = $paquet->getAnswer('ipFree');
	if(!empty($ipFree)) {
		foreach ($ipFree as $ip) {
			echo '<option value="'.$ip.'">'.$ip.'</option>';
		}
	}
	else {
		echo '<option value=""></option>';
	}
	
echo 
				'</select></div>'.
			'</div>'.
		'</div>'.
		'<div class="panel-heading">'.
			'<h3 class="panel-title">'._('Limits').'</h3>'.
		'</div>'.
		'<div class="panel-body">'.
			'<div class="row">'.
				'<div class="col-sm-6">'.
					_('Ram (MB or GB)').
				'</div>'.
				'<div class="col-sm-6">'.
					'<input type="text" class="form-control" name="ram" id="ram" placeholder="'._('Memory').'" value="'._('unlimited').'">'.
				'</div>'.
			'</div>'.
			'<div class="row">'.
				'<div class="col-sm-6">'.
					_('Swap (MB or GB)').
				'</div>'.
				'<div class="col-sm-6">'.
					'<input type="text" class="form-control" name="swap" id="swap" placeholder="'._('Swap').'" value="'._('unlimited').'">'.
				'</div>'.
			'</div>'.
			'<div class="row">'.
				'<div class="col-sm-6">'.
					_('Diskspace (MB or GB)').
				'</div>'.
				'<div class="col-sm-6">'.
					'<input type="text" class="form-control" name="diskspace" id="diskspace" placeholder="'._('Diskspace').'" value="4 GB">'.
				'</div>'.
			'</div>'.
			'<div class="row">'.
				'<div class="col-sm-6">'.
					_('Number of CPUs').
				'</div>'.
				'<div class="col-sm-6">'.
					'<input type="text" class="form-control" name="vps_cpus" id="vps_cpus" placeholder="'._('CPUs').'" value="1">'.
				'</div>'.
			'</div>'.
			'<div class="row">'.
				'<div class="col-sm-6">'.
					_('CPU limit (100% = 1 CPU)').
				'</div>'.
				'<div class="col-sm-6">'.
					'<input type="text" class="form-control" name="vps_cpulimit" id="vps_cpulimit" placeholder="'._('Limit').'" value="100">'.
				'</div>'.
			'</div>'.
		'</div>'.
	'</div>'.
	'<div class="center">
	 	<button onclick="popupclose()" type="button" class="btn btn-danger" data-toggle="dropdown">'._('Cancel').'</button> '.
		 '<button onclick="formVpsAdd('.$_GET['server'].')" type="button" class="btn btn-success" data-toggle="dropdown">'._('Confirm').'</button>'.
	 '</div>';

}
echo '<script type="text/javascript">'.
		'$("#popupTitle").html("'._('Create a new Vps').'");'.
		'</script>';
?>