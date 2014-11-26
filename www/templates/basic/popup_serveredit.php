<?php

include('../../config.php');
include('../../functions.php');
include('../../libs/Paquet.class.php');

$paquet = new Paquet();
$paquet -> add_action('serverList');
$paquet -> send_actions();

$server = $paquet->getAnswer('serverList')->list->$_GET['server'];

echo 
'<div class="panel panel-danger">'.
	'<div class="panel-heading">'.
		'<h3 class="panel-title">'._('Informations').'</h3>'.
	'</div>'.
	'<div class="panel-body">'.
		'<div class="row">'.
			'<div class="col-sm-6">'._('Server name').'</div>'.
			'<div class="col-sm-6">'.
				'<input id="name" type="text" class="form-control" placeholder="'._('Server name').'" value="'.$server->name.'">'.
			'</div>'.
		'</div>'.
		'<div class="row">'.
			'<div class="col-sm-6">'._('IP or Address').'</div>'.
			'<div class="col-sm-6">'.
				'<input id="address" type="text" class="form-control" placeholder="'._('IP or Address').'" value="'.$server->address.'">'.
			'</div>'.
		'</div>'.
		'<div class="row">'.
			'<div class="col-sm-6">'._('Security key').'</div>'.
			'<div class="col-sm-6">'.
				'<input id="key" type="text" class="form-control" placeholder="'._('key').'" value="'.$server->key.'">'.
			'</div>'.
		'</div>'.
		'<div class="row">'.
			'<div class="col-sm-6">'._('Description').'</div>'.
			'<div class="col-sm-6">'.
				'<input id="description" type="text" class="form-control" placeholder="'._('Description').'" value="'.$server->description.'">'.
			'</div>'.
		'</div>'.
	'</div>'.
'</div>'.
'<div class="center">
	<button onclick="popupclose()" type="button" class="btn btn-danger" data-toggle="dropdown">'._('Cancel').'</button> '.
	'<button onclick="formServerEdit('.$_GET['server'].')" type="button" class="btn btn-success" data-toggle="dropdown">'._('Confirm').'</button>
</div>';

echo '<script type="text/javascript">setTimeout(function() {'.
'var width=0;'.
'$(".input-group .input-group-btn .btn").each(function( i ) {'.
'  if(width < $(this).width()) {'.
'    width=$(this).width();'.
'  }'.
'});'.
'$(".input-group .input-group-btn .btn").width(width+"px")'.
'}, 100);';
echo '$("#popupTitle").html("'._('Edit a server').'");'.
	'</script>';
?>