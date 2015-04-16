<?php

include('header.php');

$paquet = new Paquet();
$paquet -> send_actions();

echo '<div class="center">'._('Command in execution').'</div>';
echo '<script type="text/javascript">'.
		'$("#popupTitle").html("'._('Send a command').'");'.
		'</script>';
?>
