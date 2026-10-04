<?php
require("connect.php");
$res = mysqli_query($link, "SELECT ip, name, mcode, barangay, place FROM sites WHERE barangay = name AND (mcode = '' OR mcode IS NULL)");
if (!$res) { echo "SQL ERROR: " . mysqli_error($link) . "\n"; }
while($row = mysqli_fetch_assoc($res)) {
    echo "MALFORMED: " . $row['name'] . "\n";
}
?>
