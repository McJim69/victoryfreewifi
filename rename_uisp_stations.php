<?php
// ==========================================
// UISP API CONFIGURATION
// ==========================================
$uisp_url = "https://10.0.10.130";
$uisp_token = "32fc12c8-bf0b-4a2c-9c1d-504ad1df54c4";
$verify_ssl = false;
// ==========================================

function uisp_api($method, $path, $body = null) {
    global $uisp_url, $uisp_token, $verify_ssl;
    $ch = curl_init(rtrim($uisp_url, '/') . "/nms/api/v2.1" . $path);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_CUSTOMREQUEST  => $method,
        CURLOPT_TIMEOUT        => 30,
        CURLOPT_SSL_VERIFYPEER => $verify_ssl,
        CURLOPT_SSL_VERIFYHOST => $verify_ssl ? 2 : 0,
        CURLOPT_HTTPHEADER     => ["x-auth-token: " . $uisp_token, "Content-Type: application/json"],
    ]);
    if ($body !== null) curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
    $resp = curl_exec($ch);
    $err  = curl_error($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    return [$code, $resp, $err];
}

// Sets the UISP alias (display name) for a device. This is stored on the UISP
// server itself, so it works even when the device is offline / unreachable.
function uisp_set_alias($device_id, $alias) {
    list($code, $resp, $err) = uisp_api("GET", "/devices/$device_id/system/unms");
    if ($code !== 200) return [false, "Could not read UISP settings (HTTP $code) $err", $resp];

    $cfg = json_decode($resp, true);
    if (!is_array($cfg)) return [false, "Invalid UISP response", $resp];

    // UISP rejects top-level null values on PUT (e.g. deviceGracePeriodOutage), so strip them.
    $cfg = array_filter($cfg, function ($v) { return $v !== null; });
    if (!isset($cfg['meta']) || !is_array($cfg['meta'])) $cfg['meta'] = [];
    $cfg['meta']['alias'] = $alias;

    list($code, $resp, $err) = uisp_api("PUT", "/devices/$device_id/system/unms", $cfg);
    $out = json_decode($resp, true);
    if ($code === 200 && isset($out['meta']['alias']) && $out['meta']['alias'] === $alias) {
        return [true, "UISP alias set to $alias", ""];
    }
    return [false, "UISP rejected alias update (HTTP $code) $err", $resp];
}

if (isset($_POST['ajax_rename'])) {
    header('Content-Type: application/json');
    $method    = isset($_POST['method']) ? $_POST['method'] : 'ssh';
    $device_id = preg_replace('/[^a-fA-F0-9\-]/', '', isset($_POST['id']) ? $_POST['id'] : '');
    $new_name  = trim($_POST['expected']);
    $cur_alias = isset($_POST['alias']) ? trim($_POST['alias']) : '';

    // ---------- Method 1: UISP server (alias) ----------
    if ($method === 'uisp') {
        if ($device_id === '') { echo json_encode(["status" => "error", "msg" => "Missing UISP device ID", "output" => ""]); exit; }
        list($ok, $msg, $out) = uisp_set_alias($device_id, $new_name);
        echo json_encode(["status" => $ok ? "success" : "error", "msg" => $msg, "output" => $ok ? "" : $out]);
        exit;
    }

    // ---------- Method 2: SSH directly to device (hostname) ----------
    // Strip characters that could break the remote shell/printf (quotes, backslash, %, /, control chars).
    $safe_name = preg_replace('/[\'"\\\\%\/\x00-\x1F]/', '', $new_name);
    // Encode every non-ASCII byte (e.g. the UTF-8 bytes of "ñ") as a printf octal escape,
    // so only plain ASCII travels through cmd.exe/plink and the device rebuilds the exact name.
    $printf_name = preg_replace_callback('/[\x80-\xFF]/', function ($m) { return sprintf('\\%03o', ord($m[0])); }, $safe_name);
    $ip = escapeshellarg($_POST['ip']);
    $ssh_user = escapeshellarg($_POST['ssh_user']);
    $ssh_pass = escapeshellarg($_POST['ssh_pass']);

    $cmd = "sed -i '/^resolv.host.1.name=/d' /tmp/system.cfg; printf 'resolv.host.1.name={$printf_name}\\n' >> /tmp/system.cfg; cfgmtd -p /etc/ -w; /usr/etc/rc.d/rc.softrestart save";
    $cmd_esc = escapeshellarg($cmd);

    // Using plink for SSH. Output stderr to stdout
    $exec_cmd = "cmd.exe /c \"echo y | plink -ssh -l {$ssh_user} -pw {$ssh_pass} {$ip} {$cmd_esc} 2>&1\"";

    $output = shell_exec($exec_cmd);

    if (strpos($output, 'Access denied') !== false) {
        echo json_encode(["status" => "error", "msg" => "Authentication Failed", "output" => $output]);
    } elseif (strpos($output, 'Connection refused') !== false || strpos($output, 'Network error') !== false || strpos($output, 'timed out') !== false) {
        echo json_encode(["status" => "error", "msg" => "Network Error / Timeout", "output" => $output]);
    } else {
        $msg = "Hostname set to $safe_name via SSH";
        // If the device already has a (wrong) UISP alias, UISP would keep showing it,
        // so sync the alias too.
        if ($cur_alias !== '' && $cur_alias !== $new_name && $device_id !== '') {
            list($ok, $amsg) = uisp_set_alias($device_id, $new_name);
            $msg .= $ok ? " + UISP alias updated" : " (warning: $amsg)";
        }
        echo json_encode(["status" => "success", "msg" => $msg, "output" => $output]);
    }
    exit;
}

