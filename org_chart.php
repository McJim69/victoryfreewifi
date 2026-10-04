<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Organizational Chart | Victory Free WiFi</title>
<meta name="description" content="Victory Free WiFi organizational structure.">
<link href="awesome/css/all.min.css" rel="stylesheet">
<style>
	*{ box-sizing:border-box; }
	html,body{ margin:0; height:100%; overflow:hidden; background:#0f0a0a; font-family:system-ui,-apple-system,"Segoe UI",Roboto,sans-serif; }

	/* Image fits the viewport by default (contain), centred */
	#stage{ position:fixed; inset:0; overflow:hidden; cursor:zoom-in; touch-action:none; }
	#stage.zoomed{ cursor:grab; }
	#stage.dragging{ cursor:grabbing; }
	#chart{
		position:absolute; left:50%; top:50%;
		max-width:100vw; max-height:100vh; width:auto; height:auto;
		transform:translate(-50%,-50%) translate(var(--x,0px),var(--y,0px)) scale(var(--s,1));
		transform-origin:center center;
		transition:transform .25s ease;
		user-select:none; -webkit-user-drag:none;
		box-shadow:0 20px 60px rgba(0,0,0,.6);
	}
	#stage.dragging #chart, #stage.wheeling #chart{ transition:none; }

	/* Floating glass toolbar */
	.bar{
		position:fixed; left:50%; bottom:18px; transform:translateX(-50%);
		display:flex; gap:6px; padding:6px; border-radius:14px; z-index:5;
		background:rgba(20,10,10,.55); border:1px solid rgba(255,255,255,.12);
		backdrop-filter:blur(10px); -webkit-backdrop-filter:blur(10px);
		box-shadow:0 10px 30px rgba(0,0,0,.4);
		opacity:.35; transition:opacity .25s ease;
	}
	.bar:hover, body.idle-off .bar{ opacity:1; }
	.bar button, .bar a{
		width:40px; height:40px; border:0; border-radius:10px; cursor:pointer;
		display:inline-flex; align-items:center; justify-content:center;
		background:transparent; color:#fff; font-size:15px; text-decoration:none;
		transition:background .2s ease, color .2s ease;
	}
	.bar button:hover, .bar a:hover{ background:rgba(248,194,85,.2); color:#f8c255; }
	.bar .zoom{ min-width:58px; color:rgba(255,255,255,.8); font-size:12px; font-weight:600; display:flex; align-items:center; justify-content:center; }
	.bar .sep{ width:1px; background:rgba(255,255,255,.15); margin:6px 2px; }
</style>
</head>
<body>
	<h1 style="position:absolute;width:1px;height:1px;overflow:hidden;clip:rect(0 0 0 0)">Organizational Chart</h1>

	<div id="stage">
		<img id="chart" src="assets/img/org_chart.jpg" alt="Victory Free WiFi Organizational Chart" draggable="false">
	</div>

	<nav class="bar" aria-label="Chart controls">
		<a href="javascript:history.length>1?history.back():location.href='admin.php'" id="btnBack" title="Back"><i class="fas fa-arrow-left"></i></a>
		<span class="sep"></span>
		<button id="btnOut" title="Zoom out (-)"><i class="fas fa-search-minus"></i></button>
		<span class="zoom" id="zoomLbl">Fit</span>
		<button id="btnIn" title="Zoom in (+)"><i class="fas fa-search-plus"></i></button>
		<button id="btnFit" title="Fit to screen (0)"><i class="fas fa-compress"></i></button>
		<span class="sep"></span>
		<button id="btnFull" title="Fullscreen (F)"><i class="fas fa-expand"></i></button>
		<a href="assets/img/org_chart.jpg" download id="btnDl" title="Download"><i class="fas fa-download"></i></a>
	</nav>

<script>
(function(){
	var stage = document.getElementById('stage'), img = document.getElementById('chart'), lbl = document.getElementById('zoomLbl');
	var s = 1, x = 0, y = 0, MIN = 1, MAX = 8;

	function clamp(){
		// keep the image from being dragged off-screen
		var w = img.offsetWidth * s, h = img.offsetHeight * s;
		var mx = Math.max(0, (w - innerWidth) / 2), my = Math.max(0, (h - innerHeight) / 2);
		x = Math.min(mx, Math.max(-mx, x)); y = Math.min(my, Math.max(-my, y));
	}
	function apply(){
		clamp();
		img.style.setProperty('--s', s); img.style.setProperty('--x', x + 'px'); img.style.setProperty('--y', y + 'px');
		stage.classList.toggle('zoomed', s > 1);
		lbl.textContent = s === 1 ? 'Fit' : Math.round(s * 100) + '%';
	}
	// zoom keeping the point (cx,cy) under the cursor fixed
	function zoomTo(ns, cx, cy){
		ns = Math.min(MAX, Math.max(MIN, ns));
		if(cx === undefined){ cx = innerWidth / 2; cy = innerHeight / 2; }
		var ox = cx - innerWidth / 2 - x, oy = cy - innerHeight / 2 - y, r = ns / s;
		x -= ox * (r - 1); y -= oy * (r - 1); s = ns;
		if(s === 1){ x = 0; y = 0; }
		apply();
	}
	function fit(){ s = 1; x = 0; y = 0; apply(); }

	// Wheel zoom
	var wt;
	stage.addEventListener('wheel', function(e){
		e.preventDefault();
		stage.classList.add('wheeling'); clearTimeout(wt); wt = setTimeout(function(){ stage.classList.remove('wheeling'); }, 150);
		zoomTo(s * (e.deltaY < 0 ? 1.15 : 1 / 1.15), e.clientX, e.clientY);
	}, { passive:false });

	// Drag to pan / click to toggle zoom
	var down = false, moved = false, sx, sy, bx, by;
	stage.addEventListener('pointerdown', function(e){ down = true; moved = false; sx = e.clientX; sy = e.clientY; bx = x; by = y; stage.setPointerCapture(e.pointerId); });
	stage.addEventListener('pointermove', function(e){
		if(!down) return;
		var dx = e.clientX - sx, dy = e.clientY - sy;
		if(Math.abs(dx) + Math.abs(dy) > 4) moved = true;
		if(moved && s > 1){ stage.classList.add('dragging'); x = bx + dx; y = by + dy; apply(); }
	});
	stage.addEventListener('pointerup', function(e){
		down = false; stage.classList.remove('dragging');
		if(!moved){ s > 1 ? fit() : zoomTo(3, e.clientX, e.clientY); }
	});

	document.getElementById('btnIn').onclick  = function(){ zoomTo(s * 1.4); };
	document.getElementById('btnOut').onclick = function(){ zoomTo(s / 1.4); };
	document.getElementById('btnFit').onclick = fit;
	document.getElementById('btnFull').onclick = function(){
		document.fullscreenElement ? document.exitFullscreen() : document.documentElement.requestFullscreen();
	};

	document.addEventListener('keydown', function(e){
		if(e.key === '+' || e.key === '=') zoomTo(s * 1.4);
		else if(e.key === '-') zoomTo(s / 1.4);
		else if(e.key === '0' || e.key === 'Escape') fit();
		else if(e.key === 'f' || e.key === 'F') document.getElementById('btnFull').click();
	});

	addEventListener('resize', apply);
	img.addEventListener('load', apply);
	apply();
})();
</script>
</body>
</html>
