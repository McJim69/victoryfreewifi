<?php
require 'connect.php';

// Revert admid = 4
mysqli_query($link, "UPDATE admin SET link3 = 'href=\"barangays.php\" >Edit Barangay' WHERE admid = 4");

// Delete any existing added rows to avoid duplicates if rerun
mysqli_query($link, "DELETE FROM admin WHERE title IN ('UISP Tools', 'Data Cleanup')");

// Insert UISP Tools
$sql1 = "INSERT INTO admin (title, link0, image, link1, link2, link3) VALUES (
    'UISP Tools',
    'href=\"#\"',
    'assets/img/admin/device.png',
    'href=\"sync_uisp.php\">Sync UISP',
    'href=\"recover_ips.php\">Recover IPs',
    'href=\"rename_uisp_stations.php\">Device Names'
)";
mysqli_query($link, $sql1);

// Insert Data Cleanup
$sql2 = "INSERT INTO admin (title, link0, image, link1, link2, link3) VALUES (
    'Data Cleanup',
    'href=\"#\"',
    'assets/img/admin/backup.png',
    'href=\"import_missing_sites.php\">Fix Missing Sites',
    'href=\"cleanup_barangays.php\">Clean Malformed',
    'href=\"fix_barangay_admin.php\">Fix Barangay Admin'
)";
mysqli_query($link, $sql2);

echo "Inserted new cards successfully.";
?>
