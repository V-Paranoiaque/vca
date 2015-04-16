<?php

include('header.php');

$paquet = new Paquet();
$paquet -> add_action('vpsReload', array($_GET['vps']));
$paquet -> send_actions();

?>
