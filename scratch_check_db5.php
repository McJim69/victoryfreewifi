<?php
require('connect.php');
$res = $link->query("SELECT * FROM sites WHERE inst_date = '".date('m/d/Y')."'");
while($r = $res->fetch_assoc()){
    print_r($r);
}
