<?php
require 'connect.php';
$q = mysqli_query($link, "SELECT * FROM admin");
$res = [];
while ($r = mysqli_fetch_assoc($q)) {
    $res[] = $r;
}
echo json_encode($res, JSON_PRETTY_PRINT);
?>
