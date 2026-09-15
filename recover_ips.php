<?php
// Increase timeout for API fetching
ini_set('max_execution_time', 600);

require("connect.php");
require("header.php");
require("menunav.php");

// ==========================================
// UISP API CONFIGURATION
// ==========================================
$uisp_url = "https://10.0.10.130";
$uisp_token = "32fc12c8-bf0b-4a2c-9c1d-504ad1df54c4";
$verify_ssl = false; 
// ==========================================

?>
<main class="main" style="min-height:614px;">
    <section style="margin-top:90px;">
        <div class="container">
            <h2 class="text-success">UISP IP Address Recovery Tool</h2>
            <div class="container" style="padding:20px; background:#fff; border:1px solid #ddd; border-radius:8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                <p class="text-secondary">This script scans UISP and attempts to match devices by name to recover missing IP addresses in your database.</p>
<?php

// 1. Fetch devices from UISP
$endpoint = rtrim($uisp_url, '/') . "/nms/api/v2.1/devices";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $endpoint);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "x-auth-token: " . $uisp_token,
    "Content-Type: application/json"
]);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $verify_ssl);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, $verify_ssl ? 2 : 0);

$response = curl_exec($ch);
if(curl_errno($ch)){
    die("<div style='color:red'><b>cURL Error:</b> " . curl_error($ch) . "</div>");
}
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($http_code !== 200) {
    die("<div style='color:red'><b>API Error:</b> Received HTTP Status Code {$http_code}. Response: " . htmlspecialchars($response) . "</div>");
}

$uisp_devices = json_decode($response, true);
if (!is_array($uisp_devices)) {
    die("<div style='color:red'><b>Data Error:</b> Failed to parse JSON from UISP.</div>");
}

echo "Successfully retrieved <b>" . count($uisp_devices) . "</b> devices from UISP.<br><br>";

// 2. Query local database
$result = $link->query("SELECT sid, ip_address, mcode, barangay FROM sites");
if (!$result) {
    die("Query failed: " . $link->error);
}

$recovered_count = 0;
$already_valid_count = 0;
$not_found_count = 0;

echo "<b>Scanning local sites and recovering IPs:</b><br>";
echo "<div style='font-family: monospace; background: #222; color: #fff; padding: 10px; height: 400px; overflow-y: scroll; border-radius: 5px; margin-top: 10px;'>";

while ($site = $result->fetch_assoc()) {
    $sid = (int)$site['sid'];
    $current_ip = $site['ip_address'];
    $db_name = trim($site['mcode'] . " - " . $site['barangay']);
    
    // Clean name for matching
    $search_name = strtolower(str_replace(' ', '', $db_name));
    
    $match_found = false;
    $new_ip = "";

    // Search through UISP devices for a name match
    foreach ($uisp_devices as $dev) {
        $dev_name = isset($dev['identification']['name']) ? strtolower(str_replace(' ', '', $dev['identification']['name'])) : '';
        $site_name = isset($dev['identification']['site']['name']) ? strtolower(str_replace(' ', '', $dev['identification']['site']['name'])) : '';
        
        // Exact Match or Substring Match (e.g. "AURO-Acad" inside "AURO-Acad-AP")
        if (
            $search_name === $dev_name || 
            $search_name === $site_name ||
            (!empty($search_name) && strpos($dev_name, $search_name) !== false) ||
            (!empty($search_name) && strpos($site_name, $search_name) !== false)
        ) {
            if (!empty($dev['ipAddress'])) {
                $ip_parts = explode('/', $dev['ipAddress']);
                $new_ip = trim($ip_parts[0]);
                $match_found = true;
                break; // Stop searching once we find a match
            }
        }
    }

    if ($match_found && filter_var($new_ip, FILTER_VALIDATE_IP)) {
        if ($current_ip === $new_ip) {
            echo "<span style='color: #0f0'>[VERIFIED]</span> Site #$sid ($db_name) already has correct IP: $current_ip<br>";
            $already_valid_count++;
        } else {
            // Update the database
            $stmt = $link->prepare("UPDATE sites SET ip_address = ? WHERE sid = ?");
            $stmt->bind_param("si", $new_ip, $sid);
            if ($stmt->execute()) {
                echo "<span style='color: #0ff'>[RECOVERED]</span> Site #$sid ($db_name) -> <b>$new_ip</b><br>";
                $recovered_count++;
            } else {
                echo "<span style='color: #f00'>[ERROR]</span> Failed to update Site #$sid ($db_name): " . $link->error . "<br>";
            }
            $stmt->close();
        }
    } else {
        $not_found_count++;
        echo "<span style='color: #ffaa00'>[NO MATCH]</span> Site #$sid ($db_name) - Could not find matching name in UISP.<br>";
    }
}
echo "</div>";

