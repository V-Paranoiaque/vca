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

if(!empty($_GET['request'])) {
	
	$paquet = new Paquet();
	$paquet -> add_action('requestInfo', $_GET['request']);
	$paquet -> send_actions();
	$request = $paquet->getAnswer('requestInfo');
	
	echo '<div class="center">'._('Close this request').'<br/><b>'.$request->title.'</b>'; 
	echo '<br/><br/>';
	echo '<button onclick="popupclose()" type="button" class="btn btn-danger" data-toggle="dropdown">'._('Cancel').'</button> ';
	echo '<button onclick="formRequestClose(\''.$_GET['request'].'\')" type="button" class="btn btn-success" data-toggle="dropdown">'._('Confirm').'</button>';
	echo '</div>';
}
else {
	echo _('Error');
}

echo '<script type="text/javascript">'.
		'$("#popupTitle").html("'._('Close this request').'");'.
		'</script>';

?>