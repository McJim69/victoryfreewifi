<?php
require('connect.php');
$res = $link->query("SELECT * FROM sites WHERE barangay LIKE '%AURO%'");
while($r = $res->fetch_assoc()){
    print_r($r);
}
