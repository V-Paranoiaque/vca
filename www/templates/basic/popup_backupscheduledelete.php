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
$paquet -> add_action('vpsSchedule', array($_GET['vps']));
$paquet -> send_actions();
$vpsScheduleList = $paquet->getAnswer('vpsSchedule');

$info = $vpsScheduleList->$_GET['saveId'];

if(!empty($info)) {
	echo '<div class="center">';
	
	printf(_('Delete %s.'), '<b>'.$info->name.'</b>');
	
	echo '<br/><br/>';
	echo '<button onclick="popupBackupSchedule('.$_GET['vps'].', 0);" type="button" class="btn btn-danger" data-toggle="dropdown">'._('Cancel').'</button> ';
	echo '<button onclick="formBackupScheduleDelete('.$_GET['vps'].', '.$_GET['saveId'].')" type="button" class="btn btn-success" data-toggle="dropdown">'._('Confirm').'</button>';
	echo '</div>';
}

echo '<script type="text/javascript">'.
		'$("#popupTitle2").html("'._('Delete backup schedule').'");'.
		'</script>';
?>
