<?php

include('../../config.php');
include('../../functions.php');
include('../../libs/Paquet.class.php');

$paquet = new Paquet();
$paquet -> add_action('vpsSchedule', array($_GET['vps']));
$paquet -> send_actions();
$vpsScheduleList = $paquet->getAnswer('vpsSchedule');

if(!empty($vpsScheduleList) && !empty($_GET['save']) && 
   !empty($vpsScheduleList->$_GET['save'])) {
  $info = $vpsScheduleList->$_GET['save'];
  $dayw = sprintf('%07d', decbin($info->dayw));
  $dayn = str_pad(decbin($info->dayn), 31, '0', STR_PAD_LEFT);
  $month= sprintf('%12d', decbin($info->month));
}

	echo
	'<div class="panel panel-danger">'.
		'<div class="panel-heading">'.
			'<h3 class="panel-title">'._('Schedule').' <span class="glyphicon glyphicon-align-justify cursor" onclick="displayvpsScheduleList()"></span></h3>'.
		'</div>'.
		'<div class="panel-body" id="panel-backupschedule-list">';
if(empty($vpsScheduleList) or sizeof($vpsScheduleList) == 0) {
  echo '<div class="row center">'._('No scheduled backup').'</div>';
}
else {
  echo '<table class="table">';
  foreach($vpsScheduleList as $schedule) {
    echo 
    '<tr><td>'.$schedule->name.'</td><td>'.
    '<a onclick="popupBackupSchedule('.$_GET['vps'].', '.$schedule->schedule_id.');" title="Editer" href="#"><span class="glyphicon glyphicon-pencil"></span></a> '.
    '<a onclick="popupBackupScheduleDelete('.$_GET['vps'].', '.$schedule->schedule_id.');" title="Supprimer" href="#"><span class="glyphicon glyphicon-remove"></span></a>'.
    '</td></tr>';
  }
  echo '</table>';
}

echo 
		'</div>'.
		'<div class="panel-heading">';
		 if(!empty($info)) {
			echo '<h3 class="panel-title">'._('Modify backup schedule').' <span class="glyphicon glyphicon-plus cursor" onclick="displayScheduleAdd()" id="displayScheduleAdd"></span></h3>';
		 }
		 else {
			echo '<h3 class="panel-title">'._('News schedule').' <span class="glyphicon glyphicon-plus cursor" onclick="displayScheduleAdd()" id="displayScheduleAdd"></span></h3>';
		 }
		
		echo 
		'</div>'.
		'<div class="panel-body" id="panel-backupschedule-add">'.
		  '<div class="row">'.
				'<div class="col-sm-6">'.
					_('Name').
				'</div>'.
				'<div class="col-sm-6">'.
					'<input type="text" class="form-control" name="name" id="name" '.
					'placeholder="'._('Name').'" value="'.(!empty($info)?$info->name:'').'">'.
				'</div>'.		  
		  '</div>'.
			'<div class="row"><br/>'.
				'<div class="col-sm-3">'.
					_('Hour').
				'</div>'.
				'<div class="col-sm-3">'.
					'<input type="number" min="0" max="23" class="form-control" name="hour"'.
					' id="hour" placeholder="'._('Hour').'" value="'.(!empty($info)?$info->hour:'0').'">'.
				'</div>'.
				'<div class="col-sm-3">'.
					_('Minutes').
				'</div>'.
				'<div class="col-sm-3">'.
					'<input type="number" min="0" max="59" class="form-control" name="minute"'.
					' id="minute" placeholder="'._('Minutes').'" value="'.(!empty($info)?$info->minute:'0').'">'.
				'</div>'.
			'</div>'.
			'<div class="row"><br/>'.
				'<div class="col-sm-3">'.
				  '<input type="checkbox" id="sunday" ';
			    if(empty($info) or !empty($dayw[0])) {
				    echo 'checked=""';
				  }
				  echo '/> '._('Sunday').
				'</div>'.
				'<div class="col-sm-3">'.
				  '<input type="checkbox" id="monday" ';
			    if(empty($info) or !empty($dayw[1])) {
				    echo 'checked=""';
				  }
				  echo '/> '._('Monday').
				'</div>'.
				'<div class="col-sm-3">'.
				  '<input type="checkbox" id="tuesday" ';
			    if(empty($info) or !empty($dayw[2])) {
				    echo 'checked=""';
				  }
				  echo '/> '._('Tuesday').
				'</div>'.
				'<div class="col-sm-3">'.
				  '<input type="checkbox" id="wednesday" ';
			    if(empty($info) or !empty($dayw[3])) {
				    echo 'checked=""';
				  }
				  echo '/> '._('Wednesday').
				'</div>'.
				'<div class="col-sm-3">'.
				  '<input type="checkbox" id="thursday" ';
			    if(empty($info) or !empty($dayw[4])) {
				    echo 'checked=""';
				  }
				  echo '/> '._('Thursday').
				'</div>'.
				'<div class="col-sm-3">'.
				  '<input type="checkbox" id="friday" ';
			    if(empty($info) or !empty($dayw[5])) {
				    echo 'checked=""';
				  }
				  echo '/> '._('Friday').
				'</div>'.
				'<div class="col-sm-3">'.
				  '<input type="checkbox" id="saturday" ';
			    if(empty($info) or !empty($dayw[6])) {
				    echo 'checked=""';
				  }
				  echo '/> '._('Saturday').
				'</div>'.
				'<div class="col-sm-3"></div>'.
			'</div>'.

		  '<div class="row center"><b class="line center">'._('Days').'</b></div>'.
		  '<div class="row">';
		    for($i=1;$i<=31;$i++) {
		      echo 
		      '<div class="col-sm-3">'.
				    '<input type="checkbox" id="day_'.$i.'" ';
				    if(empty($info) or !empty($dayn[$i-1])) {
  				    echo 'checked=""';
  				  }
				    echo '/> '.$i.
				  '</div>';
		    }
