<?php

include('../../config.php');
include('../../functions.php');
include('../../libs/Paquet.class.php');

$paquet = new Paquet();
$paquet -> add_action('getServerInfo', array($_GET['server']));
$paquet -> send_actions();

$server = $paquet->getAnswer('getServerInfo');

printf(_('If you remove %s from VCA, you will remove all his Vps from VCA but nothing will be deleted from your server. All informations about this server and his VPS will be definitly deleted.'), '<b>'.$server->name.'</b>'); 

echo '<br/><br/>';
echo '<div class="center">';
echo '<button onclick="popupclose()" type="button" class="btn btn-danger" data-toggle="dropdown">'._('Cancel').'</button>';
echo '<button onclick="formServerRemove('.$server->id.')" type="button" class="btn btn-success" data-toggle="dropdown">'._('Confirm').'</button>';
echo '</div>';

?>