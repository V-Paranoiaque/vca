<?php

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: POST, GET");
header("Access-Control-Allow-Headers: x-requested-with");
header('Content-Type: application/json');
ini_set('display_errors', 1);
error_reporting(E_ALL);

include('config.php');
include('functions.php');

include('libs/Paquet.class.php');

include('libs/Db.class.php');
include('libs/Socket.class.php');
include('libs/Vps.class.php');
include('libs/Server.class.php');
include('libs/Guest.class.php');
include('libs/User.class.php');
include('libs/Admin.class.php');

if(empty($_POST['token'])) {
	$token = '';
}
else {
	$token = protect($_POST['token']);
}

if(empty($_POST['actions'])) {
	$actions = '';
}
else {
	$actions = $_POST['actions'];
}

$answer = Paquet::vcaAction($actions, $token);

echo json_encode($answer);

?>