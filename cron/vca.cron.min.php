<?php 

include('/usr/share/vca/www/config.php');
include(PATH.'www/functions.php');

include(PATH.'www/libs/Db.class.php');
include(PATH.'www/libs/Socket.class.php');
include(PATH.'www/libs/Server.class.php');
include(PATH.'www/libs/User.class.php');

$link = Db::link();
$now = $_SERVER['REQUEST_TIME'];

$month= date('m', $now);
$dayn = date('d', $now);
$dayw = date('w', $now);
$hour = date('H', $now);
$min  = date('i', $now);

$sql = 'SELECT schedule_vps, minute, hour, dayw, dayn, month
        FROM schedule';
$req = $link->prepare($sql);
$req->execute();
while ($do = $req->fetch(PDO::FETCH_OBJ)) {
  if($do->minute == $min && $do->hour == $hour) {
    $schedule_dayw = sprintf('%07d', decbin($do->dayw));
    $schedule_dayn = str_pad(decbin($do->dayn), 31, '0', STR_PAD_LEFT);
    $schedule_month= sprintf('%12d', decbin($do->month));
    
    if(!empty($schedule_month[$month-1]) && 
       !empty($schedule_daysn[$dayn-1]) && 
       !empty($schedule_daysw[$dayw-1])) {
      User::vpsBackupAdd($do->schedule_vps);
    }
  }
}

?>
