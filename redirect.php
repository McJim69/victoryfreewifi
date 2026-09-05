<?php

	$wan="180.193.203.18";
	$lan="10.0.10.1";

	if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
		$ip = $_SERVER['HTTP_CLIENT_IP'];
	} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	} else {
		$ip = $_SERVER['REMOTE_ADDR'];
	}
	
	require("connect.php");	

	$ex = $link->query("select * from servers");
	while ($rs = mysqli_fetch_array($ex)){
		$prot = $rs["protocol"];
		$lans = $rs["localhost"];
		$webs = $rs["cloudhost"];	
		$subs = $rs["subfolder"];
	
		if (($ip)!==$wan || ($ip)!==$lan){
			header("location:$prot$lans$subs");
		}else{
			header("location:$prot$webs$subs");
		}
	}
?> 