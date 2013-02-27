<?php

session_start();
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST");
header("Content-Type: text/javascript");

switch ($_GET['action']) {

	case "read":
		echo jsonp(read());
		break;

	case "write":
		echo jsonp(write());
		break;

	case "login":
		echo jsonp(login());
		break;

	case "logout":
		echo jsonp(logout());
		break;

	case "testlogin":
		echo jsonp(testlogin());
		break;

	case "dump":
		dump();
		break;
}

function write() {
	$var = json_decode(stripslashes($_GET["clientclasses"]));
	if ($var !== file_get_contents($_SESSION['file'])) {
		$thefile = fopen($_SESSION['file'], "w+");
		$return = fwrite($thefile, json_encode($var));
		$result = array("return" => $return);
	}
	return '{"return":'.$return.'}';
}

function read() {
	$thefile = fopen($_SESSION['file'], "r+");
	$content = fread($thefile, filesize($_SESSION['file']));
	return $content;
}

function login() {
	$user = filter_var($_GET['user'], FILTER_SANITIZE_STRING);
	$directory = "../ClassTrajQ-profiles/" . $user;
	$passfile = $directory."/p.txt";
	if (!is_dir($directory)) {
		mkdir("../ClassTrajQ-profiles/" . $user);
	}
	$_SESSION['workingdir'] = $directory;
	$_SESSION['file'] = $directory."/c.json";
	$_SESSION['user'] = $user;
	if (file_exists($passfile)) {
		if (file_get_contents($passfile) === $_GET["password"]) {
			$response = array(success => true, "user" => $_SESSION['user'], "session" => session_id());
		}
		else {
			$response = array(success => false, "user" => "auth failure", "session" => session_id());
			session_unset();
		}
	}
	else {
		$file = fopen($passfile, 'w+');
		fwrite($file, $_GET['password']);
		$response = array(success => true, "user" => $_SESSION['user'], "session" => session_id());
	}
	return json_encode($response);
}

function logout() {
	session_unset();
	return testlogin();
}

function testlogin() {
	if (isset($_SESSION['user'])) {
		$returned = array("loggedin" => true, "user" => $_SESSION['user'], "session" => session_id());
		return json_encode($returned);
	}
	else {
		$returned = array("loggedin" => false, "user" => "", "session" => "");
		return json_encode($returned);
	}
}

function dump() {
	$returned = array("user" => $_SESSION['user'],
	 					"working directory" => $_SESSION['workingdir'],
	  					"working file" => $_SESSION['file']);

	echo jsonp(stripslashes(json_encode($returned)));
}

function jsonp($stuff) {
	$callback = filter_var($_GET['callback'], FILTER_SANITIZE_STRING);
	return $callback.'('.$stuff.');';
}
?>