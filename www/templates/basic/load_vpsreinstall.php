<?php

include('header.php');

$paquet = new Paquet();
$paquet -> send_actions();

echo '<div class="center">'._('Reinstallation in progress').'</div>';
echo '<script type="text/javascript">'.
		'$("#popupTitle").html("'._('Vps reinstallation').'");'.
		'</script>';
?>
