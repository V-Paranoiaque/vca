<?php

include('../../config.php');
include('../../functions.php');
include('../../libs/Paquet.class.php');

include('../../libs/Db.class.php');
include('../../libs/Socket.class.php');
include('../../libs/Vps.class.php');
include('../../libs/Server.class.php');
include('../../libs/Guest.class.php');
include('../../libs/User.class.php');
include('../../libs/Admin.class.php');

$paquet = new Paquet();
$paquet -> send_actions();

echo 
'<div class="panel panel-danger">'.
	'<div class="panel-heading">'.
		'<h3 class="panel-title">'._('Information').'</h3>'.
	'</div>'.
	'<div class="panel-body">'.
		'<div class="row">'.
			'<div class="col-sm-6">'._('Name').'</div>'.
			'<div class="col-sm-6">'.
				'<input id="name" type="text" class="form-control" placeholder="'._('Name').'">'.
			'</div>'.
		'</div>'.
		'<div class="row">'.
			'<div class="col-sm-6">'._('Mail').'</div>'.
			'<div class="col-sm-6">'.
				'<input id="mail" type="text" class="form-control" placeholder="'._('Mail').'">'.
			'</div>'.
		'</div>'.
		'<div class="row">'.
			'<div class="col-sm-6">'._('Password').'</div>'.
			'<div class="col-sm-6">'.
				'<input id="password" type="password" class="form-control" placeholder="'._('Password').'">'.
			'</div>'.
		'</div>'.
	'</div>'.
'</div>'.
'<div class="center">
	<button onclick="popupclose()" type="button" class="btn btn-danger" data-toggle="dropdown">'._('Cancel').'</button> '.
	'<button onclick="formUserAdd()" type="button" class="btn btn-success" data-toggle="dropdown">'._('Confirm').'</button>
</div>';

echo '<script type="text/javascript">'.
		'$("#popupTitle").html("'._('Add a new user').'");'.
		'</script>';

?>