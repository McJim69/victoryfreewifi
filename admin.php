<?php
	require("connect.php");

	if(!isset($_SESSION['user'])){
		header("location:index.php");
		exit();
	}
	require("header.php");
	require("menunav.php");

	/**
	 * The `admin` table stores links as raw HTML fragments, e.g.
	 *   href="servers.php"
	 *   href="backup.php" >Backup Database
	 *   href="x.php" rel="facebox">Add Item</a>
	 * Parse them into a clean [href, text, rel, target] structure so the
	 * label is always visible, regardless of how the row was entered.
	 */
	function adm_parse_link($raw){
		$raw = trim((string)$raw);
		if($raw === '') return null;

		$href = '';
		if(preg_match('/href\s*=\s*["\']([^"\']*)["\']/i', $raw, $m)) $href = trim($m[1]);
		elseif(preg_match('/href\s*=\s*([^\s>]+)/i', $raw, $m))       $href = trim($m[1]);

		$rel = '';
		if(preg_match('/rel\s*=\s*["\']([^"\']*)["\']/i', $raw, $m)) $rel = $m[1];
		$target = '';
		if(preg_match('/target\s*=\s*["\']([^"\']*)["\']/i', $raw, $m)) $target = $m[1];

		// Label = everything after the first ">" (tags stripped)
		$text = '';
		$pos = strpos($raw, '>');
		if($pos !== false) $text = trim(html_entity_decode(strip_tags(substr($raw, $pos + 1))));

		// Fallback: derive a label from the file name (servers_stats.php -> Servers Stats)
		if($text === '' && $href !== '' && $href !== '#'){
			$path = parse_url($href, PHP_URL_PATH);
			$base = pathinfo($path ? $path : $href, PATHINFO_FILENAME);
			$text = ucwords(str_replace(array('_','-'), ' ', $base));
		}
		if($text === '' && ($href === '' || $href === '#')) return null;

		return array('href' => ($href !== '' ? $href : '#'), 'text' => $text, 'rel' => $rel, 'target' => $target);
	}

	function adm_attrs($l){
		$a = 'href="'.htmlspecialchars($l['href'], ENT_QUOTES).'"';
		if($l['rel'])    $a .= ' rel="'.htmlspecialchars($l['rel'], ENT_QUOTES).'"';
		if($l['target']) $a .= ' target="'.htmlspecialchars($l['target'], ENT_QUOTES).'"';
		return $a;
	}

	// Per-module look & feel: [description, Font Awesome icon, gradient start, gradient end]
	$adm_meta = array(
		'servers'      => array('Manage network servers & controllers', 'fa-server',          '#6366f1', '#8b5cf6'),
		'database'     => array('Backup & restore operations',          'fa-database',        '#0ea5e9', '#06b6d4'),
		'team'         => array('Management, technicians & org structure', 'fa-users',      '#f43f5e', '#f59e0b'),
		'installers'   => array('Manage technician teams',              'fa-hard-hat',        '#f59e0b', '#f97316'),
		'barangays'    => array('Manage locations and sites',           'fa-map-marked-alt',  '#10b981', '#14b8a6'),
		'category'     => array('Manage device categories',             'fa-layer-group',     '#ec4899', '#f43f5e'),
		'site status'  => array('Monitor live status & updates',        'fa-signal',          '#22c55e', '#84cc16'),
		'rollouts'     => array('Track team accomplishments',           'fa-rocket',          '#ef4444', '#f97316'),
		'users'        => array('Manage system administrators',         'fa-user-shield',     '#3b82f6', '#6366f1'),
		'uisp tools'   => array('Manage devices via UISP integration',  'fa-broadcast-tower', '#8b5cf6', '#d946ef'),
		'data cleanup' => array('Fix invalid or missing records',       'fa-broom',           '#64748b', '#0ea5e9'),
	);
	$adm_default = array('Administration module', 'fa-cog', '#9a0f0f', '#e0a526');

	$cards = array();
	$exa = $link->query("SELECT * FROM admin");
	while($rsa = mysqli_fetch_array($exa)){
		$key  = strtolower(trim($rsa["title"]));
		$meta = isset($adm_meta[$key]) ? $adm_meta[$key] : $adm_default;

		$links = array();
		foreach(array('link1','link2','link3') as $col){
			$p = adm_parse_link($rsa[$col]);
			if($p) $links[] = $p;
		}
		$main = adm_parse_link($rsa["link0"]);
		if(!$main || $main['href'] === '#') $main = $links ? $links[0] : array('href'=>'#','text'=>'','rel'=>'','target'=>'');

		$cards[] = array(
			'title' => trim($rsa["title"]),
			'desc'  => $meta[0],
			'icon'  => $meta[1],
			'c1'    => $meta[2],
			'c2'    => $meta[3],
			'main'  => $main,
			'links' => $links,
		);
	}
	$totalModules = count($cards);
	$totalLinks   = 0;
	foreach($cards as $c) $totalLinks += count($c['links']);
	$userName = (isset($_SESSION['user']) && is_string($_SESSION['user'])) ? $_SESSION['user'] : 'Admin';
	$hour  = (int)date('G');
	$greet = $hour < 12 ? 'Good morning' : ($hour < 18 ? 'Good afternoon' : 'Good evening');
