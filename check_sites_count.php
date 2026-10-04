<?php
require('connect.php');
$res = $link->query("SELECT * FROM sites WHERE barangay LIKE '%-%'");
echo "Count now: " . $res->num_rows . "\n";
?>
