<?php

include('header.php');

$paquet = new Paquet();
$paquet -> add_action('vpsList');
$paquet -> add_action('ipFree');
$paquet -> send_actions();

$vpsList = $paquet->getAnswer('vpsList');
$ips    = $paquet->getAnswer('ipFree');
$server = $vpsList->$_GET['vps'];

echo 
'<div class="panel panel-danger">'.
	'<div class="panel-heading">'.
		'<h3 class="panel-title">'._('Clone').' '.$server->name.'</h3>'.
	'</div>'.
	'<div class="panel-body">'.
		'<div class="row">'.
			'<div class="col-sm-6">'._('Vps name').'</div>'.
			'<div class="col-sm-6">'.
'<input type="text" class="form-control" name="vps_name" id="vps_name" placeholder="'._('Vps name').'">'.
			'</div>'.
		'</div>'.
		'<div class="row">'.
			'<div class="col-sm-6">'._('Vps IPv4').'</div>'.
			'<div class="col-sm-6"><select name="vps_ipv4" id="vps_ipv4" class="form-control"><option value=""></option>';
			foreach ($ips as $ip) {
				echo '<option value="'.$ip.'">'.$ip.'</option>';
			}
echo
			'</select></div>'.
		'</div>'.
	'</div>'.
'</div><div class="center">
	 <button onclick="popupclose()" type="button" class="btn btn-danger" data-toggle="dropdown">'._('Cancel').'</button> '.
	'<button onclick="formVpsClone('.$_GET['server'].', '.$server->id.')" type="button" class="btn btn-success" data-toggle="dropdown">'._('Confirm').'</button>'.
'</div>';
echo '<script type="text/javascript">'.
		'$("#popupTitle").html("'._('Clone the Vps').'");'.
		'</script>';
?>
