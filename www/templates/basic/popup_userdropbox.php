<?php

include('header.php');

$paquet = new Paquet();
$paquet -> add_action('dropboxGetUrl');
$paquet -> send_actions();

$dropboxUrl = $paquet->getAnswer('dropboxGetUrl');

if(!empty($dropboxUrl)) {

echo '<div id="errorDropbox" role="alert" class="alert alert-danger alert-hide">'.
		_('Bad authorization code').
		'</div>';

echo '<div class="panel panel-danger">'.
	'<div class="panel-heading">'.
		'<h3 class="panel-title">'._('Define a Dropbox account').'</h3>'.
	'</div>'.
	'<div class="panel-body">'.
		'<div class="row">'.
			'<div class="col-sm-6">'._('Authorization code').'</div>'.
			'<div class="col-sm-6">'.
				'<input type="password" placeholder="'._('Authorization code').'" class="form-control" id="authorization">'.
			'</div>'.
		'</div>'.
		'<div class="row center"><br/>'.
				'<a class="btn btn-danger" href="'.$dropboxUrl.'" target="_blank">'.
					'<span class="glyphicon glyphicon-lock"></span> '._('Generate code').
				'</a> '.
				'<button data-toggle="dropdown" class="btn btn-success" type="button" onclick="formDropboxToken()">'._('Save').'</button>'.
		'</div>'.
	'</div>'.
'</div>';

}
else {
	echo '<div role="alert" class="alert alert-danger">'.
	_('Dropbox is not configured').
	'</div>';
}

echo '<script type="text/javascript">'.
		'$("#popupTitle").html("'._('Configure Dropbox').'");'.
		'</script>';

?>