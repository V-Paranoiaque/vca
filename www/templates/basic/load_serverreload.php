<?php

include('header.php');

$paquet = new Paquet();
$paquet -> send_actions();

echo '<div class="center">'._('Reload in progress').'</div>';
echo '<script type="text/javascript">'.
		'$("#popupTitle").html("'._('Server reload').'");'.
		'</script>';
?>
