<?php

include('header.php');

$paquet = new Paquet();
$paquet -> add_action('vpsList');
$paquet -> send_actions();

$vps = $paquet->getAnswer('vpsList')->$_GET['vps'];
echo
'<div class="panel panel-danger">'.
	'<div class="panel-heading">'.
		'<h3 class="panel-title">';
			printf(_('Change the root password of %s'), '<b>'.$vps->name.'</b>');
		echo 
		'</h3>'.
	'</div>'.
	'<div class="panel-body">'.
		'<div class="row">'.
			'<div class="col-sm-6">'._('New root password').'</div>'.
			'<div class="col-sm-6"><input type="text" class="form-control" name="password" id="password" placeholder="'._('New root password').'"></div>'.
		'</div>'.
	'</div>'.
'</div>'.

'<div class="center"><br/>'.
'<button onclick="popupclose()" type="button" class="btn btn-danger" data-toggle="dropdown">'._('Cancel').'</button> ';
echo '<button onclick="formVpsPassword('.$vps->id.')" type="button" class="btn btn-success" data-toggle="dropdown">'._('Confirm').'</button>';
echo '</div>';
echo '<script type="text/javascript">'.
		'$("#popupTitle").html("'._('Change Vps password').'");'.
		'</script>';

?>