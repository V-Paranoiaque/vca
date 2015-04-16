<?php

include('header.php');

$paquet = new Paquet();
$paquet -> send_actions();

echo '<div class="center">'._('Cloning in progress').'</div>';
echo '<script type="text/javascript">'.
		'$("#popupTitle").html("'._('Cloning Vps').'");'.
		'</script>';
?>
