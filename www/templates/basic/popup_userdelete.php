<?php

include('../../config.php');
include('../../functions.php');
include('../../libs/Paquet.class.php');

$paquet = new Paquet();
$paquet -> add_action('userList');
$paquet -> send_actions();

$user = $paquet->getAnswer('userList')->$_GET['user'];

printf('<h3>'._('Delete %s').'</h3>', $user->user);

echo '<div role="alert" class="alert alert-danger">';
printf(_('If you delete this account, every servers of %s will be unattributed.'), $user->user_name);
echo '</div>';

echo '<br/><br/>';
echo '<div class="center">';
echo '<button onclick="popupclose()" type="button" class="btn btn-danger" data-toggle="dropdown">'._('Cancel').'</button> ';
echo '<button onclick="formUserDelete('.$user->user_id.')" type="button" class="btn btn-success" data-toggle="dropdown">'._('Confirm').'</button>';
echo '</div>';

echo '<script type="text/javascript">'.
'$("#popupTitle").html("'._('Delete an user').'");'.
'</script>';

?>