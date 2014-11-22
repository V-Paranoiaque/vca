<?php

include('../../config.php');
include('../../functions.php');
include('../../libs/Paquet.class.php');

$paquet = new Paquet();
$paquet -> add_action('userProfile');
$paquet -> send_actions();
$userInfo = $paquet->getAnswer('userProfile');

echo 
'<div class="panel panel-danger">'.
	'<div class="panel-heading">'.
		'<h3 class="panel-title">'._('User informations').'</h3>'.
	'</div>'.
	'<div class="panel-body">'.
	  '<div class="rows">'.
		  '<div class="col-sm-6">'._('Name').'</div>'.
		  '<div class="col-sm-6"><input type="text" id="name" class="form-control" name="name" placeholder="'._('Name').'" value="'.$userInfo->user_name.'" required></div>'.
		  '</div>'.
	  '<div class="rows">'.
		  '<div class="col-sm-6">'._('Mail').'</div>'.
		  '<div class="col-sm-6"><input type="text" id="mail" class="form-control" name="mail" placeholder="'._('Mail').'" value="'.$userInfo->user_mail.'" required></div>'.
	  '</div>'.
	'</div>'.
'</div>';
echo '<div class="center">
	 	<button onclick="popupclose()" type="button" class="btn btn-danger" data-toggle="dropdown">'._('Cancel').'</button> '.
	'<button onclick="formProfile()" type="button" class="btn btn-success" data-toggle="dropdown">'._('Confirm').'</button>'.
	 '</div>';
	
?>