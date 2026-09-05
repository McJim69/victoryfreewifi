<?php
	require("connect.php");
	$link->query("delete from device where device_id='".$_GET["device_id"]."'");
	echo "Success";
?>