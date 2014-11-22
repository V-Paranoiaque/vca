<?php 

echo '<div class="input-group">
	<span class="input-group-btn">
		<button class="btn btn btn-danger" type="button">'._('IP').'</button>
	</span><input id="ip" type="text" class="form-control">
</div>	

<div class="center">
	<button onclick="popupclose()" type="button" class="btn btn-danger" data-toggle="dropdown">'._('Cancel').'</button> <button onclick="formIpAdd()" type="button" class="btn btn-success" data-toggle="dropdown">'._('Confirm').'</button>
</div>';

?>