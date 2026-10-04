<?php
require('connect.php');
$res = $link->query("SELECT DISTINCT barangay FROM sites");
while($r = $res->fetch_assoc()){
    echo $r['barangay'] . "\n";
}
