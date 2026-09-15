<?php
require("connect.php");

$sql = "
DROP TABLE IF EXISTS base_stations;
CREATE TABLE base_stations (
  bst_id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  stn_id int(11) NOT NULL,
  station_name varchar(50) NOT NULL,
  ip_address varchar(50) NULL,
  coordinates varchar(20) DEFAULT NULL,
  tower_height varchar(10) DEFAULT NULL,
  elevation varchar(10) DEFAULT NULL
);
";

if ($link->multi_query($sql)) {
    echo "Success: Schema updated.\n";
} else {
    echo "Error: " . $link->error . "\n";
}
?>
