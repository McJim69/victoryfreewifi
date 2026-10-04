<?php
require("connect.php");
require("station_naming.php");

// --- AJAX: Save a single fix ---
if (isset($_POST['ajax_fix'])) {
    header('Content-Type: application/json');
    $sid    = (int)$_POST['sid'];
    $new    = mysqli_real_escape_string($link, trim($_POST['new_barangay']));

    // Also link the site to the official barangay row (bid), so the rename tool resolves it by ID
    list($by_bid, $by_key) = load_official_barangays($link);
    
    // Fetch the current row to get mcode
    $cq = mysqli_query($link, "SELECT mcode, place FROM sites WHERE sid=$sid");
    if (!($cr = mysqli_fetch_assoc($cq))) {
        echo json_encode(['status' => 'error', 'msg' => 'Site not found']);
        exit;
    }
    $mcode = $cr['mcode'];
    $cur_place = $cr['place'];
    
    $k = brgy_key($mcode, $new);
    if (empty($by_key[$k])) {
        echo json_encode(['status' => 'error', 'msg' => 'Not an official barangay for this MCODE']);
        exit;
    }
    $bid = (int)$by_key[$k];

    // Check collision
    if (!isset($_POST['force'])) {
        $check = mysqli_query($link, "SELECT sid, ip_address FROM sites WHERE mcode='$mcode' AND barangay='$new' AND place='$cur_place' AND sid != $sid");
        if (mysqli_num_rows($check) > 0) {
            $conf = mysqli_fetch_assoc($check);
            echo json_encode([
                'status' => 'collision', 
                'msg' => 'Collision with site #'.$conf['sid'].($conf['ip_address'] ? ' (IP: '.$conf['ip_address'].')' : ' (no IP)'),
                'conflict_sid' => $conf['sid']
            ]);
            exit;
        }
    } else {
        $conflict_sid = (int)$_POST['conflict_sid'];
        mysqli_query($link, "DELETE FROM sites WHERE sid=$conflict_sid");
    }

    $sql = "UPDATE sites SET barangay='$new', bid=$bid WHERE sid=$sid";
    if (mysqli_query($link, $sql)) {
        if (mysqli_affected_rows($link) > 0) {
            echo json_encode(['status' => 'ok', 'affected' => mysqli_affected_rows($link)]);
        } else {
            echo json_encode(['status' => 'error', 'msg' => 'No rows affected. The site may already be updated.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'msg' => mysqli_error($link)]);
    }
    exit;
}

// --- AJAX: Save a placement (PCODE) fix ---
if (isset($_POST['ajax_fix_place'])) {
    header('Content-Type: application/json');
    $sid    = (int)$_POST['sid'];
    $pcodes = load_pcodes($link);
    $p      = strtoupper(trim($_POST['new_place']));
    if (!isset($pcodes[$p])) {
        echo json_encode(['status' => 'error', 'msg' => 'Not a valid PCODE from the placement table']);
        exit;
    }
    $new = mysqli_real_escape_string($link, $pcodes[$p]);

    // Check collision
    if (!isset($_POST['force'])) {
        $check = mysqli_query($link, "SELECT mcode, barangay FROM sites WHERE sid=$sid");
        if ($srow = mysqli_fetch_assoc($check)) {
            $mc = $srow['mcode'];
            $br = $srow['barangay'];
            $col = mysqli_query($link, "SELECT sid, ip_address FROM sites WHERE mcode='$mc' AND barangay='$br' AND place='$new' AND sid != $sid");
            if (mysqli_num_rows($col) > 0) {
                $conf = mysqli_fetch_assoc($col);
                echo json_encode([
                    'status' => 'collision', 
                    'msg' => 'Collision with site #'.$conf['sid'].($conf['ip_address'] ? ' (IP: '.$conf['ip_address'].')' : ' (no IP)'),
                    'conflict_sid' => $conf['sid']
                ]);
                exit;
            }
        }
    } else {
        $conflict_sid = (int)$_POST['conflict_sid'];
        mysqli_query($link, "DELETE FROM sites WHERE sid=$conflict_sid");
    }

    $sql = "UPDATE sites SET place='$new' WHERE sid=$sid";
    if (mysqli_query($link, $sql)) {
        echo json_encode(['status' => 'ok', 'affected' => mysqli_affected_rows($link)]);
    } else {
        echo json_encode(['status' => 'error', 'msg' => mysqli_error($link)]);
    }
    exit;
}

// --- AJAX: Skip / mark ignored (just return ok, no DB change) ---
if (isset($_POST['ajax_skip'])) {
    header('Content-Type: application/json');
    echo json_encode(['status' => 'skipped']);
    exit;
}

require("header.php");
require("menunav.php");

// --- Build official barangay list ---
$official = [];
$q = mysqli_query($link, "SELECT mcode, barangay FROM barangays ORDER BY barangay");
while ($r = mysqli_fetch_assoc($q)) {
    $key = strtoupper(trim($r['mcode']));
    $official[$key][] = trim($r['barangay']);
}
list($by_bid, $by_key) = load_official_barangays($link);

// --- Fuzzy best match ---
function best_match($input, $candidates) {
    $best = 0; $match = null;
    foreach ($candidates as $c) {
        similar_text(strtoupper($input), strtoupper($c), $pct);
        if ($pct > $best) { $best = $pct; $match = $c; }
    }
    return ['match' => $match, 'score' => round($best, 1)];
}

// --- Collect mismatches ---
$res = mysqli_query($link, "SELECT sid, ip_address, mcode, barangay, place FROM sites WHERE mcode != '' AND mcode IS NOT NULL AND barangay != '' AND barangay IS NOT NULL ORDER BY mcode, barangay");
$rows = [];
while ($row = mysqli_fetch_assoc($res)) {
    $mcode = strtoupper(trim($row['mcode']));
    $brgy  = trim($row['barangay']);
    if (!isset($official[$mcode])) continue;
    // Same rule as the rename tool: case-insensitive, spaces == hyphens
    if (empty($by_key[brgy_key($mcode, $brgy)])) {
        $r = best_match($brgy, $official[$mcode]);
        $rows[] = [
            'sid'        => $row['sid'],
            'ip'         => $row['ip_address'],
            'mcode'      => $mcode,
            'barangay'   => $row['barangay'], // raw untouched db value for display
            'place'      => $row['place'],
            'suggestion' => $r['match'],
            'score'      => $r['score'],
            'options'    => $official[$mcode],
        ];
    }
}

// --- Placement (PCODE) list ---
$placements = [];   // pcode => pname
$q = mysqli_query($link, "SELECT pcode, pname FROM placement ORDER BY pcode");
while ($r = mysqli_fetch_assoc($q)) $placements[strtoupper(trim($r['pcode']))] = $r['pname'];

// Known abbreviations technicians use -> official PCODE
$place_aliases = [
    'ES' => 'ELS', 'ELS1' => 'ELS', 'CES' => 'ELS', 'ELS2' => 'ES2',
    'HALL' => 'BAP', 'BRGY' => 'BAP',
    'RELAY' => 'REL', 'RLY' => 'REL',
    'CP' => 'CKP',
    'CBS' => 'BST',
    'TESDA' => 'VOC',
    'COL' => 'PGC',
    'JAIL' => 'GOF',
    'HQ' => 'GHQ',
    'HOSP' => 'HOS', 'MARKET' => 'PMA',
];

// Only known abbreviations get a suggestion; fuzzy matching on 2-5 letter codes is unreliable.
function suggest_pcode($place, $placements, $aliases) {
    $p = strtoupper(trim($place));
    if (isset($aliases[$p]) && isset($placements[$aliases[$p]])) return [$aliases[$p], 'alias'];
    return [null, 'none'];
}

$prows = [];
$res = mysqli_query($link, "SELECT sid, ip_address, mcode, barangay, bid, place FROM sites ORDER BY (ip_address IS NULL OR ip_address = ''), mcode, barangay");
while ($row = mysqli_fetch_assoc($res)) {
    $p = strtoupper(trim($row['place']));
    if (isset($placements[$p])) continue;
    list($sug, $conf) = suggest_pcode($row['place'], $placements, $place_aliases);
    list($b, $berr) = resolve_site_barangay($row, $by_bid, $by_key);
    $prows[] = [
        'sid'        => (int)$row['sid'],
        'ip'         => $row['ip_address'],
        'mcode'      => strtoupper(trim($row['mcode'])),
        'barangay'   => $row['barangay'],
        'place'      => $row['place'],
        'suggestion' => $sug,
        'conf'       => $conf,
        // Name prefix for the live preview (null if the barangay itself still needs fixing)
        'prefix'     => $b ? strtoupper(trim($b['mcode'])) . '-' . station_brgy_part($b['barangay']) : null,
    ];
}
$p_with_ip = count(array_filter($prows, function ($r) { return trim((string)$r['ip']) !== ''; }));
?>

<script>setActive("admin");</script>
<script>setActive("fix_barangays");</script>

<style>
    .brgy-card {
        background: #fff;
        border: 1px solid #dee2e6;
        border-radius: 10px;
        padding: 18px 22px;
        margin-bottom: 14px;
        transition: all .25s ease;
        box-shadow: 0 1px 4px rgba(0,0,0,.06);
        position: relative;
    }
    .brgy-card:hover { box-shadow: 0 4px 12px rgba(13,110,253,.10); }
    .brgy-card.resolved {
        opacity: .45;
        border-color: #28a745;
        background: #f6fff8;
    }
    .brgy-card.skipped-card {
        opacity: .45;
        border-color: #6c757d;
        background: #f8f9fa;
    }
    .badge-score {
        font-size:.75rem; padding:3px 8px; border-radius:999px;
    }
    .badge-score.high   { background:#d4edda; color:#155724; }
    .badge-score.med    { background:#fff3cd; color:#856404; }
    .badge-score.low    { background:#f8d7da; color:#721c24; }
    .site-label { font-weight:600; font-size:1rem; }
    .old-brgy   { font-family:monospace; color:#dc3545; font-size:.95rem; }
    .progress-bar-wrap { height:6px; background:#e9ecef; border-radius:3px; margin-bottom:12px; }
    .progress-bar-fill { height:6px; background:#0d6efd; border-radius:3px; transition:width .3s; }
    .stats-bar { background:#f0f4ff; border:1px solid #c7d7f5; border-radius:8px; padding:12px 18px; margin-bottom:20px; font-size:.9rem; }

    .fix-tabs { display:flex; gap:8px; margin-bottom:18px; border-bottom:2px solid #e9ecef; }
    .fix-tab { background:none; border:none; padding:10px 18px; font-weight:600; color:#6c757d; border-bottom:3px solid transparent; margin-bottom:-2px; cursor:pointer; transition:all .2s; }
    .fix-tab:hover { color:#0d6efd; }
    .fix-tab.active { color:#0d6efd; border-bottom-color:#0d6efd; }
    .fix-tab .cnt { display:inline-block; min-width:24px; padding:1px 8px; margin-left:6px; border-radius:999px; background:#e9ecef; color:#495057; font-size:.8rem; }
    .fix-tab.active .cnt { background:#0d6efd; color:#fff; }
    .fix-pane { display:none; }
    .fix-pane.active { display:block; animation: paneIn .25s ease; }
    @keyframes paneIn { from { opacity:0; transform:translateY(4px); } to { opacity:1; transform:none; } }

    .name-preview { font-family:monospace; font-size:.85rem; background:#f1f8f4; color:#1e7e34; padding:2px 8px; border-radius:5px; }
    .name-preview.warn { background:#fff3cd; color:#856404; }
    .noip { font-size:.75rem; color:#6c757d; background:#f1f3f5; padding:2px 6px; border-radius:4px; margin-left:6px; }
</style>

<main class="main">
    <section style="margin-top:90px;">
        <div class="container">
            <h2 class="text-primary mb-1">
                <i class="fa fa-map-marker"></i> Site Data Review
            </h2>
            <p class="text-muted mb-3">
                Station names follow <code>MCODE-Barangay-PCODE-STN</code>. Fix sites whose barangay or placement
                does not match the official <b>barangays</b> / <b>placement</b> tables, so the rename tool can name them.
            </p>

            <div class="fix-tabs">
                <button class="fix-tab active" data-pane="pane-brgy">Barangay <span class="cnt"><?php echo count($rows); ?></span></button>
                <button class="fix-tab" data-pane="pane-place">Placement <span class="cnt"><?php echo count($prows); ?></span></button>
            </div>

            <!-- ===================== BARANGAY ===================== -->
            <div class="fix-pane active" id="pane-brgy">
            <p class="text-muted mb-3">These sites have barangay names that don't match the official list. Select the correct barangay from the dropdown and click <b>Fix</b>, or <b>Skip</b> to leave unchanged.</p>

            <div class="stats-bar" id="stats-bar">
                <span id="stat-total"><b><?php echo count($rows); ?></b> sites to review</span> &nbsp;|&nbsp;
                <span id="stat-fixed" style="color:#28a745;"><b>0</b> fixed</span> &nbsp;|&nbsp;
                <span id="stat-skipped" style="color:#6c757d;"><b>0</b> skipped</span>
            </div>

            <div class="progress-bar-wrap">
                <div class="progress-bar-fill" id="prog-bar" style="width:0%"></div>
            </div>

            <?php if (empty($rows)): ?>
                <div class="alert alert-success"><i class="fa fa-check-circle"></i> All barangays are valid! No mismatches found.</div>
            <?php else: ?>
            <div id="cards-wrap">
            <?php foreach ($rows as $i => $m):
                $score_class = $m['score'] >= 75 ? 'high' : ($m['score'] >= 55 ? 'med' : 'low');
                $opts_html = '';
                foreach ($m['options'] as $opt) {
                    $sel = ($opt === $m['suggestion']) ? 'selected' : '';
                    $opts_html .= "<option value=\"".htmlspecialchars($opt)."\" $sel>".htmlspecialchars($opt)."</option>";
                }
            ?>
            <div class="brgy-card" id="card-<?php echo $i; ?>">
                <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                    <div>
                        <div class="site-label">
                            <span class="badge badge-primary" style="background:#0d6efd;color:#fff;padding:3px 8px;border-radius:5px;margin-right:6px;"><?php echo htmlspecialchars($m['mcode']); ?></span>
                            <?php echo htmlspecialchars($m['ip']); ?>
                            <?php if ($m['place']): ?><span class="text-muted"> — <?php echo htmlspecialchars($m['place']); ?></span><?php endif; ?>
                        </div>
                        <div class="mt-1">
                            Current: <span class="old-brgy">"<?php echo htmlspecialchars($m['barangay']); ?>"</span>
                            <span class="badge-score <?php echo $score_class; ?> ml-2">Best guess: <?php echo htmlspecialchars($m['suggestion']); ?> (<?php echo $m['score']; ?>%)</span>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-2" style="flex-shrink:0;">
                        <select class="form-control form-control-sm" id="sel-<?php echo $i; ?>" style="min-width:200px;">
                            <?php echo $opts_html; ?>
                        </select>
                        <button class="btn btn-success btn-sm ml-2" onclick="doFix(<?php echo $i; ?>, this)"
                            data-sid="<?php echo htmlspecialchars($m['sid']); ?>">
                            <i class="fa fa-check"></i> Fix
                        </button>
                        <button class="btn btn-secondary btn-sm ml-1" onclick="doSkip(<?php echo $i; ?>)">
                            Skip
                        </button>
                    </div>
                </div>
                <div id="msg-<?php echo $i; ?>" class="mt-1" style="font-size:.85rem;"></div>
            </div>
            <?php endforeach; ?>
            </div>
            <?php endif; ?>
            </div>

            <!-- ===================== PLACEMENT ===================== -->
            <div class="fix-pane" id="pane-place">
            <p class="text-muted mb-3">
                These sites use a placement code that is not in the official <b>placement</b> table.
                Pick the correct PCODE and click <b>Fix</b>. The preview shows the resulting station name.
            </p>

            <div class="stats-bar">
                <span><b><?php echo count($prows); ?></b> sites to review (<b><?php echo $p_with_ip; ?></b> with an IP / UISP device)</span> &nbsp;|&nbsp;
                <span id="pstat-fixed" style="color:#28a745;"><b>0</b> fixed</span> &nbsp;|&nbsp;
                <span id="pstat-skipped" style="color:#6c757d;"><b>0</b> skipped</span>
            </div>

            <div class="progress-bar-wrap">
                <div class="progress-bar-fill" id="pprog-bar" style="width:0%"></div>
            </div>

            <?php if (empty($prows)): ?>
                <div class="alert alert-success"><i class="fa fa-check-circle"></i> All placement codes are valid! No mismatches found.</div>
            <?php else: ?>
            <?php foreach ($prows as $i => $m):
                $has_sug = $m['suggestion'] !== null;
                $opts_html = $has_sug ? '' : "<option value=\"\" selected>-- Select PCODE --</option>";
                foreach ($placements as $code => $name) {
                    $sel = ($code === $m['suggestion']) ? 'selected' : '';
                    $opts_html .= "<option value=\"$code\" $sel>$code — " . htmlspecialchars($name) . "</option>";
                }
                $preview_code = $has_sug ? $m['suggestion'] : '???';
            ?>
            <div class="brgy-card" id="pcard-<?php echo $i; ?>">
                <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                    <div>
                        <div class="site-label">
                            <span class="badge badge-primary" style="background:#0d6efd;color:#fff;padding:3px 8px;border-radius:5px;margin-right:6px;"><?php echo htmlspecialchars($m['mcode']); ?></span>
                            <?php echo htmlspecialchars($m['barangay']); ?>
                            <?php if (trim((string)$m['ip']) !== ''): ?>
                                <span class="text-muted"> — <?php echo htmlspecialchars($m['ip']); ?></span>
                            <?php else: ?>
                                <span class="noip">no IP</span>
                            <?php endif; ?>
                            <small class="text-muted ml-1">site #<?php echo $m['sid']; ?></small>
                        </div>
                        <div class="mt-1">
                            Current: <span class="old-brgy">"<?php echo htmlspecialchars($m['place']); ?>"</span>
                            <?php if ($has_sug): ?>
                                <span class="badge-score high ml-2">Suggested: <?php echo $m['suggestion']; ?> (known abbreviation)</span>
                            <?php else: ?>
                                <span class="badge-score low ml-2">Unknown code &mdash; choose manually</span>
                            <?php endif; ?>
                        </div>
                        <div class="mt-1">
                            <?php if ($m['prefix'] !== null): ?>
                                New name: <span class="name-preview" id="pprev-<?php echo $i; ?>" data-prefix="<?php echo htmlspecialchars($m['prefix']); ?>"><?php echo htmlspecialchars($m['prefix'] . '-' . $preview_code . '-STN'); ?></span>
                            <?php else: ?>
                                <span class="name-preview warn"><i class="fa fa-exclamation-triangle"></i> Barangay must also be fixed (see Barangay tab)</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-2" style="flex-shrink:0;">
                        <select class="form-control form-control-sm psel" id="psel-<?php echo $i; ?>" data-i="<?php echo $i; ?>" style="min-width:230px;">
                            <?php echo $opts_html; ?>
                        </select>
                        <button class="btn btn-success btn-sm ml-2" onclick="doFixPlace(<?php echo $i; ?>, this)"
                            data-sid="<?php echo $m['sid']; ?>">
                            <i class="fa fa-check"></i> Fix
                        </button>
                        <button class="btn btn-secondary btn-sm ml-1" onclick="doSkipPlace(<?php echo $i; ?>)">
                            Skip
                        </button>
                    </div>
                </div>
                <div id="pmsg-<?php echo $i; ?>" class="mt-1" style="font-size:.85rem;"></div>
            </div>
            <?php endforeach; ?>
            <?php endif; ?>
            </div>
        </div>
    </section>
</main>

<script>
// --- Tabs ---
document.querySelectorAll('.fix-tab').forEach(function (btn) {
    btn.addEventListener('click', function () {
        document.querySelectorAll('.fix-tab').forEach(b => b.classList.remove('active'));
        document.querySelectorAll('.fix-pane').forEach(p => p.classList.remove('active'));
        btn.classList.add('active');
        document.getElementById(btn.dataset.pane).classList.add('active');
        history.replaceState(null, '', '#' + btn.dataset.pane);
    });
});
if (location.hash) {
    const t = document.querySelector('.fix-tab[data-pane="' + location.hash.substring(1) + '"]');
    if (t) t.click();
}

function esc(s) { const d = document.createElement('div'); d.textContent = s; return d.innerHTML; }

// --- Barangay fixes ---
const total = <?php echo count($rows); ?>;
let fixed = 0, skipped = 0;

function updateStats() {
    document.getElementById('stat-fixed').innerHTML   = '<b>' + fixed   + '</b> fixed';
    document.getElementById('stat-skipped').innerHTML = '<b>' + skipped + '</b> skipped';
    const done = fixed + skipped;
    document.getElementById('prog-bar').style.width = (total ? Math.round(done/total*100) : 0) + '%';
}

async function doFix(i, btn, force=false, conflict_sid=null) {
    const sel    = document.getElementById('sel-' + i);
    const newVal = sel.value;
    const msg    = document.getElementById('msg-' + i);
    msg.innerHTML = '<span style="color:orange;">Saving...</span>';

    const fd = new FormData();
    fd.append('ajax_fix', '1');
    fd.append('sid', btn.dataset.sid);
    fd.append('new_barangay', newVal);
    if (force) {
        fd.append('force', '1');
        fd.append('conflict_sid', conflict_sid);
    }

    try {
        const r   = await fetch('', { method:'POST', body:fd });
        const res = await r.json();
        if (res.status === 'ok') {
            msg.innerHTML = '<span style="color:#28a745;"><i class="fa fa-check"></i> Fixed &rarr; <b>' + esc(newVal) + '</b></span>';
            document.getElementById('card-' + i).classList.add('resolved');
            btn.disabled = true;
            fixed++;
        } else if (res.status === 'collision') {
            msg.innerHTML = '<span style="color:#dc3545;">' + esc(res.msg) + '</span> ' +
                            '<button class="btn btn-warning btn-sm" onclick="doFix(' + i + ', document.querySelector(\'#card-' + i + ' .btn-success\'), true, ' + res.conflict_sid + ')">Delete Conflict & Force Update</button>';
        } else {
            msg.innerHTML = '<span style="color:red;">Error: ' + esc(res.msg) + '</span>';
        }
    } catch(e) {
        msg.innerHTML = '<span style="color:red;">Network error</span>';
    }
    updateStats();
}

function doSkip(i) {
    document.getElementById('card-' + i).classList.add('skipped-card');
    document.getElementById('msg-' + i).innerHTML = '<span style="color:#6c757d;">Skipped</span>';
    skipped++;
    updateStats();
}

// --- Placement fixes ---
const ptotal = <?php echo count($prows); ?>;
let pfixed = 0, pskipped = 0;

function updatePStats() {
    document.getElementById('pstat-fixed').innerHTML   = '<b>' + pfixed   + '</b> fixed';
    document.getElementById('pstat-skipped').innerHTML = '<b>' + pskipped + '</b> skipped';
    document.getElementById('pprog-bar').style.width = (ptotal ? Math.round((pfixed + pskipped) / ptotal * 100) : 0) + '%';
}

// Live name preview when the PCODE dropdown changes
document.querySelectorAll('.psel').forEach(function (sel) {
    sel.addEventListener('change', function () {
        const prev = document.getElementById('pprev-' + sel.dataset.i);
        if (prev) prev.textContent = prev.dataset.prefix + '-' + (sel.value || '???') + '-STN';
    });
});

async function doFixPlace(i, btn, force=false, conflict_sid=null) {
    const newVal = document.getElementById('psel-' + i).value;
    const msg    = document.getElementById('pmsg-' + i);
    if (!newVal) { msg.innerHTML = '<span style="color:red;">Please select a PCODE first.</span>'; return; }
    msg.innerHTML = '<span style="color:orange;">Saving...</span>';

    const fd = new FormData();
    fd.append('ajax_fix_place', '1');
    fd.append('sid', btn.dataset.sid);
    fd.append('new_place', newVal);
    if (force) {
        fd.append('force', '1');
        fd.append('conflict_sid', conflict_sid);
    }

    try {
        const r   = await fetch('', { method:'POST', body:fd });
        const res = await r.json();
        if (res.status === 'ok' && res.affected > 0) {
            msg.innerHTML = '<span style="color:#28a745;"><i class="fa fa-check"></i> Fixed &rarr; <b>' + esc(newVal) + '</b></span>';
            document.getElementById('pcard-' + i).classList.add('resolved');
            btn.disabled = true;
            pfixed++;
        } else if (res.status === 'ok') {
            msg.innerHTML = '<span style="color:#856404;">No change: the site was already modified. Reload the page.</span>';
        } else if (res.status === 'collision') {
            msg.innerHTML = '<span style="color:#dc3545;">' + esc(res.msg) + '</span> ' +
                            '<button class="btn btn-warning btn-sm" onclick="doFixPlace(' + i + ', document.querySelector(\'#pcard-' + i + ' .btn-success\'), true, ' + res.conflict_sid + ')">Delete Conflict & Force Update</button>';
        } else {
            msg.innerHTML = '<span style="color:red;">Error: ' + esc(res.msg) + '</span>';
        }
    } catch(e) {
        msg.innerHTML = '<span style="color:red;">Network error</span>';
    }
    updatePStats();
}

function doSkipPlace(i) {
    document.getElementById('pcard-' + i).classList.add('skipped-card');
    document.getElementById('pmsg-' + i).innerHTML = '<span style="color:#6c757d;">Skipped</span>';
    pskipped++;
    updatePStats();
}
</script>

<?php require("footer.php"); ?>
