<?php

include('header.php');

$paquet = new Paquet();
$paquet -> add_action('serverList');
$paquet -> add_action('vpsList');
$paquet -> send_actions();

$serverList = $paquet->getAnswer('serverList')->list;
$vpsList    = $paquet->getAnswer('vpsList');

if(empty($serverList) or sizeof((array)$serverList) <= 1) {
	echo 
'<div class="alert alert-danger center">'.
_('You can\'t move a VPS with only one server').
'</div>';
}
elseif(empty($_GET['vps']) or empty($vpsList) or empty($vpsList->$_GET['vps']) or 
       empty($_GET['server']) or empty($serverList->$_GET['server'])) {
	echo
	'<div class="alert alert-danger center">'.
	_('This Vps does not exist').
	'</div>';
}
else {
	echo
	'<div class="alert alert-warning center">'.
	_('Don\'t forget to allow root connexion with ssh key authantification between your servers').
	'</div>';
	echo 
'<div class="panel panel-danger">'.
	'<div class="panel-heading">'.
		'<h3 class="panel-title">';
	printf(_('Move %s'), $vpsList->$_GET['vps']->name);
	echo '</h3>'.
	'</div>'.
	'<div class="panel-body">'.
		'<div class="row">'.
			'<div class="col-sm-6">'.
				_('Server').
			'</div>'.
			'<div class="col-sm-6"><select name="server" id="server" class="form-control">';
		foreach ($serverList as $server) {
			if($server->id != $_GET['server']) {
				echo '<option value="'.$server->id.'">'.$server->name.'</option>';
			}
		}
echo
			'</select></div>'.
		'</div>'.
	'</div>'.
'</div>'.
'<div class="center">
<button onclick="popupclose()" type="button" class="btn btn-danger" data-toggle="dropdown">'._('Cancel').'</button> '.
	'<button onclick="formVpsMove('.$_GET['server'].', '.$_GET['vps'].')" type="button" class="btn btn-success" data-toggle="dropdown">'._('Confirm').'</button>'.
'</div>';
}

echo '<script type="text/javascript">'.
 '$("#popupTitle").html("'._('Move a VPS').'");'.
	'</script>';
	
?>