?>

<link href="assets/css/admin.css" rel="stylesheet">

<script>setActive("admin");</script>

<main id="main">
	<section id="breadcrumbs" class="breadcrumbs adm-hero">
		<div class="container">
			<ol>
				<li><a href="index.php">Home</a></li>
				<li>Admin</li>
				<li>Settings</li>
			</ol>
			<div class="adm-hero-row">
				<div>
					<div class="adm-eyebrow"><i class="fas fa-sliders-h"></i>&nbsp; Control Center</div>
					<h1><?php echo $greet; ?>, <?php echo htmlspecialchars($userName); ?> 👋</h1>
					<p class="adm-sub">Everything you need to run Victory Free WiFi — all in one place.</p>
				</div>
				<div class="adm-actions">
					<div class="adm-search">
						<i class="fas fa-search"></i>
						<input type="text" id="admSearch" placeholder="Search tools..." autocomplete="off" aria-label="Search admin tools">
						<kbd>/</kbd>
					</div>
					<a rel="facebox" href="admin_add.php" class="adm-btn" id="admAddBtn"><i class="fas fa-plus"></i> Add Module</a>
				</div>
			</div>
			<div class="adm-stats">
				<div class="adm-stat"><i class="fas fa-th-large"></i><b><?php echo $totalModules; ?></b> Modules</div>
				<div class="adm-stat"><i class="fas fa-link"></i><b><?php echo $totalLinks; ?></b> Quick Actions</div>
				<div class="adm-stat"><i class="far fa-calendar-alt"></i><b><?php echo date('M j'); ?></b> <?php echo date('l'); ?></div>
			</div>
		</div>
	</section>

	<section class="adm-wrap">
		<div class="container">
			<div class="adm-grid" id="admGrid">
				<?php foreach($cards as $i => $c):
					$labels = array();
					foreach($c['links'] as $l) $labels[] = $l['text'];
					$search = strtolower($c['title'].' '.$c['desc'].' '.implode(' ', $labels));
				?>
				<article class="adm-card" style="--c1:<?php echo $c['c1']; ?>;--c2:<?php echo $c['c2']; ?>;animation-delay:<?php echo $i * 50; ?>ms"
				         data-search="<?php echo htmlspecialchars($search, ENT_QUOTES); ?>">
					<a class="adm-head" <?php echo adm_attrs($c['main']); ?>>
						<div class="adm-icon"><i class="fas <?php echo $c['icon']; ?>"></i></div>
						<div>
							<h2 class="adm-title"><?php echo htmlspecialchars($c['title']); ?></h2>
							<p class="adm-desc"><?php echo htmlspecialchars($c['desc']); ?></p>
						</div>
					</a>
					<ul class="adm-links">
						<?php if(!$c['links']): ?>
							<li class="adm-empty">No quick actions yet</li>
						<?php else: foreach($c['links'] as $l): ?>
							<li>
								<a <?php echo adm_attrs($l); ?>>
									<span class="dot"></span>
									<span><?php echo htmlspecialchars($l['text']); ?></span>
									<i class="fas fa-arrow-right arr"></i>
								</a>
							</li>
						<?php endforeach; endif; ?>
					</ul>
				</article>
				<?php endforeach; ?>
			</div>
			<div class="adm-noresult" id="admNoResult">
				<i class="fas fa-search-minus"></i>
				No tools match "<span id="admTerm"></span>"
			</div>
		</div>
	</section>
</main>

<script>
	(function(){
		var input = document.getElementById('admSearch');
		var cards = document.querySelectorAll('#admGrid .adm-card');
		var none  = document.getElementById('admNoResult');
		var term  = document.getElementById('admTerm');

		input.addEventListener('input', function(){
			var q = this.value.trim().toLowerCase(), shown = 0;
			for(var i = 0; i < cards.length; i++){
				var hit = !q || cards[i].getAttribute('data-search').indexOf(q) !== -1;
				cards[i].style.display = hit ? '' : 'none';
				if(hit) shown++;
			}
			term.textContent = this.value;
			none.style.display = shown ? 'none' : 'block';
		});

		// Press "/" to focus search, Esc to clear
		document.addEventListener('keydown', function(e){
			var tag = (document.activeElement && document.activeElement.tagName) || '';
			if(e.key === '/' && tag !== 'INPUT' && tag !== 'TEXTAREA'){ e.preventDefault(); input.focus(); }
			if(e.key === 'Escape' && document.activeElement === input){ input.value = ''; input.dispatchEvent(new Event('input')); input.blur(); }
		});
	})();

	if ( window.history.replaceState ) {
		window.history.replaceState( null, null, window.location.href );
	}
</script>

<?php require("footer.php");?>
