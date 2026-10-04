<?php
require('connect.php');
$res = $link->query("SELECT * FROM sites WHERE sid=829");
if ($res && $res->num_rows > 0) {
    echo "ID 829 exists!\n";
} else {
    echo "ID 829 does NOT exist.\n";
}
?>
