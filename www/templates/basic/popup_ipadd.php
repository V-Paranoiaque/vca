<?php

include('header.php');

$paquet = new Paquet();
$paquet -> send_actions();

echo 
'<div class="panel panel-danger">'.
	'<div class="panel-heading">'.
		'<h3 class="panel-title">'._('Add an IP in VCA').'</h3>'.
	'</div>'.
	'<div class="panel-body">'.
		'<div class="row">'.
			'<div class="col-sm-6">'._('IP').'</div>'.
			'<div class="col-sm-6"><input id="ip" type="text" class="form-control"></div>'.
		'</div>'.
	'</div>'.
'</div>'.
'<div class="center">
	<button onclick="popupclose()" type="button" class="btn btn-danger" data-toggle="dropdown">'._('Cancel').'</button> <button onclick="formIpAdd()" type="button" class="btn btn-success" data-toggle="dropdown">'._('Confirm').'</button>
</div>';

echo '<script type="text/javascript">'.
		'$("#popupTitle").html("'._('Add an IP in VCA').'");'.
		'</script>';

?>