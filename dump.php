<?php require("connect.php"); if($link->query("ALTER TABLE sites_detail ADD COLUMN device_category VARCHAR(100) AFTER device_code")) echo "Success!"; else echo $link->error; ?>
