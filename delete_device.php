<?php
	require("connect.php");
	$pid = $_GET["id"];
	$dev = $link->query("select device_id from sites_detail where id='$pid' ");
	$did = mysqli_fetch_array($dev);
	$qry = $link->query("select device_stock from device where device_id='".$did[0]."' ");
	$rss = mysqli_fetch_array($qry);
	$stock=$rss[0]+1;

	$link->query("update device set device_stock = '$stock' where device_id='".$did[0]."'");

	$link->query("delete from sites_detail where id='$pid' ");

	echo "Success";
?>