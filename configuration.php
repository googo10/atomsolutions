<?php
@session_start();

// Redirect to secured (SSL) version of site (because I have few forms with information)
if(empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] == "off"){
    $redirect = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    header('HTTP/1.1 301 Moved Permanently');
    header('Location: ' . $redirect);
    exit();
}

// Redirect to login / register page if user is not loged in
if(!isset($_SESSION['user']) && $page != 'user') {
	$redirect = '/Atom-Solutions/user.php';
	header('Location: ' . $redirect);
	exit();
}


// Show errors
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Set default timezone
date_default_timezone_set('Europe/Sofia');

// ------------------------------------------------------------------------
// DB information
$servername = "localhost";
$username = "";
$password = "";
$database = "";

// Create connection
global $mysqli;
$mysqli = new mysqli($servername, $username, $password, $database);

// Check connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}
// ------------------------------------------------------------------------
// Get user logic
function get_user() {
	global $mysqli;
	
	if(!isset($_SESSION['user'])) {
		return FALSE;
	}
	
	$username = $_SESSION['user'];
	$select_user = "SELECT * FROM `users` WHERE `username`='$username' LIMIT 1";
	$result = $mysqli->query($select_user);
	
	return $result->fetch_assoc();
}
// ------------------------------------------------------------------------
// Get user books
function get_user_books() {
	global $mysqli;
	
	if(!isset($_SESSION['user'])) {
		return FALSE;
	}
	
	$user = get_user();
	$uid = $user['uid'];
	$get_books = "SELECT * FROM `books` WHERE `uid`='$uid'";
	$result = $mysqli->query($get_books);
	
	return $result->fetch_all(MYSQLI_ASSOC);
}
// ------------------------------------------------------------------------
// Get books
function get_books($bid = NULL) {
	global $mysqli;
	
	if(!empty($bid)) {
		$get_books = "SELECT * FROM `books` WHERE `bid`='$bid'";
	} else {
		$get_books = "SELECT * FROM `books` ORDER BY `bid` DESC";
	}
	
	$result = $mysqli->query($get_books);
	return $result->fetch_all(MYSQLI_ASSOC);
}
