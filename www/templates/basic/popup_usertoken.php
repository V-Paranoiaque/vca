<?php

include('header.php');

$paquet = new Paquet();
$paquet -> add_action('userProfile');
$paquet -> send_actions();

$userInfo = $paquet->getAnswer('userProfile');

echo 
'<div class="alert alert-danger alert-dismissible" role="alert" id="alert_usertoken">'.
	'<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">'._('Close').'</span></button>'.
	'<div id="error_usertoken"></div>'.
'</div>';

echo 
'<div class="panel panel-danger">'.
	'<div class="panel-heading">'.
		'<h3 class="panel-title">'._('Strong authentication').'</h3>'.
	'</div>'.
	'<div class="panel-body">'.
		'<div class="row">'.
			'<div class="col-sm-6">'._('Use strong authentication').'</div>'.
			'<div class="col-sm-6"><input type="checkbox" name="useractivated" id="activatetoken" ';
			if($userInfo-> user_strongauth) {
				echo 'checked="checked"';
			}
			echo '> '._('Activate token authentication').'</div>'.
		'</div>'.
		'<div class="row">'.
			'<div class="col-sm-6">'._('New PIN').'</div>'.
			'<div class="col-sm-6"><input type="password" class="form-control" name="newpin" id="newpin" placeholder="'._('New PIN code').'"></div>'.
		'</div>'.
	'</div>'.
'</div>';
echo '<br/>';
echo '<div class="center">';
echo '<button onclick="popupclose()" type="button" class="btn btn-danger" data-toggle="dropdown">'._('Cancel').'</button> ';
echo '<button onclick="formUserToken()" type="button" class="btn btn-success" data-toggle="dropdown">'._('Confirm').'</button>';
echo '</div>';
echo '<script type="text/javascript">setTimeout(function() {'.
'$("#alert_usertoken").hide();'.
'}, 100);'.
'$("#popupTitle").html("'._('Strong authentication').'");'.
'</script>';

?>