ini_set('max_execution_time', 300);
require("connect.php");
require("station_naming.php");
require("header.php");
require("menunav.php");

// Generate DB Mapping: MCODE-Barangay-PCODE-STN from the official barangays + placement tables
list($by_bid, $by_key) = load_official_barangays($link);
$pcodes = load_pcodes($link);
$sites = [];        // ip => expected name
$unresolved = [];   // ip => [site, reason]
$q = $link->query("SELECT sid, ip_address, mcode, barangay, bid, place FROM sites WHERE ip_address != '' AND ip_address IS NOT NULL");
while($r = $q->fetch_assoc()) {
    $ip_parts = explode('/', $r['ip_address']);
    $ip = trim($ip_parts[0]);
    if(filter_var($ip, FILTER_VALIDATE_IP)) {
        list($name, $err) = expected_station_name($r, $by_bid, $by_key, $pcodes);
        if ($name !== null) $sites[$ip] = $name;
        else $unresolved[$ip] = [$r, $err];
    }
}
?>
<script>setActive("admin");</script>
<script>setActive("rename");</script>

<style>
    .rn-mode { display:flex; gap:12px; flex-wrap:wrap; margin-bottom:15px; }
    .rn-mode label { flex:1; min-width:230px; border:2px solid #ddd; border-radius:8px; padding:12px 14px; cursor:pointer; transition:all .2s; margin:0; }
    .rn-mode label:hover { border-color:#80bdff; background:#f5faff; }
    .rn-mode input:checked + span { color:#0056b3; }
    .rn-mode label.active { border-color:#007bff; background:#eaf4ff; box-shadow:0 2px 6px rgba(0,123,255,.15); }
    .rn-mode small { display:block; color:#666; font-weight:normal; margin-top:4px; }
    .rn-stats .badge { font-size:90%; margin-right:6px; padding:6px 10px; }
    #mismatchTable td { vertical-align:middle; }
    .rn-mono { font-family:monospace; font-size:90%; }
</style>

<main class="main" style="">
    <section style="margin-top:90px;">
        <div class="container">
            <h2 class="text-primary">Rename Station Devices</h2>
            <div class="container" style="padding:20px; background:#fff; border:1px solid #ddd; border-radius:8px;">
            <p>
                Naming convention: <code>MCODE-Barangay-PCODE-STN</code> &mdash; MCODE and Barangay from the official
                <b>barangays</b> table, PCODE from the <b>placement</b> table (station devices only).<br>
                Two ways to rename:
                <b>SSH</b> changes the real hostname on the antenna (device must be online), while
                <b>UISP Server</b> sets the device alias on the UISP server (works even when the device is offline).
            </p>
<?php

list($http_code, $response) = uisp_api("GET", "/devices?role=station");

if ($http_code !== 200) {
    echo "<div style='color:red'>Failed to fetch UISP devices. HTTP $http_code</div>";
} else {
    $devices = json_decode($response, true);
    $mismatches = [];
    $blocked = [];   // UISP stations whose DB record can't produce a valid name

    foreach ($devices as $dev) {
        if (!isset($dev['identification']['role']) || $dev['identification']['role'] !== 'station') continue;
        if (empty($dev['ipAddress'])) continue;

        $ip_parts = explode('/', $dev['ipAddress']);
        $ip = trim($ip_parts[0]);
        if (isset($unresolved[$ip])) {
            $blocked[] = ['ip' => $ip, 'current' => $dev['identification']['name'], 'site' => $unresolved[$ip][0], 'reason' => $unresolved[$ip][1]];
            continue;
        }
        if (!isset($sites[$ip])) continue;

        $current_name = $dev['identification']['name'];          // what UISP shows (alias if set)
        $hostname     = isset($dev['identification']['hostname']) ? $dev['identification']['hostname'] : $current_name;
        $alias        = isset($dev['meta']['alias']) ? (string)$dev['meta']['alias'] : '';
        $expected     = $sites[$ip];
        $online       = (isset($dev['overview']['status']) && $dev['overview']['status'] === 'active');

        if ($current_name !== $expected) {
            $type = 'rename';            // wrong name in UISP
        } elseif ($hostname !== $expected) {
            $type = 'hostname';          // UISP alias is correct, but device hostname is still old
        } else {
            continue;
        }

        $mismatches[] = [
            'id'       => $dev['identification']['id'],
            'ip'       => $ip,
            'current'  => $current_name,
            'hostname' => $hostname,
            'alias'    => $alias,
            'expected' => $expected,
            'online'   => $online,
            'type'     => $type,
        ];
    }

    // Online first, then by type
    usort($mismatches, function ($a, $b) {
        if ($a['type'] !== $b['type']) return $a['type'] === 'rename' ? -1 : 1;
        return $b['online'] - $a['online'];
    });

    if (count($blocked) > 0) {
        echo "<div class='alert alert-danger'><b>" . count($blocked) . " station(s) cannot be renamed</b> because their site record does not match the official "
           . "<b>barangays</b> / <b>placement</b> tables. Fix them first in <a href='fix_barangay_admin.php'>Fix Barangays</a> or the site editor."
           . "<details class='mt-2'><summary>Show list</summary><table class='table table-sm table-bordered mt-2 mb-0' style='background:#fff;'>"
           . "<thead><tr><th>IP</th><th>Current UISP Name</th><th>Site #</th><th>Problem</th></tr></thead><tbody>";
        foreach ($blocked as $b) {
            echo "<tr><td class='rn-mono'>" . htmlspecialchars($b['ip']) . "</td><td>" . htmlspecialchars($b['current']) . "</td><td>" . (int)$b['site']['sid'] . "</td><td>" . htmlspecialchars($b['reason']) . "</td></tr>";
        }
        echo "</tbody></table></details></div>";
    }

    if (count($mismatches) > 0) {
        $c_rename  = count(array_filter($mismatches, function ($m) { return $m['type'] === 'rename'; }));
        $c_host    = count($mismatches) - $c_rename;
        $c_online  = count(array_filter($mismatches, function ($m) { return $m['online']; }));
        $c_offline = count($mismatches) - $c_online;

        echo "<div class='rn-stats mb-3'>";
        echo "<span class='badge badge-danger'>Wrong name in UISP: $c_rename</span>";
        echo "<span class='badge badge-warning'>Alias OK, hostname pending: $c_host</span>";
        echo "<span class='badge badge-success'>Online: $c_online</span>";
        echo "<span class='badge badge-secondary'>Offline: $c_offline</span>";
        echo "</div>";

        echo "<table class='table table-bordered table-striped table-sm' id='mismatchTable'>";
        echo "<thead class='thead-dark'><tr><th style='width:30px;'><input type='checkbox' id='chkAll' checked></th><th>IP Address</th><th>Device</th><th>Current UISP Name</th><th>Expected Name (DB)</th><th>Method</th><th>Status</th></tr></thead><tbody>";
        foreach ($mismatches as $i => $m) {
            $dev_badge = $m['online'] ? "<span class='badge badge-success'>Online</span>" : "<span class='badge badge-secondary'>Offline</span>";
            $cur = htmlspecialchars($m['current']);
            if ($m['type'] === 'hostname') {
                $cur .= "<br><small class='text-warning'>hostname: " . htmlspecialchars($m['hostname']) . "</small>";
            } elseif ($m['alias'] !== '') {
                $cur .= "<br><small class='text-muted'>(alias) hostname: " . htmlspecialchars($m['hostname']) . "</small>";
            }
            echo "<tr id='row-$i'>";
            echo "<td><input type='checkbox' class='rowChk' data-i='$i' checked></td>";
            echo "<td class='rn-mono'>" . htmlspecialchars($m['ip']) . "</td>";
            echo "<td>$dev_badge</td>";
            echo "<td>$cur</td>";
            echo "<td><b style='color:green'>" . htmlspecialchars($m['expected']) . "</b></td>";
            echo "<td id='method-$i'></td>";
            echo "<td id='status-$i'>Waiting...</td>";
            echo "</tr>";
        }
        echo "</tbody></table>";
?>
        <hr>
        <div id="renameForm">
            <h4>Rename Method</h4>
            <div class="rn-mode">
                <label class="active"><input type="radio" name="rn_mode" value="auto" checked> <span><b>Auto (recommended)</b></span>
                    <small>SSH for online devices, UISP Server alias for offline devices.</small></label>
                <label><input type="radio" name="rn_mode" value="uisp"> <span><b>UISP Server only</b></span>
                    <small>Set alias on UISP for all selected devices. No SSH needed. Works offline.</small></label>
                <label><input type="radio" name="rn_mode" value="ssh"> <span><b>SSH only</b></span>
                    <small>Change real hostname on the antenna. Offline devices are skipped.</small></label>
            </div>

            <div id="sshCreds">
                <h5>SSH Credentials for Antennas</h5>
                <div class="form-inline">
                    <div class="form-group mb-2"><label class="mr-2">SSH Username</label>
						<input type="text" id="ssh_user" class="form-control" value="ubnt">
					</div>
                    <div class="form-group mb-2"><label class="mr-2">SSH Password</label>
						<input type="password" id="ssh_pass" class="form-control">
					</div>
                </div>
            </div>
            <p class="mt-2 mb-2"><small class="text-muted" id="planSummary"></small></p>
            <button type="button" id="btnExecute" class="btn btn-warning mb-2">Execute Rename</button>
        </div>

        <div id="progressArea" style="display:none;">
            <h4>Rename Execution Log</h4>
            <div id="logConsole" style="background:#222; color:#0f0; padding:10px; height:300px; overflow-y:auto; font-family:monospace;"></div>
            <br><a href="rename_uisp_stations.php" class="btn btn-primary">Reload List</a>
        </div>

<script>
const mismatches = <?php echo json_encode($mismatches); ?>;

function getMode() {
    return document.querySelector('input[name="rn_mode"]:checked').value;
}

// Decide which method applies to a row for the chosen mode ('ssh', 'uisp' or null = skip)
function planFor(m, mode) {
    if (m.type === 'hostname') {
        // Alias already correct; only SSH can fix the real hostname
        return (mode !== 'uisp' && m.online) ? 'ssh' : null;
    }
    if (mode === 'uisp') return 'uisp';
    if (mode === 'ssh')  return m.online ? 'ssh' : null;
    return m.online ? 'ssh' : 'uisp'; // auto
}

function methodLabel(p) {
    if (p === 'ssh')  return "<span class='badge badge-info'>SSH</span>";
    if (p === 'uisp') return "<span class='badge badge-primary'>UISP Server</span>";
    return "<span class='badge badge-light'>Skip</span>";
}

function refreshPlan() {
    const mode = getMode();
    let nSsh = 0, nUisp = 0, nSkip = 0;
    document.querySelectorAll('.rn-mode label').forEach(l => l.classList.toggle('active', l.querySelector('input').checked));
    mismatches.forEach((m, i) => {
        const checked = document.querySelector('.rowChk[data-i="' + i + '"]').checked;
        const p = checked ? planFor(m, mode) : null;
        document.getElementById('method-' + i).innerHTML = checked ? methodLabel(p) : "<span class='text-muted'>—</span>";
        if (!checked) return;
        if (p === 'ssh') nSsh++; else if (p === 'uisp') nUisp++; else nSkip++;
    });
    document.getElementById('sshCreds').style.display = nSsh > 0 ? 'block' : 'none';
    document.getElementById('planSummary').innerHTML =
        `Plan: <b>${nSsh}</b> via SSH, <b>${nUisp}</b> via UISP Server, <b>${nSkip}</b> skipped.`;
}

document.querySelectorAll('input[name="rn_mode"]').forEach(r => r.addEventListener('change', refreshPlan));
document.querySelectorAll('.rowChk').forEach(c => c.addEventListener('change', refreshPlan));
document.getElementById('chkAll').addEventListener('change', function() {
    document.querySelectorAll('.rowChk').forEach(c => c.checked = this.checked);
    refreshPlan();
});
refreshPlan();

document.getElementById('btnExecute').addEventListener('click', async function() {
    const mode = getMode();
    const user = document.getElementById('ssh_user').value;
    const pass = document.getElementById('ssh_pass').value;

    const jobs = [];
    mismatches.forEach((m, i) => {
        if (!document.querySelector('.rowChk[data-i="' + i + '"]').checked) return;
        jobs.push({ i: i, m: m, plan: planFor(m, mode) });
    });
    const nSsh = jobs.filter(j => j.plan === 'ssh').length;
    const nUisp = jobs.filter(j => j.plan === 'uisp').length;

    if (nSsh + nUisp === 0) { alert('Nothing to do for the selected devices and method.'); return; }
    if (nSsh > 0 && (!user || !pass)) { alert('Please enter both SSH Username and Password'); return; }
    if (!confirm(`This will rename ${nSsh} device(s) via SSH and ${nUisp} device(s) via the UISP Server. Proceed?`)) return;

    document.getElementById('renameForm').style.display = 'none';
    document.getElementById('progressArea').style.display = 'block';
    document.querySelectorAll('.rowChk, #chkAll').forEach(c => c.disabled = true);
    const logConsole = document.getElementById('logConsole');
    let ok = 0, fail = 0, skip = 0;

    for (const job of jobs) {
        const m = job.m;
        const statusCell = document.getElementById('status-' + job.i);

        if (!job.plan) {
            statusCell.innerHTML = '<span style="color:gray;">Skipped</span>';
            logConsole.innerHTML += "<span style='color:#aaa'>[SKIPPED]</span> " + m.ip + " (" + (m.online ? 'not applicable for this method' : 'offline') + ")<br>";
            skip++;
            continue;
        }

        statusCell.innerHTML = '<span style="color:orange;">Processing...</span>';
        logConsole.innerHTML += (job.plan === 'ssh' ? "SSH to " : "UISP Server rename for ") + m.ip + "...<br>";
        logConsole.scrollTop = logConsole.scrollHeight;

        try {
            let formData = new FormData();
            formData.append('ajax_rename', '1');
            formData.append('method', job.plan);
            formData.append('id', m.id);
            formData.append('ip', m.ip);
            formData.append('alias', m.alias);
            formData.append('expected', m.expected);
            if (job.plan === 'ssh') {
                formData.append('ssh_user', user);
                formData.append('ssh_pass', pass);
            }

            let response = await fetch('', { method: 'POST', body: formData });
            let result = await response.json();

            if (result.status === 'success') {
                statusCell.innerHTML = '<span style="color:green;">Success</span>';
                logConsole.innerHTML += "[SUCCESS] " + m.ip + ": " + result.msg + "<br>";
                ok++;
            } else {
                statusCell.innerHTML = '<span style="color:red;">Failed</span>';
                logConsole.innerHTML += "<span style='color:red'>[FAILED]</span> " + m.ip + ": " + result.msg + " - " + (result.output || '') + "<br>";
                fail++;
            }
        } catch(err) {
            statusCell.innerHTML = '<span style="color:red;">Error</span>';
            logConsole.innerHTML += "<span style='color:red'>[ERROR]</span> " + m.ip + ": " + err.message + "<br>";
            fail++;
        }
        logConsole.scrollTop = logConsole.scrollHeight;
    }
    logConsole.innerHTML += `<br><b>Finished!</b> Success: ${ok}, Failed: ${fail}, Skipped: ${skip}<br>`;
    logConsole.scrollTop = logConsole.scrollHeight;
});
</script>
<?php
    } else {
        echo "<div class='alert alert-success'>All station devices are perfectly synced with the database naming convention!</div>";
    }
}
?>
            </div>
        </div>
    </section>
</main>
<?php require("footer.php"); ?>
