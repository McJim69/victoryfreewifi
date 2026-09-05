<?php	
	require("connect.php");
	
	$bsid = $_GET["bst_id"];

	$link->query("delete from base_stations where bst_id='$bsid' ");

	echo "Success";
?>