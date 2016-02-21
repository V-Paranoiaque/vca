<?php

include('header.php');

$paquet = new Paquet();
$paquet -> add_action('configuration');
$paquet -> send_actions();

$conf = $paquet->getAnswer('configuration');

echo 
'<div class="panel panel-danger">'.
	'<div class="panel-heading">'.
		'<h3 class="panel-title">'._('Strong authentication').'</h3>'.
	'</div>'.
	'<div class="panel-body">'.
		'<div class="row">'.
			'<div class="col-sm-6">'._('Domain key').'</div>'.
			'<div class="col-sm-6">'.
				'<input type="text" class="form-control" '.
				       'name="domainkey" id="domainkey" '.
				       'placeholder="'._('Domain key').'" '.
				       'value="'.$conf->domain_key.'">'.
			'</div>'.
		'</div>'.
		'<div class="row">'.
			'<div class="col-sm-6">'._('Key size').'</div>'.
			'<div class="col-sm-6">'.
				'<select id="key_size" class="form-control">'.
					'<option value="4">4 '._('characters').'</option>'.
					'<option value="8">8 '._('characters').'</option>'.
					'<option value="16">16 '._('characters').'</option>'.
					'<option value="32">32 '._('characters').'</option>'.
				'</select>'.
			'</div>'.
		'</div>'.
		'<div class="row">'.
			'<div class="col-sm-6">'._('Validity').'</div>'.
			'<div class="col-sm-6">'.
				'<select id="validity" class="form-control">'.
					'<option value="15">15 '._('seconds').'</option>'.
					'<option value="30" selected="selected">30 '._('seconds').'</option>'.
					'<option value="45">45 '._('seconds').'</option>'.
					'<option value="60">60 '._('seconds').'</option>'.
					'<option value="90">90 '._('seconds').'</option>'.
					'<option value="120">120 '._('seconds').'</option>'.
				'</select>'.
			'</div>'.
		'</div>'.
	'</div>'.
'</div>';
echo '<br/>';
echo '<div class="center">';
echo '<button onclick="popupclose()" type="button" class="btn btn-danger" data-toggle="dropdown">'._('Cancel').'</button> ';
echo '<button onclick="formConfToken()" type="button" class="btn btn-success" data-toggle="dropdown">'._('Confirm').'</button>';
echo '</div>';
echo '<script type="text/javascript">'.
'$("#popupTitle").html("'._('Strong authentication').'");';

if(!empty($conf->key_size)) {
	echo '$("#key_size").val('.$conf->key_size.');';
}
if(!empty($conf->key_period)) {
	echo '$("#validity").val('.$conf->key_period.');';
}

echo
'</script>';

?>