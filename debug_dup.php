<?php
require("connect.php");
echo "--- indexes on sites ---\n";
$r = $link->query("SHOW INDEX FROM sites"); while ($x = $r->fetch_assoc()) echo "{$x['Key_name']} non_unique={$x['Non_unique']} seq={$x['Seq_in_index']} col={$x['Column_name']}\n";
echo "\n--- conflicts ---\n";
$cases = [[852,'ELS'],[805,'REL'],[848,'ELS']];
foreach ($cases as $c) {
    list($sid, $pc) = $c;
    $s = $link->query("SELECT * FROM sites WHERE sid=$sid")->fetch_assoc();
    echo "SITE #$sid: {$s['mcode']}|{$s['barangay']}|{$s['place']} ip={$s['ip_address']} status={$s['status']} inst={$s['inst_date']} installer={$s['installer']} link={$s['link_ap_bst']} coords={$s['coordinates']}\n";
    $b = $link->real_escape_string($s['barangay']); $m = $link->real_escape_string($s['mcode']);
    $r = $link->query("SELECT * FROM sites WHERE mcode='$m' AND barangay='$b' AND place='$pc'");
    while ($o = $r->fetch_assoc()) echo "  EXISTING #{$o['sid']}: {$o['mcode']}|{$o['barangay']}|{$o['place']} ip={$o['ip_address']} status={$o['status']} inst={$o['inst_date']} installer={$o['installer']} link={$o['link_ap_bst']} coords={$o['coordinates']} img={$o['loc_img']}/{$o['add_img']} speed={$o['speedtest']}\n";
}
// How many of the 41 placement rows would collide with their suggested code?
echo "\n--- collision count for all invalid-place rows (vs alias suggestion) ---\n";
$alias = ['ES'=>'ELS','ELS1'=>'ELS','CES'=>'ELS','ELS2'=>'ES2','HALL'=>'BAP','RELAY'=>'REL','CP'=>'CKP','TESDA'=>'VOC','COL'=>'PGC','JAIL'=>'GOF'];
$pc = []; $r = $link->query("SELECT pcode FROM placement"); while ($x = $r->fetch_assoc()) $pc[strtoupper($x['pcode'])] = 1;
$r = $link->query("SELECT sid, mcode, barangay, place, ip_address FROM sites");
$all = []; while ($x = $r->fetch_assoc()) $all[] = $x;
foreach ($all as $s) {
    $p = strtoupper(trim($s['place'])); if (isset($pc[$p]) || !isset($alias[$p])) continue;
    $t = $alias[$p]; $hit = null;
    foreach ($all as $o) if ($o['sid'] != $s['sid'] && strcasecmp($o['mcode'],$s['mcode'])==0 && strcasecmp($o['barangay'],$s['barangay'])==0 && strcasecmp(trim($o['place']),$t)==0) $hit = $o;
    echo "#{$s['sid']} {$s['mcode']}|{$s['barangay']}|{$s['place']}->$t ip={$s['ip_address']} : " . ($hit ? "COLLIDES with #{$hit['sid']} ip='{$hit['ip_address']}'" : "ok") . "\n";
}
