<?php
$file = 'import_missing_sites.php';
$content = file_get_contents($file);

$search = '/foreach \(\ as \\) \{.*?if \(count\(\\) > 0\) \{/s';

$replace = <<<'REPLACE'
     = [];

    foreach ( as ) {
        if (isset(['identification']['role']) && ['identification']['role'] === 'station') {
            if (!empty(['ipAddress'])) {
                 = explode('/', ['ipAddress']);
                 = trim([0]);
                
                if (!in_array(, )) {
                     = ['identification']['name'];
                     = explode('-', );
                    
                     = '';
                     = '';
                     = '';
                    
                     = end();
                    if (strtoupper(trim()) === 'STN') {
                        array_pop();
                    }
                    
                    if (count() >= 3) {
                         = array_shift();
                         = array_pop();
                         = implode('-', );
                        
                        [] = [
                            'ip' => ,
                            'name' => ,
                            'mcode' => trim(),
                            'barangay' => trim(),
                            'place' => trim()
                        ];
                    } else {
                        // STRICT ENFORCEMENT: Reject if it doesn't have at least 3 parts (MCODE-Barangay-Place)
                        [] = [
                            'ip' => ,
                            'name' => 
                        ];
                    }
                }
            }
        }
    }

    if (count() > 0) {
        echo "<div class='alert alert-danger'><h4><i class='fa fa-exclamation-triangle'></i> STRICT NAMING POLICY ENFORCED</h4>";
        echo "<p>The following <b>" . count() . "</b> devices in UISP were rejected because the technicians did not follow the exact <code>MCODE-Barangay-Place</code> naming format. They cannot be imported until their names are fixed in UISP.</p>";
        echo "<ul>";
        foreach ( as ) {
            echo "<li><b>{['name']}</b> (IP: {['ip']})</li>";
        }
        echo "</ul></div>";
    }

    if (count() > 0) {
REPLACE;

$new_content = preg_replace($search, $replace, $content);
if ($new_content !== null && $new_content !== $content) {
    file_put_contents($file, $new_content);
    echo "Successfully updated import_missing_sites.php!";
} else {
    echo "Regex failed or no changes made.";
}
?>
