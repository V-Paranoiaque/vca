<?php

include('header.php');

$paquet = new Paquet();
$paquet -> add_action('serverRestart', array($_GET['server']));
$paquet -> send_actions();

?>