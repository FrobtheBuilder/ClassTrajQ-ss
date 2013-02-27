<?php
	session_start();
	include "callback.php";
	$var = json_decode(stripslashes($_GET["clientclasses"]));
	$thefile = fopen($_SESSION['workingdir']."/c.json", "w+");
	fwrite($thefile, json_encode($var));

    header('Content-Type: text/javascript');
    echo jsonp(json_encode($var), $_GET['callback']);
?>