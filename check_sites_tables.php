<?php
require('connect.php');
$res = $link->query("SHOW TABLES LIKE '%site%'");
while($row = $res->fetch_array()) {
    echo $row[0] . "\n";
}
?>
