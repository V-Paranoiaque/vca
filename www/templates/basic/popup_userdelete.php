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
$paquet -> add_action('userList');
$paquet -> send_actions();

$user = $paquet->getAnswer('userList')->$_GET['user'];

echo 
'<div class="panel panel-danger">'.
	'<div class="panel-heading">';
printf('<h3 class="panel-title">'._('Delete %s').'</h3>', $user->user_name);
echo 	'</div>'.
	'<div class="panel-body">';
printf(_('If you delete this account, every servers of %s will be unattributed.'), $user->user_name);
echo '</div>'.
'</div>';

echo '<br/>';
echo '<div class="center">';
echo '<button onclick="popupclose()" type="button" class="btn btn-danger" data-toggle="dropdown">'._('Cancel').'</button> ';
echo '<button onclick="formUserDelete('.$user->user_id.')" type="button" class="btn btn-success" data-toggle="dropdown">'._('Confirm').'</button>';
echo '</div>';

echo '<script type="text/javascript">'.
'$("#popupTitle").html("'._('Delete an user').'");'.
'</script>';

?>