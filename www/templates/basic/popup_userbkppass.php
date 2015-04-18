<?php

include('header.php');

$paquet = new Paquet();
$paquet -> send_actions();

echo
'<div class="panel panel-danger">'.
	'<div class="panel-heading">'.
		'<h3 class="panel-title">'._('Define the backup password').'</h3>'.
	'</div>'.
	'<div class="panel-body">'.
		'<div class="row">'.
			'<div class="col-sm-6">'._('Backup password').'</div>'.
			'<div class="col-sm-6"><input type="password" class="form-control" name="password" id="password" placeholder="'._('Backup password').'"></div>'.
		'</div>'.
	'</div>'.
'</div>'.

'<div class="center"><br/>'.
'<button onclick="popupclose()" type="button" class="btn btn-danger" data-toggle="dropdown">'._('Cancel').'</button> ';
echo '<button onclick="formUserBkppass()" type="button" class="btn btn-success" data-toggle="dropdown">'._('Confirm').'</button>';
echo '</div>';
echo '<script type="text/javascript">'.
		'$("#popupTitle").html("'._('Define the backup password').'");'.
		'</script>';

?>