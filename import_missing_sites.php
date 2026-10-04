<?php
if (isset($_POST['ajax_import'])) {
    require("connect.php");
    require("station_naming.php");
    header('Content-Type: application/json');
    
    // Resolve against the official barangays + placement tables
    list($by_bid, $by_key) = load_official_barangays($link);
    $pcodes = load_pcodes($link);
    $k = brgy_key($_POST['mcode'], $_POST['barangay']);
    if (empty($by_key[$k])) {
        echo json_encode(["status" => "error", "msg" => "'" . trim($_POST['barangay']) . "' is not an official barangay for " . strtoupper(trim($_POST['mcode']))]);
        exit;
    }
    $p = strtoupper(trim($_POST['place']));
    if (!isset($pcodes[$p])) {
        echo json_encode(["status" => "error", "msg" => "'" . trim($_POST['place']) . "' is not in the placement table"]);
        exit;
    }
    $official = $by_bid[$by_key[$k]];
    
    $ip = $link->real_escape_string($_POST['ip']);
    $mcode = $link->real_escape_string(strtoupper(trim($official['mcode'])));
    $barangay = $link->real_escape_string($official['barangay']);
    $bid = (int)$official['bid'];
    $place = $link->real_escape_string($pcodes[$p]);
    $inst_date = date('m/d/Y');
    
    $check = $link->query("SELECT * FROM sites WHERE ip_address LIKE '%$ip%'");
    if ($check->num_rows > 0) {
        echo json_encode(["status" => "error", "msg" => "IP already exists in database"]);
        exit;
    }
    
    $query = "INSERT INTO sites (mcode, barangay, bid, place, ip_address, inst_date, status) VALUES ('$mcode', '$barangay', $bid, '$place', '$ip', '$inst_date', 1)";
    if ($link->query($query)) {
        echo json_encode(["status" => "success", "msg" => "Imported to Database"]);
    } else {
        echo json_encode(["status" => "error", "msg" => "DB Error: " . $link->error]);
    }
    exit;
}

require("connect.php");
require("station_naming.php");
require("header.php");
require("menunav.php");

$uisp_url = "https://10.0.10.130";
$uisp_token = "32fc12c8-bf0b-4a2c-9c1d-504ad1df54c4";
$verify_ssl = false;

// Get existing IPs from DB
$db_ips = [];
$q = $link->query("SELECT ip_address FROM sites WHERE ip_address != '' AND ip_address IS NOT NULL");
while($r = $q->fetch_assoc()) {
    $ip_parts = explode('/', $r['ip_address']);
    $ip = trim($ip_parts[0]);
    if(filter_var($ip, FILTER_VALIDATE_IP)) {
        $db_ips[] = $ip;
    }
}

// Get official barangays + placement codes from DB (shared naming convention)
list($official_by_bid, $official_brgys) = load_official_barangays($link);
$official_pcodes = load_pcodes($link);
?>
<script>setActive("admin");</script>
<script>setActive("import_sites");</script>

<main class="main" style="">
    <section style="margin-top:90px;">
        <div class="container">
            <h2 class="text-primary">Import Missing Sites from UISP</h2>
            <div class="container" style="padding:20px; background:#fff; border:1px solid #ddd; border-radius:8px;">
            <p>This tool scans UISP for Station devices whose IP addresses are <b>not</b> in the Database. You can review and import them.</p>
<?php

