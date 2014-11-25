<?php

include('../../config.php');
include('../../functions.php');
include('../../libs/Paquet.class.php');

$paquet = new Paquet();
$paquet -> send_actions();

echo '<div class="input-group">
	<span class="input-group-btn">
		<button class="btn btn btn-danger" type="button">'._('Server name').'</button>
	</span><input id="name" type="text" class="form-control" placeholder="'._('Server name').'">
</div>	
<div class="input-group">
	<span class="input-group-btn">
		<button class="btn btn btn-danger" type="button">'._('IP or Address').'</button>
	</span><input id="address" type="text" class="form-control" placeholder="'._('IP or Address').'">
</div>	
<div class="input-group">
	<span class="input-group-btn">
		<button class="btn btn btn-danger" type="button">'._('Security key').'</button>
	</span><input id="key" type="text" class="form-control" placeholder="'._('key').'">
</div>	
<div class="input-group">
	<span class="input-group-btn">
		<button class="btn btn btn-danger" type="button">'._('Description').'</button>
	</span><input id="description" type="text" class="form-control" placeholder="'._('Description').'">
</div>

<div class="center">
	<button onclick="popupclose()" type="button" class="btn btn-danger" data-toggle="dropdown">'._('Cancel').'</button> <button onclick="formServerAdd()" type="button" class="btn btn-success" data-toggle="dropdown">'._('Confirm').'</button>
</div>';

echo '<script type="text/javascript">setTimeout(function() {'.
'var width=0;'.
'$(".input-group .input-group-btn .btn").each(function( i ) {'.
'  if(width < $(this).width()) {'.
'    width=$(this).width();'.
'  }'.
'});'.
'$(".input-group .input-group-btn .btn").width(width+"px")'.
'}, 100);';
echo '$("#popupTitle").html("'._('Add a new server to VCA').'");'.
	'</script>';
?>