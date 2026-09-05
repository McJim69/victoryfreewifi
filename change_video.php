<?php
	require("connect.php");
	$link->query("update video_link set source='".$_GET["source"]."' where vid='".$_GET["vid"]."'") or die (mysqli_error($link));
?>