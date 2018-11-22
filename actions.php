<?php 
require_once('configuration.php');

// Check for actions
if(!isset($_GET['action'])) {
	$redirect = '/Atom-Solutions/index.php';
	header('Location: ' . $redirect);
	exit();
}

// Logout action
if($_GET['action'] == 'logout') {
	session_destroy();
	$redirect = '/Atom-Solutions/user.php';
	header('Location: ' . $redirect);
	exit();
}


$redirect = '/Atom-Solutions/index.php';
header('Location: ' . $redirect);
exit();