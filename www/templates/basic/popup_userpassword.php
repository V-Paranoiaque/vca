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
'<div class="alert alert-danger alert-dismissible" role="alert" id="alert_userpassword">'.
	'<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">'._('Close').'</span></button>'.
	'<div id="error_userpassword"></div>'.
'</div>';

echo 
'<div class="panel panel-danger">'.
	'<div class="panel-heading">'.
		'<h3 class="panel-title">'._('Change your password').'</h3>'.
	'</div>'.
	'<div class="panel-body">'.
		'<div class="row">'.
			'<div class="col-sm-6">'._('Current password').'</div>'.
			'<div class="col-sm-6"><input type="password" class="form-control" name="current" id="current" placeholder="'._('Current password').'"></div>'.
		'</div>'.
		'<div class="row">'.
			'<div class="col-sm-6">'._('New password').'</div>'.
			'<div class="col-sm-6"><input type="password" class="form-control" name="newpassword" id="newpassword" placeholder="'._('New password').'"></div>'.
		'</div>'.
		'<div class="row">'.
			'<div class="col-sm-6">'._('Confirm new password').'</div>'.
			'<div class="col-sm-6"><input type="password" class="form-control" name="confirmpassword" id="confirmpassword" placeholder="'._('Confirm new password').'"></div>'.
		'</div>'.
	'</div>'.
'</div>';
echo '<br/>';
echo '<div class="center">';
echo '<button onclick="popupclose()" type="button" class="btn btn-danger" data-toggle="dropdown">'._('Cancel').'</button> ';
echo '<button onclick="formUserPassword()" type="button" class="btn btn-success" data-toggle="dropdown">'._('Confirm').'</button>';
echo '</div>';
echo '<script type="text/javascript">setTimeout(function() {'.
'$("#alert_userpassword").hide();'.
'}, 100);'.
'$("#popupTitle").html("'._('Change your password').'");'.
'</script>';

?>