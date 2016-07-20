<?php

include('header.php');

$paquet = new Paquet();
$paquet -> add_action('vpsList');
$paquet -> add_action('vpsTemplate', array($_GET['vps']));
$paquet -> send_actions();

$vps  = $paquet->getAnswer('vpsList')->$_GET['vps'];
$list = $paquet->getAnswer('vpsTemplate');

echo '<div class="center">';
printf(_('Reinstall %s, don\'t forget to redefine the root password.'), '<b>'.$vps->name.'</b>');
echo '</div>';
if(!empty($list)) {
	echo '<select name="os" id="os" class="form-control">';
	foreach ($list as $template) {
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
