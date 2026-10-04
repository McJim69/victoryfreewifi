<?php
require 'connect.php';
$sql = "UPDATE admin SET link3 = 'href=\"fix_barangay_admin.php\">Fix Site Data' WHERE admid = 4";
if (mysqli_query($link, $sql)) {
    echo "Updated link3 successfully.";
} else {
    echo "Error: " . mysqli_error($link);
}
?>
