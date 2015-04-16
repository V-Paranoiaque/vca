<?php

include('header.php');

$paquet = new Paquet();
$paquet -> add_action('serverDelete', array($_GET['server']));
$paquet -> send_actions();

?>