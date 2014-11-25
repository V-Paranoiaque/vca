<?php

include('../../config.php');
include('../../functions.php');
include('../../libs/Paquet.class.php');

if(!empty($_GET['ip']) && filter_var($_GET['ip'], FILTER_VALIDATE_IP)) {
	echo '<div class="center">';
	printf(_('Delete %s from VCA.'), '<b>'.$_GET['ip'].'</b>'); 
	echo '<br/><br/>';
	echo '<button onclick="popupclose()" type="button" class="btn btn-danger" data-toggle="dropdown">'._('Cancel').'</button> ';
	echo '<button onclick="formIpDelete(\''.$_GET['ip'].'\')" type="button" class="btn btn-success" data-toggle="dropdown">'._('Confirm').'</button>';
	echo '</div>';
}
else {
	echo _('Error');
}

echo '<script type="text/javascript">'.
		'$("#popupTitle").html("'._('Remove an IP from VCA').'");'.
		'</script>';

?>