echo 
  		'</div>'.
  		'<div class="row center"><b class="line center">'._('Month').'</b></div>'.
			'<div class="row">'.
				'<div class="col-sm-3">'.
				  '<input type="checkbox" id="month_1" ';
			    if(empty($info) or !empty($month[0])) {
				    echo 'checked=""';
				  }
				  echo '/> '._('January').
				'</div>'.
				'<div class="col-sm-3">'.
				  '<input type="checkbox" id="month_2" ';
			    if(empty($info) or !empty($month[1])) {
				    echo 'checked=""';
				  }
				  echo '/> '._('February').
				'</div>'.
				'<div class="col-sm-3">'.
				  '<input type="checkbox" id="month_3" ';
			    if(empty($info) or !empty($month[2])) {
				    echo 'checked=""';
				  }
				  echo '/> '._('March').
				'</div>'.
				'<div class="col-sm-3">'.
				  '<input type="checkbox" id="month_4" ';
			    if(empty($info) or !empty($month[3])) {
				    echo 'checked=""';
				  }
				  echo '/> '._('April').
				'</div>'.
				'<div class="col-sm-3">'.
				  '<input type="checkbox" id="month_5" ';
			    if(empty($info) or !empty($month[4])) {
				    echo 'checked=""';
				  }
				  echo '/> '._('May').
				'</div>'.
				'<div class="col-sm-3">'.
				  '<input type="checkbox" id="month_6" ';
			    if(empty($info) or !empty($month[5])) {
				    echo 'checked=""';
				  }
				  echo '/> '._('June').
				'</div>'.
				'<div class="col-sm-3">'.
				  '<input type="checkbox" id="month_7" ';
			    if(empty($info) or !empty($month[6])) {
				    echo 'checked=""';
				  }
				  echo '/> '._('July').
				'</div>'.
				'<div class="col-sm-3">'.
				  '<input type="checkbox" id="month_8" ';
			    if(empty($info) or !empty($month[7])) {
				    echo 'checked=""';
				  }
				  echo '/> '._('August').
				'</div>'.
				'<div class="col-sm-3">'.
				  '<input type="checkbox" id="month_9" ';
			    if(empty($info) or !empty($month[8])) {
				    echo 'checked=""';
				  }
				  echo '/> '._('September').
				'</div>'.
				'<div class="col-sm-3">'.
				  '<input type="checkbox" id="month_10" ';
			    if(empty($info) or !empty($month[9])) {
				    echo 'checked=""';
				  }
				  echo '/> '._('October').
				'</div>'.
				'<div class="col-sm-3">'.
				  '<input type="checkbox" id="month_11" ';
			    if(empty($info) or !empty($month[10])) {
				    echo 'checked=""';
				  }
				  echo '/> '._('November').
				'</div>'.
				'<div class="col-sm-3">'.
				  '<input type="checkbox" id="month_12" ';
			    if(empty($info) or !empty($month[11])) {
				    echo 'checked=""';
				  }
				  echo '/> '._('December').
				'</div>'.
			'</div>'.
			'<div class="row center"><br/>'.
	      '<button onclick="formScheduleAdd('.$_GET['vps'].', '.(!empty($info)?$info->schedule_id:'0').')" type="button" class="btn btn-success" data-toggle="dropdown">'._('Save').'</button>'.
			'</div>'.
		'</div>'.
	'</div>';

echo '<script type="text/javascript">'.
'$("#popupTitle").html("'._('Backup schedule').'");';
  if(!empty($info)) {
    echo 'setTimeout(function() {$("#displayScheduleAdd").click();}, 500);';
  }
  echo '</script>';

?>
