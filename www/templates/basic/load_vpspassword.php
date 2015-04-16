<?php

include('header.php');

$paquet = new Paquet();
$paquet -> send_actions();

echo '<div class="center">'._('Modification in progress').'</div>';
echo '<script type="text/javascript">'.
		'$("#popupTitle").html("'._('Modify Vps root password').'");'.
		'</script>';
?>
