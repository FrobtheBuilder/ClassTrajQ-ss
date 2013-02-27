<?php


function jsonp($stuff, $callback) {
	if (isset($callback)){
		$callback = filter_var($_GET['callback'], FILTER_SANITIZE_STRING);
	}
	return $callback.'('.$stuff.');';
}
?>