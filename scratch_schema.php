<?php
require('connect.php');
$res = $link->query("SHOW CREATE TABLE sites");
$row = $res->fetch_row();
echo $row[1];
