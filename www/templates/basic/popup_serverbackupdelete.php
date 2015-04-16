<?php

include('header.php');

$paquet = new Paquet();
$paquet -> add_action('vpsList', array($_GET['server']));
$paquet -> send_actions();

if(!empty($_GET['vps']) && !empty($_GET['name'])) {
	$vpsList = $paquet->getAnswer('vpsList');
	
	echo '<div class="center">';
	
	if(!empty($vpsList->$_GET['vps'])) {
		printf(_('Delete the backup of %s from %s.'), '<b>'.tsdate($_GET['name']).'</b>', '<b>'.$vpsList->$_GET['vps']->name.'</b>');
	}
	else {
		printf(_('Delete the backup of %s.'), '<b>'.tsdate($_GET['name']).'</b>');
	}
	echo '<br/><br/>';
	echo '<button onclick="popupclose()" type="button" class="btn btn-danger" data-toggle="dropdown">'._('Cancel').'</button> ';
	echo '<button onclick="formServerBackupDelete('.$_GET['server'].', '.$_GET['vps'].', '.$_GET['name'].')" type="button" class="btn btn-success" data-toggle="dropdown">'._('Confirm').'</button>';
	echo '</div>';
}
else {
	echo _('Error');
}

echo '<script type="text/javascript">'.
		'$("#popupTitle").html("'._('Delete a backup').'");'.
		'</script>';

?>