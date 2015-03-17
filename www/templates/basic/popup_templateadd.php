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

$files = file_get_contents('http://download.openvz.org/template/precreated/') ;
//Exctract table
preg_match("/<table[^>]*>(.*)<\/table>/isU", $files, $table);

//Exctract templates
preg_match_all("/<a[^>]*>(.*)<\/a>/isU", $table[1], $rows);

$rows = $rows[1];
$rowNb = sizeof($rows);

echo '<div class="col-xs-12 col-sm-4"><a href="#" onclick="displayTemplate(\'normal\')">'._('Normal').'</a></div>';
echo '<div class="col-xs-12 col-sm-4"><a href="#" onclick="displayTemplate(\'minimal\')">'._('Minimal').'</a></div>';
echo '<div class="col-xs-12 col-sm-4"><a href="#" onclick="displayTemplate(\'devel\')">'._('Development').'</a></div>';

echo '<div class="row"><br/><br/><table class="table">';
for($i=3;$i<$rowNb;$i++) {
	if(substr($rows[$i], -7) == '.tar.gz') {
		if(substr($rows[$i], -15) == '-minimal.tar.gz') {
			$class = 'minimal';
		}
		elseif(substr($rows[$i], -13) == '-devel.tar.gz') {
			$class = 'devel';
		}
		else {
			$class = 'normal';
		}
		echo '<tr class="template '.$class.'"><td>'.$rows[$i].'</td>'.
		     '<td><a href="#" title="'._('Download this template').'" onclick="formTemplateAdd('.$_GET['server'].', \''.$rows[$i].'\');"><span aria-hidden="true" class="glyphicon glyphicon-download-alt"></span></a></td></tr>';
	}
}
echo '</table></div>';
echo '<script type="text/javascript">'.
		'$("#popupTitle").html("'._('Add a new template').'");'.
		'function displayTemplate(type) {'.
		  '$(".template").hide();'.
		  '$("."+type).show();'.
		'}'.
		'displayTemplate(\'minimal\')'.
		'</script>';
?>