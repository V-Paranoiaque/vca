<?php

include('../../config.php');
include('../../functions.php');
include('../../libs/Paquet.class.php');

$paquet = new Paquet();
$paquet -> send_actions();

echo 
'<div class="panel panel-danger">'.
	'<div class="panel-heading">'.
		'<h3 class="panel-title">'._('Your request').'</h3>'.
	'</div>'.
	'<div class="panel-body">'.
		'<div class="row">'.
			'<div class="col-sm-4">'._('Subject').'</div>'.
			'<div class="col-sm-8">'.
				'<input id="subject" type="text" class="form-control" placeholder="'._('Subject').'">'.
			'</div>'.
		'</div>'.
		'<div class="row">'.
			'<div class="col-sm-4">'._('Message').'</div>'.
			'<div class="col-sm-8">'.
				'<textarea id="message" class="form-control" placeholder="'._('Message').'" rows="6"></textarea>'.
			'</div>'.
		'</div>'.
	'</div>'.
'</div>'.
'<div class="center">
	<button onclick="popupclose()" type="button" class="btn btn-danger" data-toggle="dropdown">'._('Cancel').'</button> '.
	'<button onclick="formRequestAdd()" type="button" class="btn btn-success" data-toggle="dropdown">'._('Confirm').'</button>
</div>';

echo '<script type="text/javascript">'.
'$("#popupTitle").html("'._('Create a new request').'");'.
'</script>';

?>