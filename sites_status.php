<?php
	// Get count of active sites
	$qryACT = $link->query("SELECT COUNT(*) AS total FROM sites WHERE status = 1") or die(mysqli_error($link));
	$aryACT = mysqli_fetch_assoc($qryACT);
	$rawACT = (int)$aryACT['total'];
	$totACT = number_format($rawACT);
	$active = "<b style='font-family:arial;color:green'>$totACT</b>";

	// Get count of downed sites
	$qryDWN = $link->query("SELECT COUNT(*) AS total FROM sites WHERE status = 0") or die(mysqli_error($link));
	$aryDWN = mysqli_fetch_assoc($qryDWN);
	$rawDWN = (int)$aryDWN['total'];
	$totDWN = number_format($rawDWN);
	$downed = "<b style='font-family:arial;color:red'>$totDWN</b>";
?>
