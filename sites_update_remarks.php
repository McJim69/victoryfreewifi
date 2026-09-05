<?php
	require("connect.php");
	$link->query("update sites set remarks='".$_GET["remarks"]."' where sid='".$_GET["sid"]."'") or die (mysqli_error($link));
	
	$ex=$link->query("select * from sites where sid='".$_GET["sid"]."'");
	
	while($rs=$ex->fetch_array()){
		if($rs["remarks"]=="null"){
			$link->query("update sites set remarks='' where sid='".$_GET["sid"]."'");
		}
	}
?>