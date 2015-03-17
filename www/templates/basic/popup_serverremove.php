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
$paquet -> add_action('serverList');
$paquet -> send_actions();

$server = $paquet->getAnswer('serverList')->list->$_GET['server'];

printf(_('If you remove %s from VCA, you will remove all his Vps from VCA but nothing will be deleted from your server. All information about this server and his Vps will be definitly deleted.'), '<b>'.$server->name.'</b>'); 

echo '<br/><br/>';
echo '<div class="center">';
echo '<button onclick="popupclose()" type="button" class="btn btn-danger" data-toggle="dropdown">'._('Cancel').'</button> ';
echo '<button onclick="formServerRemove('.$server->id.')" type="button" class="btn btn-success" data-toggle="dropdown">'._('Confirm').'</button>';
echo '</div>';
echo '<script type="text/javascript">'.
		'$("#popupTitle").html("'._('Remove the server from VCA').'");'.
		'</script>';
?>