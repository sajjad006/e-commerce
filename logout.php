<?php
	session_start();
	session_unset();
	session_destroy();
	$url=$_SERVER['HTTP_REFERER'];
	if (strpos($url, "account") || strpos($url, "orderdetails") || strpos($url, "cart") || strpos($url, "checkout")) {
		header("Location:home.php");
	}
	else{
		header("Location:".$url);
	}
	$login=false;
?>