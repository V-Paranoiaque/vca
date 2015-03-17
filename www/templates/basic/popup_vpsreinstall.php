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
$paquet -> add_action('vpsList');
$paquet -> add_action('vpsTemplate', array($_GET['vps']));
$paquet -> send_actions();

$vps = $paquet->getAnswer('vpsList')->$_GET['vps'];

echo '<div class="center">';
printf(_('Reinstall %s, don\'t forget to redefine the root password.'), '<b>'.$vps->name.'</b>');
echo '</div>';

if(!empty($paquet->getAnswer('vpsTemplate'))) {
	echo '<select name="os" id="os" class="form-control">';
	foreach ($paquet->getAnswer('vpsTemplate') as $template) {
		if($template == $vps->ostemplate) {
			echo '<option value="'.$template.'" selected="selected">'.$template.'</option>';
		}
		else {
			echo '<option value="'.$template.'">'.$template.'</option>';
		}
	}
	echo '</select>';
}
echo '<div class="center">
	 	<button onclick="popupclose()" type="button" class="btn btn-danger" data-toggle="dropdown">'._('Cancel').'</button> '.
	'<button onclick="formVpsReinstall('.$_GET['vps'].')" type="button" class="btn btn-success" data-toggle="dropdown">'._('Confirm').'</button>'.
	 '</div>';
echo '<script type="text/javascript">'.
		'$("#popupTitle").html("'._('Reinstall the Vps').'");'.
		'</script>';
?>