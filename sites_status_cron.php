<?php
	ini_set('max_execution_time', 300); // 300 seconds = 5 minutes
	require("connect.php");

	$port = 443;
	$timeout = 1.0; // must be float

	$result = $link->query("SELECT sid, ip_address FROM sites");
	if (!$result) {
		die("Query failed: " . $link->error);
	}

	while ($site = $result->fetch_assoc()) {
		$host = (!empty($site["ip_address"]) && filter_var($site["ip_address"], FILTER_VALIDATE_IP)) 
			? $site["ip_address"] 
			: "0.0.0.0";

		$status = 0;
		$connection = @fsockopen($host, $port, $errCode, $errStr, $timeout);
		if ($connection) {
			$status = 1;
			fclose($connection);
		}

		$sid = isset($site["sid"]) ? (int)$site["sid"] : 0;
		if ($sid > 0) {
			if (!$link->query("UPDATE sites SET status = $status WHERE sid = $sid")) {
				error_log("Update failed for SID $sid: " . $link->error);
			}
		}
	}
?>
