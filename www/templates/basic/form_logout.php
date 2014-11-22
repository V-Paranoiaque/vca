<?php

include('../../config.php');
include('../../functions.php');
include('../../libs/Paquet.class.php');

setcookie('token', '', -1, '/', $_SERVER['HTTP_HOST']);
setcookie('temps', 0, -1, '/', $_SERVER['HTTP_HOST']);

?>
