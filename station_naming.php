<?php
// ============================================================
// Station device naming convention (single source of truth)
//
//   MCODE-Barangay-PCODE-STN
//
//   MCODE    : barangays.mcode   (municipality code, upper-case)
//   Barangay : barangays.barangay (official spelling, spaces -> hyphens)
//   PCODE    : placement.pcode
//   STN      : fixed suffix, station devices only
//
// Example: barangays "Santo Niño (Pob)" / TUKU, placement BAP
//          -> TUKU-Santo-Niño-(Pob)-BAP-STN
// ============================================================

// Key used to compare barangay names: case-insensitive, spaces == hyphens.
function brgy_key($mcode, $barangay) {
    return strtoupper(trim($mcode)) . '|' . strtoupper(preg_replace('/[\s\-]+/u', '-', trim($barangay)));
}

// Barangay segment of the station name (official spelling, spaces -> hyphens).
function station_brgy_part($barangay) {
    return preg_replace('/\s+/u', '-', trim($barangay));
}

function build_station_name($mcode, $barangay, $pcode) {
    return strtoupper(trim($mcode)) . '-' . station_brgy_part($barangay) . '-' . strtoupper(trim($pcode)) . '-STN';
}

// Loads official barangays. Returns [by_bid, by_key]; by_key maps brgy_key() => bid (or false if ambiguous).
function load_official_barangays($link) {
    $by_bid = [];
    $by_key = [];
    $q = $link->query("SELECT bid, mcode, barangay FROM barangays");
    while ($r = $q->fetch_assoc()) {
        $by_bid[(int)$r['bid']] = $r;
        $k = brgy_key($r['mcode'], $r['barangay']);
        $by_key[$k] = isset($by_key[$k]) ? false : (int)$r['bid'];
    }
    return [$by_bid, $by_key];
}

// Loads official placement codes. Returns [UPPER(pcode) => pcode].
function load_pcodes($link) {
    $pc = [];
    $q = $link->query("SELECT pcode FROM placement");
    while ($r = $q->fetch_assoc()) $pc[strtoupper(trim($r['pcode']))] = strtoupper(trim($r['pcode']));
    return $pc;
}

// Resolves the official barangay row for a site: by bid first, then by name.
// Returns [row|null, error|null].
function resolve_site_barangay($site, $by_bid, $by_key) {
    $bid = isset($site['bid']) ? (int)$site['bid'] : 0;
    if ($bid && isset($by_bid[$bid])) {
        $b = $by_bid[$bid];
        // Guard against a bid that points to a different barangay than the site record says
        if (trim($site['barangay']) !== '' && brgy_key($site['mcode'], $site['barangay']) !== brgy_key($b['mcode'], $b['barangay'])) {
            return [null, "bid #$bid is '{$b['mcode']} {$b['barangay']}' but site says '{$site['mcode']} {$site['barangay']}'"];
        }
        return [$b, null];
    }
    $k = brgy_key($site['mcode'], $site['barangay']);
    if (isset($by_key[$k])) {
        if ($by_key[$k] === false) return [null, "ambiguous barangay name '{$site['barangay']}'"];
        return [$by_bid[$by_key[$k]], null];
    }
    return [null, "'{$site['mcode']} {$site['barangay']}' not in barangays table"];
}

// Builds the expected station name for a site. Returns [name|null, error|null].
function expected_station_name($site, $by_bid, $by_key, $pcodes) {
    list($b, $err) = resolve_site_barangay($site, $by_bid, $by_key);
    $p = strtoupper(trim($site['place']));
    if (!isset($pcodes[$p])) {
        $perr = "placement '" . trim($site['place']) . "' not in placement table";
        $err = $err ? "$err; $perr" : $perr;
    }
    if ($err) return [null, $err];
    return [build_station_name($b['mcode'], $b['barangay'], $pcodes[$p]), null];
}
