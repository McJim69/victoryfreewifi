<?php
// Increase timeout for API fetching
ini_set('max_execution_time', 300);

require("connect.php");
require("header.php");
require("menunav.php");

// ==========================================
// UISP API CONFIGURATION
// ==========================================
$uisp_url = "https://10.0.10.130";
$uisp_token = "32fc12c8-bf0b-4a2c-9c1d-504ad1df54c4";

// Bypass SSL verification if you are using self-signed certificates on your UISP server
$verify_ssl = false; 
// ==========================================

?>
<main class="main" style="min-height:614px;">
    <section style="margin-top:90px;">
        <div class="container">
            <h2 class="text-success">UISP Synchronization Log</h2>
            <div class="container" style="padding:20px; background:#fff; border:1px solid #ddd; border-radius:8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
<?php

if ($uisp_token === "PASTE_YOUR_X_AUTH_TOKEN_HERE" || empty($uisp_token)) {
    die("<div style='color:red; font-size:18px;'><b>Error:</b> Please edit this file (<code>sync_uisp.php</code>) and paste your UISP API Token and URL in the configuration section at the top.</div>");
}

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

echo "Connecting to UISP API at <i>{$endpoint}</i>...<br>";

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

// 2. Map UISP devices by IP address for fast lookup
$uisp_map = [];
foreach ($uisp_devices as $dev) {
    if (!empty($dev['ipAddress'])) {
        // Handle cases where UISP might return IP with subnet mask (e.g. 192.168.1.20/24)
        $ip_parts = explode('/', $dev['ipAddress']);
        $clean_ip = $ip_parts[0];
        
        $status_text = isset($dev['overview']['status']) ? strtolower($dev['overview']['status']) : 'unknown';
        
        // UISP usually uses "active" for online, "disconnected" / "unreachable" for offline
        $is_online = ($status_text === 'active') ? 1 : 0;
        
        $uisp_map[$clean_ip] = $is_online;
    }
}

// 3. Query local database and update
$result = $link->query("SELECT sid, ip_address, mcode, barangay FROM sites");
if (!$result) {
    die("Query failed: " . $link->error);
}

$updated_count = 0;
$offline_count = 0;
$online_count = 0;
$not_found_count = 0;

echo "<b>Syncing local sites:</b><br>";
echo "<div style='font-family: monospace; background: #222; color: #0f0; padding: 10px; height: 350px; overflow-y: scroll; border-radius: 5px; margin-top: 10px;'>";

while ($site = $result->fetch_assoc()) {
    $sid = (int)$site['sid'];
    $ip = $site['ip_address'];
    $name = $site['mcode'] . " - " . $site['barangay'];
    
    if (empty($ip) || !filter_var($ip, FILTER_VALIDATE_IP)) {
        // Mark as offline if IP is invalid
        $link->query("UPDATE sites SET status = 0 WHERE sid = $sid");
        $offline_count++;
        echo "<span style='color: #ffaa00'>[SKIPPED & OFFLINE]</span> Site #$sid ($name) - Invalid or missing IP: $ip<br>";
        continue;
    }

    if (isset($uisp_map[$ip])) {
        $new_status = $uisp_map[$ip];
        
        // Update database
        $update_query = "UPDATE sites SET status = $new_status WHERE sid = $sid";
        if ($link->query($update_query)) {
            $updated_count++;
            if ($new_status === 1) {
                $online_count++;
                echo "[SYNCED] Site #$sid ($name) -> <span style='color: #fff'>ONLINE</span><br>";
            } else {
                $offline_count++;
                echo "[SYNCED] Site #$sid ($name) -> <span style='color: #f00'>OFFLINE</span><br>";
            }
        } else {
            echo "<span style='color: #f00'>[ERROR]</span> Failed to update Site #$sid ($name): " . $link->error . "<br>";
        }
    } else {
        // Mark as offline if not found in UISP
        $link->query("UPDATE sites SET status = 0 WHERE sid = $sid");
        $offline_count++;
        $not_found_count++;
        echo "<span style='color: #aaa'>[NOT FOUND & OFFLINE]</span> Site #$sid ($name) - IP $ip not found in UISP.<br>";
    }
}
echo "</div>";

// Summary
echo "<h3>Sync Complete</h3>";
echo "<ul>";
echo "<li>Total Sites Updated: <b>$updated_count</b></li>";
echo "<li>Set to Online: <b style='color:green'>$online_count</b></li>";
echo "<li>Set to Offline: <b style='color:red'>$offline_count</b></li>";
echo "<li>Sites not found in UISP: <b>$not_found_count</b></li>";
echo "</ul>";
echo "</div>
        </div>
    </section>
</main>";
?>

<?php require("footer.php");?>
