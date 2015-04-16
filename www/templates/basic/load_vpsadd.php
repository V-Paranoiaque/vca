<?php

include('header.php');

$paquet = new Paquet();
$paquet -> send_actions();

echo '<div class="center">'._('Creation in progress').'</div>';
echo '<script type="text/javascript">'.
		'$("#popupTitle").html("'._('Create a new Vps').'");'.
		'</script>';
?>
