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

echo '<div class="input-group">
	<span class="input-group-btn">
		<button class="btn btn btn-danger" type="button">'._('IP').'</button>
	</span><input id="ip" type="text" class="form-control">
</div>	

<div class="center">
	<button onclick="popupclose()" type="button" class="btn btn-danger" data-toggle="dropdown">'._('Cancel').'</button> <button onclick="formIpAdd()" type="button" class="btn btn-success" data-toggle="dropdown">'._('Confirm').'</button>
</div>';

echo '<script type="text/javascript">'.
		'$("#popupTitle").html("'._('Add an IP in VCA').'");'.
		'</script>';

?>