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
$paquet -> send_actions();

echo '<div class="center">'._('Do you confirm ?').'
<button onclick="popupclose()" type="button" class="btn btn-danger" data-toggle="dropdown">'._('Cancel').'</button> <button onclick="formServerRestart('.$_GET['server'].')" type="button" class="btn btn-success" data-toggle="dropdown">'._('Confirm').'</button>
</div>';
echo '<script type="text/javascript">'.
'$("#popupTitle").html("'._('Restart the server').'");'.
'</script>';

?>
