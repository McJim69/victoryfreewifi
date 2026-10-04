<?php
require('connect.php');
$q = "UPDATE sites SET mcode='AURO', barangay='Bagong Maslog', place='BAP' WHERE sid=829";
if (!$link->query($q)) {
    echo "Error updating: " . $link->error;
} else {
    echo "Updated successfully!\n";
    $res = $link->query("SELECT * FROM sites WHERE sid=829");
    $row = $res->fetch_assoc();
    echo "After update: " . $row['barangay'];
}
?>
