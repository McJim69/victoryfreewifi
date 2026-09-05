<?php
	require("connect.php");
	$link->query("delete from category where cat_id='".$_GET["cat_id"]."'");
	echo "Success";
?>