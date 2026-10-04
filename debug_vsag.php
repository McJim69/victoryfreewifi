<?php
require("connect.php");
$res = mysqli_query($link, "SELECT name, mcode, barangay, place FROM sites WHERE name LIKE '%VSAG%' OR name LIKE '%VINC%'");
while($row = mysqli_fetch_assoc($res)) {
    echo $row['name'] . " | " . $row['mcode'] . " | " . $row['barangay'] . "\n";
}
?>
