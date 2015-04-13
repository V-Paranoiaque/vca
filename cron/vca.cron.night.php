<?php 

include('/usr/share/vca/www/config.php');
include(PATH.'www/functions.php');

include(PATH.'www/libs/Db.class.php');
include(PATH.'www/libs/Socket.class.php');
include(PATH.'www/libs/Server.class.php');
include(PATH.'www/libs/Guest.class.php');
include(PATH.'www/libs/User.class.php');
include(PATH.'www/libs/Admin.class.php');

$admin = new Admin(0);
$admin->avScan();

?>