$endpoint = rtrim($uisp_url, '/') . "/nms/api/v2.1/devices";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $endpoint);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "x-auth-token: " . $uisp_token,
    "Content-Type: application/json"
]);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $verify_ssl);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($http_code !== 200) {
    echo "<div style='color:red'>Failed to fetch UISP devices. HTTP $http_code</div>";
} else {
    $devices = json_decode($response, true);
    $missing_sites = [];
    $rejected_sites = [];

    foreach ($devices as $dev) {
        if (isset($dev['identification']['role']) && $dev['identification']['role'] === 'station') {
            if (!empty($dev['ipAddress'])) {
                $ip_parts = explode('/', $dev['ipAddress']);
                $ip = trim($ip_parts[0]);
                
                if (!in_array($ip, $db_ips)) {
                    $name = $dev['identification']['name'];
                    $parts = explode('-', $name);
                    
                    $mcode = '';
                    $barangay = '';
                    $place = '';
                    
                    $last_part = end($parts);
                    if (strtoupper(trim($last_part)) === 'STN') {
                        array_pop($parts);
                    }
                    
                    if (count($parts) >= 3) {
                        $mcode = array_shift($parts);
                        $place = array_pop($parts);
                        $barangay = implode('-', $parts);
                        
                        $mcode_upper = strtoupper(trim($mcode));
                        $brgy_upper = strtoupper(trim($barangay));
                        
                        if ($mcode_upper === 'VSAG') {
                            $rejected_sites[] = [
                                'ip' => $ip,
                                'name' => $name . " (ERROR: Use VINC instead of VSAG!)"
                            ];
                        } elseif (empty($official_brgys[brgy_key($mcode, $barangay)])) {
                            $rejected_sites[] = [
                                'ip' => $ip,
                                'name' => $name . " (ERROR: '" . trim($barangay) . "' is not an official barangay for MCODE '" . trim($mcode) . "'!)"
                            ];
                        } elseif (!isset($official_pcodes[strtoupper(trim($place))])) {
                            $rejected_sites[] = [
                                'ip' => $ip,
                                'name' => $name . " (ERROR: '" . trim($place) . "' is not a valid PCODE from the placement table!)"
                            ];
                        } else {
                            $missing_sites[] = [
                                'ip' => $ip,
                                'name' => $name,
                                'mcode' => trim($mcode),
                                'barangay' => trim($barangay),
                                'place' => trim($place)
                            ];
                        }
                    } else {
                        // STRICT ENFORCEMENT
                        $rejected_sites[] = [
                            'ip' => $ip,
                            'name' => $name
                        ];
                    }
                }
            }
        }
    }

    if (count($rejected_sites) > 0) {
        echo "<div class='alert alert-danger'><h4><i class='fa fa-exclamation-triangle'></i> STRICT NAMING POLICY ENFORCED</h4>";
        echo "<p>The following <b>" . count($rejected_sites) . "</b> devices in UISP were rejected because the technicians did not follow the exact <code>MCODE-Barangay-Place</code> naming format (must have at least 2 hyphens). They cannot be imported until their names are fixed in UISP.</p>";
        echo "<ul>";
        foreach ($rejected_sites as $rej) {
            echo "<li><b>{$rej['name']}</b> (IP: {$rej['ip']})</li>";
        }
        echo "</ul></div>";
    }

    if (count($missing_sites) > 0) {
        echo "<div class='alert alert-warning'>Found <b>" . count($missing_sites) . "</b> station devices in UISP that are not in the database.</div>";
        echo "<form id='importForm'>";
        echo "<table class='table table-bordered table-striped' id='missingTable'>";
        echo "<thead class='thead-dark'><tr><th><input type='checkbox' id='selectAll' checked></th><th>UISP Name</th><th>IP Address</th><th>MCODE</th><th>Barangay</th><th>Place</th><th>Status</th></tr></thead><tbody>";
        
        foreach ($missing_sites as $i => $m) {
            echo "<tr id='row-$i'>";
            echo "<td><input type='checkbox' class='site-chk' data-index='$i' checked></td>";
            echo "<td>{$m['name']}</td>";
            echo "<td><input type='text' id='ip-$i' value='{$m['ip']}' class='form-control form-control-sm' readonly></td>";
            echo "<td><input type='text' id='mcode-$i' value='{$m['mcode']}' class='form-control form-control-sm'></td>";
            echo "<td><input type='text' id='barangay-$i' value='{$m['barangay']}' class='form-control form-control-sm'></td>";
            echo "<td><input type='text' id='place-$i' value='{$m['place']}' class='form-control form-control-sm'></td>";
            echo "<td id='status-$i'>Pending</td>";
            echo "</tr>";
        }
        
        echo "</tbody></table>";
        echo "<button type='button' id='btnImport' class='btn btn-success'>Import Selected to Database</button>";
        echo "</form>";
?>
<script>
document.getElementById('selectAll').addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('.site-chk');
    for (let chk of checkboxes) {
        chk.checked = this.checked;
    }
});

document.getElementById('btnImport').addEventListener('click', async function() {
    const checkboxes = document.querySelectorAll('.site-chk:checked');
    if (checkboxes.length === 0) {
        alert('Please select at least one site to import.');
        return;
    }
    
    if (!confirm('Import ' + checkboxes.length + ' sites to the database?')) return;
    
    this.disabled = true;
    
    for (let chk of checkboxes) {
        let i = chk.getAttribute('data-index');
        let statusCell = document.getElementById('status-' + i);
        statusCell.innerHTML = '<span style="color:orange;">Importing...</span>';
        
        let ip = document.getElementById('ip-' + i).value;
        let mcode = document.getElementById('mcode-' + i).value;
        let barangay = document.getElementById('barangay-' + i).value;
        let place = document.getElementById('place-' + i).value;
        
        try {
            let formData = new FormData();
            formData.append('ajax_import', '1');
            formData.append('ip', ip);
            formData.append('mcode', mcode);
            formData.append('barangay', barangay);
            formData.append('place', place);
            
            let response = await fetch('', {
                method: 'POST',
                body: formData
            });
            let result = await response.json();
            
            if (result.status === 'success') {
                statusCell.innerHTML = '<span style="color:green;">Success</span>';
                chk.checked = false;
            } else {
                statusCell.innerHTML = '<span style="color:red;">Failed: ' + result.msg + '</span>';
            }
        } catch(err) {
            statusCell.innerHTML = '<span style="color:red;">Error</span>';
        }
    }
    
    alert('Import process finished!');
    this.disabled = false;
});
</script>
<?php
    } else {
        echo "<div class='alert alert-success'>No missing sites found. All UISP stations are tracked in the database!</div>";
    }
}
?>
            </div>
        </div>
    </section>
</main>
<?php require("footer.php"); ?>


