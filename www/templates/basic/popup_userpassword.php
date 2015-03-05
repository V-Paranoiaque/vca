<?php

include('../../config.php');
include('../../functions.php');
include('../../libs/Paquet.class.php');

$paquet = new Paquet();
$paquet -> send_actions();

echo '<div class="center">'._('Change your password').'</div>'.
'<br/>'.
'<div class="alert alert-danger alert-dismissible" role="alert" id="alert_userpassword">'.
	'<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">'._('Close').'</span></button>'.
	'<div id="error_userpassword"></div>'.
'</div>'.

'<div class="input-group">'.
	'<span class="input-group-btn">'.
		'<button type="button" class="btn btn btn-danger">'._('Current password').'</button>'.
	'</span><input type="password" class="form-control" name="current" id="current" placeholder="'._('Current password').'">'.
'</div>
<div class="input-group">'.
	'<span class="input-group-btn">'.
		'<button type="button" class="btn btn btn-danger">'._('New password').'</button>'.
	'</span><input type="password" class="form-control" name="newpassword" id="newpassword" placeholder="'._('New password').'">'.
'</div>
<div class="input-group">'.
	'<span class="input-group-btn">'.
		'<button type="button" class="btn btn btn-danger">'._('Confirm new password').'</button>'.
	'</span><input type="password" class="form-control" name="confirmpassword" id="confirmpassword" placeholder="'._('Confirm new password').'">'.
'</div>';

echo '<br/><br/>';
echo '<div class="center">';
echo '<button onclick="popupclose()" type="button" class="btn btn-danger" data-toggle="dropdown">'._('Cancel').'</button> ';
echo '<button onclick="formUserPassword()" type="button" class="btn btn-success" data-toggle="dropdown">'._('Confirm').'</button>';
echo '</div>';
echo '<script type="text/javascript">setTimeout(function() {'.
'$("#alert_userpassword").hide();'.
'var width=0;'.
'$(".input-group .input-group-btn .btn").each(function( i ) {'.
'  if(width < $(this).width()) {'.
'    width=$(this).width();'.
'  }'.
'});'.
'$(".input-group .input-group-btn .btn").width(width+"px")'.
'}, 100);'.
'$("#popupTitle").html("'._('Change your password').'");'.
'</script>';

?>