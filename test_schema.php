<?php
require("connect.php");
$res = $link->query("SHOW COLUMNS FROM sites");
if(!$res) die($link->error);
while($r = $res->fetch_assoc()) {
    echo $r["Field"] . "\n";
}
?>
