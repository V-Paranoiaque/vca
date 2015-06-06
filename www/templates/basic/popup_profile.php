<?php

include('header.php');

$paquet = new Paquet();
$paquet -> add_action('userProfile');
$paquet -> add_action('languageList');
$paquet -> send_actions();
$userInfo = $paquet->getAnswer('userProfile');
$languageList = $paquet->getAnswer('languageList');

echo 
'<div class="panel panel-danger">'.
	'<div class="panel-heading">'.
		'<h3 class="panel-title">'._('User information').'</h3>'.
	'</div>'.
	'<div class="panel-body">'.
	  '<div class="row">'.
		  '<div class="col-sm-6">'._('Name').'</div>'.
		  '<div class="col-sm-6"><input type="text" id="name" class="form-control" name="name" placeholder="'._('Name').'" value="'.$userInfo->user_name.'" required></div>'.
		  '</div>'.
	  '<div class="row">'.
		  '<div class="col-sm-6">'._('Email').'</div>'.
		  '<div class="col-sm-6"><input type="text" id="mail" class="form-control" name="mail" placeholder="'._('Email').'" value="'.$userInfo->user_mail.'" required></div>'.
	  '</div>'.
	  '<div class="row">'.
		  '<div class="col-sm-6">'._('Langue').'</div>'.
		  '<div class="col-sm-6"><select id="language" class="form-control">';;

foreach ($languageList as $locale => $name) {
	echo '<option value="'.$locale.'">'.$name.'</option>';
}

echo '</select></div>'.
	  '</div>'.
	'</div>'.
'</div>';
echo '<div class="center">
	 	<button onclick="popupclose()" type="button" class="btn btn-danger" data-toggle="dropdown">'._('Cancel').'</button> '.
	'<button onclick="formProfile()" type="button" class="btn btn-success" data-toggle="dropdown">'._('Confirm').'</button>'.
	 '</div>';
echo '<script type="text/javascript">'.
		'$("#language").val(\''.$paquet -> getLanguage().'\');'.
		'$("#popupTitle").html("'._('Edit your profile').'");'.
		'</script>';
?>