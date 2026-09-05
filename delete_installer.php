<?php
	require("connect.php");
	$link->query("delete from installer where tid='".$_GET["tid"]."'");
	echo "Success";
?>