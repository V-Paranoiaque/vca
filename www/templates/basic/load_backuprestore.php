<?php

include('../../config.php');
include('../../functions.php');
include('../../libs/Paquet.class.php');

$paquet = new Paquet();
$paquet -> send_actions();

echo '<div class="center">'._('Restoration in progress').'</div>';
echo '<script type="text/javascript">'.
		'$("#popupTitle").html("'._('Restoration in progress').'");'.
		'</script>';
?>
