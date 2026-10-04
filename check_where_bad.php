<?php
require('connect.php');
$res = $link->query("SELECT * FROM barangays WHERE barangay LIKE '%AURO-Bagong%'");
while($row = $res->fetch_assoc()) {
    echo "IN BARANGAYS TABLE: ID: " . $row['bid'] . " | Barangay: " . $row['barangay'] . "\n";
}
$res = $link->query("SELECT * FROM sites WHERE barangay LIKE '%AURO-Bagong%'");
while($row = $res->fetch_assoc()) {
    echo "IN SITES TABLE: ID: " . $row['sid'] . " | Barangay: " . $row['barangay'] . "\n";
}
?>
