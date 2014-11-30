<?php

include('../../config.php');
include('../../functions.php');
include('../../libs/Paquet.class.php');

$paquet = new Paquet();
$paquet -> send_actions();

echo '<div class="center">';
printf(_('Delete %s'), '<b>'.$_GET['template'].'</b>');
echo 
'<br/>
	<button onclick="popupclose()" type="button" class="btn btn-danger" data-toggle="dropdown">'._('Cancel').'</button> '.
	'<button onclick="formTemplateDelete('.$_GET['server'].', \''.$_GET['template'].'\')" type="button" class="btn btn-success" data-toggle="dropdown">'._('Confirm').'</button>
</div>';

echo '<script type="text/javascript">'.
 '$("#popupTitle").html("'._('Delete a template').'");'.
	'</script>';
?>