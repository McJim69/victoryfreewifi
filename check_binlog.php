<?php
require('connect.php');
$res = $link->query("SHOW VARIABLES LIKE 'log_bin'");
$row = $res->fetch_assoc();
echo "Binlog: " . $row['Value'] . "\n";
?>
