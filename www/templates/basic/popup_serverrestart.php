<?php

include('header.php');

$paquet = new Paquet();
$paquet -> send_actions();

echo '<div class="center">'._('Do you confirm ?').'
<button onclick="popupclose()" type="button" class="btn btn-danger" data-toggle="dropdown">'._('Cancel').'</button> <button onclick="formServerRestart('.$_GET['server'].')" type="button" class="btn btn-success" data-toggle="dropdown">'._('Confirm').'</button>
</div>';
echo '<script type="text/javascript">'.
'$("#popupTitle").html("'._('Restart the server').'");'.
'</script>';

?>
