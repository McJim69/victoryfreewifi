<?php
require('connect.php');
$res = $link->query("SHOW COLUMNS FROM sites_detail");
while($row = $res->fetch_assoc()) {
    echo $row['Field'] . " ";
}
?>
