<?php
require('connect.php');
$res = $link->query("SELECT sid, mcode, barangay, place, ip_address FROM sites WHERE barangay LIKE 'AURO%'");
while($r = $res->fetch_assoc()){
    print_r($r);
}
