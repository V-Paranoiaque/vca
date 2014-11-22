<?php

include('../../config.php');
include('../../functions.php');
include('../../libs/Paquet.class.php');

$paquet = new Paquet();
$paquet -> add_action('vpsList');
$paquet -> send_actions();

$vps = $paquet->getAnswer('vpsList')->$_GET['vps'];

printf(_('Change the root password of %s'), '<b>'.$vps->name.'</b>');

echo '<br/><br/>'.

'<div class="input-group">'.
	'<span class="input-group-btn">'.
		'<button type="button" class="btn btn btn-danger">'._('New root password').'</button>'.
	'</span><input type="text" class="form-control" name="password" id="password" placeholder="'._('New root password').'">'.
'</div>';

echo '<br/><br/>';
echo '<div class="center">';
echo '<button onclick="popupclose()" type="button" class="btn btn-danger" data-toggle="dropdown">'._('Cancel').'</button>';
echo '<button onclick="formVpsPassword('.$vps->id.')" type="button" class="btn btn-success" data-toggle="dropdown">'._('Confirm').'</button>';
echo '</div>';

?>