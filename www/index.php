<?php

include('config.php');
include('functions.php');
include('libs/Paquet.class.php');
include('libs/Smarty.class.php');

include('libs/Db.class.php');
include('libs/Socket.class.php');
include('libs/Vps.class.php');
include('libs/Server.class.php');
include('libs/Guest.class.php');
include('libs/User.class.php');
include('libs/Admin.class.php');

$smarty = new Smarty();
$smarty->setTemplateDir('templates/'.TEMPLATE.'/');

$paquet = new Paquet();
$paquet -> add_action('serverList');
$paquet -> send_actions();

if(!empty($paquet->getAnswer('serverList')->list)) {
	if(empty($_GET['server'])) {
		$_GET['server'] = 0;
	}
	$smarty->assign('serverList', $paquet->getAnswer('serverList')->list);
	$smarty->assign('vpsCurrent', $_GET['server']);
}
else {
	$smarty->assign('serverList', null);
	$smarty->assign('vpsCurrent', 0);
}

if(!empty($paquet->getAnswer('serverList')->nb)) {
	$smarty->assign('vpsNb', $paquet->getAnswer('serverList')->nb);
}
else {
	$smarty->assign('vpsNb', 0);
}
$smarty->assign('userRank', $paquet->userInfo('rank'));

if($paquet->userInfo('id') == 0) {
	if(!empty($_GET['page'])) {
		redirect();
	}
	else {
		$page = 'signin';
	}
}
else {
	if(empty($_GET['page'])) {
		$_GET['page'] = '';
	}
	
	switch ($_GET['page']) {
		case 'settings':
		case 'profile':
		case 'help':
		
		case 'server':
		case 'template':
		case 'ip':
		case 'backup':
		case 'avscan':
		case 'request':
		case 'requestinfo':
		
		case 'vpslist':
		case 'vps':
		
		case 'user':
			$page = $_GET['page'];
		break;
		
		default:
			$page = 'home';
		break;
	}
}

$smarty->assign('currentPage', $page);

// Load PHP var
include ('templates/'.TEMPLATE.'/'.$page.'.php');

echo '<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>'.$vca_page_title.'</title>
    <link rel="icon" href="/favicon.ico">

    <!-- Bootstrap core CSS -->
    <link href="/libs/bootstrap.min.css" rel="stylesheet">
    <link href="/libs/bootstrap-dialog.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="/templates/'.TEMPLATE.'/design.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <script src="/libs/jquery.min.js"></script>
    <script src="/libs/bootstrap.min.js"></script>
    <script src="/libs/bootstrap-dialog.min.js"></script>
    <script src="/templates/'.TEMPLATE.'/scripts.js"></script>
  </head>

  <body>';

if($page != 'signin') {
	$smarty->display('connected_top.tpl');
}

$smarty->display($page.'.tpl');

if($page != 'signin') {
	$smarty->display('connected_bottom.tpl');
}

echo '
	</body>
</html>';

?>
