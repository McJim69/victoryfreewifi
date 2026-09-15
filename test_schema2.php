<?php
require("connect.php");
$res = $link->query("SHOW COLUMNS FROM base_stations");
if(!$res) die($link->error);
while($r = $res->fetch_assoc()) {
    echo $r["Field"] . "\n";
}
?>
