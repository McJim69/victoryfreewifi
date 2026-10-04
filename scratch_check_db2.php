<?php
require('connect.php');
$res = $link->query("SELECT * FROM barangays WHERE barangay LIKE 'AURO%'");
while($r = $res->fetch_assoc()){
    print_r($r);
}