// Summary
echo "<h3>Recovery Complete for Sites</h3>";
echo "<ul>";
echo "<li>IP Addresses Recovered & Updated: <b style='color:#007bff; font-size: 1.2rem'>$recovered_count</b></li>";
echo "<li>Sites that already had correct IP: <b style='color:green'>$already_valid_count</b></li>";
echo "<li>Sites unable to be matched by name: <b style='color:#ffaa00'>$not_found_count</b></li>";
echo "</ul>";

// ==========================================
// RECOVER BASE STATIONS
// ==========================================

$result_bs = $link->query("SELECT bst_id, ip_address, station_name FROM base_stations");
if ($result_bs) {
    $bs_recovered = 0;
    $bs_valid = 0;
    $bs_not_found = 0;

    echo "<br><b>Scanning Base Stations and recovering IPs:</b><br>";
    echo "<div style='font-family: monospace; background: #222; color: #fff; padding: 10px; height: 300px; overflow-y: scroll; border-radius: 5px; margin-top: 10px;'>";

    while ($bs = $result_bs->fetch_assoc()) {
        $bs_id = (int)$bs['bst_id'];
        $current_ip = $bs['ip_address'];
        $db_name = trim($bs['station_name']);
        
        // Clean name for matching
        $search_name = strtolower(str_replace(' ', '', $db_name));
        
        $match_found = false;
        $new_ip = "";

        // Search through UISP devices for a name match
        foreach ($uisp_devices as $dev) {
            $dev_name = isset($dev['identification']['name']) ? strtolower(str_replace(' ', '', $dev['identification']['name'])) : '';
            $site_name = isset($dev['identification']['site']['name']) ? strtolower(str_replace(' ', '', $dev['identification']['site']['name'])) : '';
            
            // Exact Match or Substring Match
            if (
                $search_name === $dev_name || 
                $search_name === $site_name ||
                (!empty($search_name) && strpos($dev_name, $search_name) !== false) ||
                (!empty($search_name) && strpos($site_name, $search_name) !== false)
            ) {
                if (!empty($dev['ipAddress'])) {
                    $ip_parts = explode('/', $dev['ipAddress']);
                    $new_ip = trim($ip_parts[0]);
                    $match_found = true;
                    break;
                }
            }
        }

        if ($match_found && filter_var($new_ip, FILTER_VALIDATE_IP)) {
            if ($current_ip === $new_ip) {
                echo "<span style='color: #0f0'>[VERIFIED]</span> Base Station #$bs_id ($db_name) already has correct IP: $current_ip<br>";
                $bs_valid++;
            } else {
                // Update the database
                $stmt = $link->prepare("UPDATE base_stations SET ip_address = ? WHERE bst_id = ?");
                $stmt->bind_param("si", $new_ip, $bs_id);
                if ($stmt->execute()) {
                    echo "<span style='color: #0ff'>[RECOVERED]</span> Base Station #$bs_id ($db_name) -> <b>$new_ip</b><br>";
                    $bs_recovered++;
                } else {
                    echo "<span style='color: #f00'>[ERROR]</span> Failed to update Base Station #$bs_id ($db_name): " . $link->error . "<br>";
                }
                $stmt->close();
            }
        } else {
            $bs_not_found++;
            echo "<span style='color: #ffaa00'>[NO MATCH]</span> Base Station #$bs_id ($db_name) - Could not find matching name in UISP.<br>";
        }
    }
    echo "</div>";

    echo "<h3>Recovery Complete for Base Stations</h3>";
    echo "<ul>";
    echo "<li>IP Addresses Recovered & Updated: <b style='color:#007bff; font-size: 1.2rem'>$bs_recovered</b></li>";
    echo "<li>Base Stations that already had correct IP: <b style='color:green'>$bs_valid</b></li>";
    echo "<li>Base Stations unable to be matched: <b style='color:#ffaa00'>$bs_not_found</b></li>";
    echo "</ul>";
}

echo "<br><p class='text-success' style='font-weight: bold;'><i>You can now run <b>sync_uisp.php</b> again to sync the statuses of all these newly recovered IPs!</i></p>";
echo "</div>
        </div>
    </section>
</main>";
?>
<?php require("footer.php");?>
