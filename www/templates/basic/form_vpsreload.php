<?php

include('../../config.php');
include('../../functions.php');
include('../../libs/Paquet.class.php');

$paquet = new Paquet();
$paquet -> add_action('vpsReload', array($_GET['vps']));
$paquet -> send_actions();

?>
