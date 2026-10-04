<?php
require('connect.php');
$res = $link->query("SELECT DISTINCT barangay FROM barangays WHERE barangay LIKE '%-%'");
while($r = $res->fetch_assoc()){
    echo "from barangays: ".$r['barangay']."\n";
}
$res = $link->query("SELECT DISTINCT barangay FROM sites WHERE barangay LIKE '%-%'");
while($r = $res->fetch_assoc()){
    echo "from sites: ".$r['barangay']."\n";
}
