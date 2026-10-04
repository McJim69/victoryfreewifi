<?php
require('connect.php');
$tables_res = $link->query("SHOW TABLES");
while($table_row = $tables_res->fetch_array()) {
    $table = $table_row[0];
    $cols_res = $link->query("SHOW COLUMNS FROM $table");
    while($col_row = $cols_res->fetch_assoc()) {
        $col = $col_row['Field'];
        // skip some obvious non-string columns
        if (strpos($col_row['Type'], 'varchar') !== false || strpos($col_row['Type'], 'text') !== false) {
            $q = "SELECT * FROM $table WHERE $col LIKE '%AURO-Bagong%' LIMIT 1";
            $check = $link->query($q);
            if ($check && $check->num_rows > 0) {
                echo "FOUND IN TABLE: $table, COLUMN: $col\n";
            }
        }
    }
}
echo "Done searching DB.\n";
?>
