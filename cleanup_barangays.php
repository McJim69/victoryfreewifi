<?php
require('connect.php');
require('header.php');
require('menunav.php');
echo"<div class='container main'><br><br><br><br><br><br>";

echo "<h2>Cleanup Malformed Barangays</h2>";
$res = $link->query("SELECT * FROM sites");
$fixed = 0;

// Load valid mcodes for safer matching
$mcodes_query = $link->query("SELECT DISTINCT mcode FROM barangays");
$valid_mcodes = [];
while ($m_row = $mcodes_query->fetch_assoc()) {
    if (trim($m_row['mcode']) !== '') {
        $valid_mcodes[] = strtoupper(trim($m_row['mcode']));
    }
}

while($row = $res->fetch_assoc()) {
    $barangay = trim($row['barangay']);
    if ($barangay === '') continue;
    
    $parts = explode('-', $barangay);
    
    // If the barangay field looks like a full device name (e.g., AURO-Bagong Maslog-BAP)
    if (count($parts) >= 3) {
        $last_part = end($parts);
        if (strtoupper($last_part) === 'STN') {
            array_pop($parts);
        }
        
        // The first part must be a valid MCODE
        if (count($parts) >= 3 && in_array(strtoupper(trim($parts[0])), $valid_mcodes)) {
            $mcode = $link->real_escape_string(array_shift($parts));
            $place = $link->real_escape_string(array_pop($parts));
            $correct_barangay = $link->real_escape_string(implode('-', $parts));
            
            $sid = (int)$row['sid'];
            
            // Check for collision to avoid unique key error
            $check = $link->query("SELECT sid FROM sites WHERE mcode='$mcode' AND barangay='$correct_barangay' AND place='$place' AND sid != $sid");
            if ($check && $check->num_rows > 0) {
                echo "<span style='color:orange;'>Collision skipped for SID $sid: <b>$barangay</b> (A site with MCODE: $mcode, Brgy: $correct_barangay, Place: $place already exists)</span><br>";
                continue;
            }
            
            $query = "UPDATE sites SET mcode='$mcode', place='$place', barangay='$correct_barangay' WHERE sid=$sid";
            if($link->query($query)) {
                echo "Fixed SID $sid: <b>$barangay</b> => Corrected Barangay: <b>$correct_barangay</b> (MCODE: $mcode, Place: $place)<br>";
                $fixed++;
            } else {
                echo "Error updating SID $sid: " . $link->error . "<br>";
            }
        }
    }
}

if ($fixed === 0) {
    echo "<p>No malformed barangays found in the database. Everything looks clean!</p>";
} else {
    echo "<p><b>Successfully fixed $fixed records!</b></p>";
}
echo"</div>";
include 'footer.php';
?>
