<?php
require('connect.php');
$res = $link->query("SELECT * FROM barangays WHERE barangay LIKE '%-BAP%' OR barangay LIKE '%-ELS%' OR barangay LIKE '%ZDSPGC%' OR barangay LIKE '%SAN MIGUEL%' OR barangay LIKE '%-%'");
while($row = $res->fetch_assoc()) {
    echo "ID: " . $row['bid'] . " | Barangay: " . $row['barangay'] . " | MCODE: " . $row['mcode'] . "\n";
}
?>
