<?php
require('connect.php');
$res = $link->query("SELECT * FROM sites LIMIT 5");
while($r = $res->fetch_assoc()) { print_r($r); }
