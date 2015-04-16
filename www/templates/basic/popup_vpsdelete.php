<?php

include('header.php');

$paquet = new Paquet();
$paquet -> add_action('vpsList');
$paquet -> send_actions();

$server = $paquet->getAnswer('vpsList');

if(!empty($server) && !empty($server->$_GET['vps'])) {
	$vps = $server->$_GET['vps'];
	printf( 
	_('If you delete %s from %s, you will definitly delete all datas'), '<b>'.$vps->name.'</b>', '<b>'.$vps->serverName.'</b>'); 
	
	echo '<br/><br/>';
	echo '<div class="center">';
	echo '<button onclick="popupclose()" type="button" class="btn btn-danger" data-toggle="dropdown">'._('Cancel').'</button> ';
	echo '<button onclick="formVpsDelete('.$vps->serverId.', '.$vps->id.')" type="button" class="btn btn-success" data-toggle="dropdown">'._('Confirm').'</button>';
	echo '</div>';
}
else {
	echo '<h2 class="center">'._('error').'</h2>';
}
echo '<script type="text/javascript">'.
		'$("#popupTitle").html("'._('Delete the Vps').'");'.
		'</script>';

?>