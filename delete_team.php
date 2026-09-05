<?php
	require("connect.php");
	$link->query("delete from org_structure where orgs_oid='".$_GET["orgs_oid"]."'");
	echo "Success";
?>