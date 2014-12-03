<?php

include('../../config.php');
include('../../functions.php');
include('../../libs/Paquet.class.php');

$paquet = new Paquet();
$paquet -> send_actions();

if(!empty($_GET['vps']) && !empty($_GET['name'])) {
	echo '<div class="center">';
	printf(_('Delete %s.'), '<b>'.$_GET['name'].'</b>'); 
	echo '<br/><br/>';
	echo '<button onclick="popupclose()" type="button" class="btn btn-danger" data-toggle="dropdown">'._('Cancel').'</button> ';
	echo '<button onclick="formBackupDelete('.$_GET['vps'].', '.$_GET['name'].')" type="button" class="btn btn-success" data-toggle="dropdown">'._('Confirm').'</button>';
	echo '</div>';
}
else {
	echo _('Error');
}

echo '<script type="text/javascript">'.
		'$("#popupTitle").html("'._('Delete a backup').'");'.
		'</script>';

?>