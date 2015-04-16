<?php

include('header.php');

$paquet = new Paquet();
$paquet -> send_actions();

echo '<div class="center">'._('Backup in progress').'</div>';
echo '<script type="text/javascript">'.
		'$("#popupTitle").html("'._('Backup in progress').'");'.
		'</script>';
?>
