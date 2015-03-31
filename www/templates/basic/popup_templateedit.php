<?php

include('../../config.php');
include('../../functions.php');
include('../../libs/Paquet.class.php');

include('../../libs/Db.class.php');
include('../../libs/Socket.class.php');
include('../../libs/Vps.class.php');
include('../../libs/Server.class.php');
include('../../libs/Guest.class.php');
include('../../libs/User.class.php');
include('../../libs/Admin.class.php');

$paquet = new Paquet();
$paquet -> send_actions();

echo 
'<div class="panel panel-danger">'.
	'<div class="panel-heading">'.
		'<h3 class="panel-title">'._('Enter the new name').'</h3>'.
	'</div>'.
	'<div class="panel-body">'.
		'<table class="table table-invisible"><tr>'.
			'<td>'._('Template name').'</td>'.
			'<td>'.
				'<input id="name" type="text" class="form-control" placeholder="'._('Template name').'" value="'.$_GET['template'].'">'.
			'</td>'.
		'</tr></table>'.
	'</div>'.
'</div>'.
'<div class="center">
	<button onclick="popupclose()" type="button" class="btn btn-danger" data-toggle="dropdown">'._('Cancel').'</button> '.
	'<button onclick="formTemplateEdit('.$_GET['server'].', \''.$_GET['template'].'\')" type="button" class="btn btn-success" data-toggle="dropdown">'._('Confirm').'</button>
</div>';

echo '<script type="text/javascript">'.
 '$("#popupTitle").html("'._('Rename a template').'");'.
	'</script>';
?>