<?php
	session_start();
	include "callback.php";
	$thefile = fopen($_SESSION['workingdir']."/c.json", "r+");
	$content = fread($thefile, filesize($_SESSION['workingdir']."/c.json"));
	$json = json_decode($content);
	$response = json_encode($json);
	header('Content-Type: text/javascript');
	echo jsonp($content, $_GET['callback']);
?>