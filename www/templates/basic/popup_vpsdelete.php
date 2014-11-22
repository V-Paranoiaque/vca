<?php

include('../../config.php');
include('../../functions.php');
include('../../libs/Paquet.class.php');

$paquet = new Paquet();
$paquet -> add_action('getVpsInfo', array($_GET['vps']));
$paquet -> send_actions();

$server = $paquet->getAnswer('getVpsInfo');

printf( 
_('If you delete %s from %s, you will definitly delete all datas'), '<b>'.$server->vps_name.'</b>', '<b>'.$server->server_name.'</b>'); 

echo '<br/><br/>';
echo '<div class="center">';
echo '<button onclick="popupclose()" type="button" class="btn btn-danger" data-toggle="dropdown">'._('Cancel').'</button> ';
echo '<button onclick="formVpsDelete('.$server->server_id.', '.$server->id.')" type="button" class="btn btn-success" data-toggle="dropdown">'._('Confirm').'</button>';
echo '</div>';

?>