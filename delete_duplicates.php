<?php
require('connect.php');
$q = "DELETE FROM sites WHERE barangay LIKE '%-%' OR barangay LIKE '%SAN MIGUEL%'";
if (!$link->query($q)) {
    echo "Error deleting: " . $link->error;
} else {
    echo "Deleted " . $link->affected_rows . " malformed duplicate sites!\n";
}
?>
