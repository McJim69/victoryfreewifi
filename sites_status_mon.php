<?php 
	require("connect.php");

	// Query active/inactive counts for total
	$qryStatus = $link->query("SELECT SUM(status = 1) AS active, SUM(status = 0) AS inactive FROM sites");
	$row = $qryStatus->fetch_assoc();
	$rawACT = (int)$row['active'];
	$rawDWN = (int)$row['inactive'];

	// Totals and percentages
	$total = $rawACT + $rawDWN;
	$percent1 = ($total > 0) ? ($rawACT / $total) * 100 : 0;
	$percent2 = 100 - $percent1;

	// Query for municipality breakdown
	$qryMuni = $link->query("
		SELECT 
			mcode, 
			COUNT(*) as total_sites,
			SUM(status = 1) AS active, 
			SUM(status = 0) AS inactive 
		FROM sites 
		GROUP BY mcode 
		ORDER BY total_sites DESC
	");
	
	$muniLabels = [];
	$muniActive = [];
	$muniInactive = [];
	
	while($rowM = $qryMuni->fetch_assoc()){
		$muniLabels[] = $rowM['mcode'];
		$muniActive[] = (int)$rowM['active'];
		$muniInactive[] = (int)$rowM['inactive'];
	}

	require("header_all.php"); 
	require("menunav.php");	
?>

<script>
	setActive("sites"); 
	setActive("stats");
</script>

<style>
	.dashboard-card {
		border-radius: 10px;
		box-shadow: 0 4px 6px rgba(0,0,0,0.1);
		background: #fff;
		padding: 20px;
		margin-bottom: 20px;
		border: 1px solid #eee;
	}
	.stat-value {
		font-size: 2.5rem;
		font-weight: 700;
		line-height: 1;
	}
	.stat-label {
		color: #6c757d;
		font-weight: 600;
		text-transform: uppercase;
		font-size: 0.9rem;
		margin-top: 5px;
	}
	.list-container {
		height: 400px;
		overflow-y: auto;
		border-radius: 0 0 8px 8px;
		border: 1px solid #ddd;
		border-top: none;
	}
	.list-group-item {
		border-left: none;
		border-right: none;
		border-top: none;
		border-bottom: 1px solid #eee;
	}
	.list-group-item:last-child {
		border-bottom: none;
	}
	.chart-wrapper {
		position: relative;
		height: 300px;
		width: 100%;
	}
</style>

<!-- Chart.js -->
<script src="assets/chartjs/chart.js"></script>

<main id="main" style="background: #f4f6f9; padding-bottom: 50px;">
	<section class="breadcrumbs" style="margin-top: 120px;">
		<div class="container">
			<ol>
				<li><a href="index.php">Home</a></li>
				<li><a href="sites_list.php">Sites</a></li>
				<li>Analytics Dashboard</li>
			</ol>
			<h2>Site Status & Analytics</h2>
		</div>
	</section>

	<section class="inner-page pt-4">
		<div class="container" data-aos="fade-up">
			
			<!-- KPI Row -->
			<div class="row">
				<div class="col-lg-4 col-md-6">
					<div class="dashboard-card text-center" style="border-bottom: 4px solid #007bff;">
						<div class="stat-value text-primary"><?php echo number_format($total); ?></div>
						<div class="stat-label">Total Deployed Sites</div>
					</div>
				</div>
				<div class="col-lg-4 col-md-6">
					<div class="dashboard-card text-center" style="border-bottom: 4px solid #28a745;">
						<div class="stat-value text-success"><?php echo number_format($rawACT); ?> <span style="font-size: 1.2rem; color: #aaa;">(<?php echo number_format($percent1); ?>%)</span></div>
						<div class="stat-label">Online Sites</div>
					</div>
				</div>
				<div class="col-lg-4 col-md-12">
					<div class="dashboard-card text-center" style="border-bottom: 4px solid #dc3545;">
						<div class="stat-value text-danger"><?php echo number_format($rawDWN); ?> <span style="font-size: 1.2rem; color: #aaa;">(<?php echo number_format($percent2); ?>%)</span></div>
						<div class="stat-label">Offline Sites</div>
					</div>
				</div>
			</div>

			<!-- Charts Row -->
			<div class="row">
				<div class="col-lg-4">
					<div class="dashboard-card">
						<h5 class="text-center mb-4">Overall Network Status</h5>
						<div class="chart-wrapper">
							<canvas id="doughnutChart"></canvas>
						</div>
					</div>
				</div>
				<div class="col-lg-8">
					<div class="dashboard-card">
						<div class="d-flex justify-content-between align-items-center mb-4">
							<h5 class="mb-0">Municipality Health Distribution</h5>
							<div class="small">
								Next refresh in <b id="countdown" class="text-danger">05:00</b>
							</div>
						</div>
						<div class="chart-wrapper">
							<canvas id="barChart"></canvas>
						</div>
					</div>
				</div>
			</div>

			<!-- Lists Row -->
			<div class="row mt-2">
				<div class="col-lg-6">
					<div class="dashboard-card p-0 mb-4">
						<div class="p-3 bg-success text-white rounded-top d-flex justify-content-between align-items-center">
							<h5 class="m-0"><i class="fa fa-wifi"></i> Online Sites</h5>
							<span class="badge bg-light text-success" style="font-size:1.1rem;"><?php echo number_format($rawACT); ?></span>
						</div>
						<div class="list-container">
							<div class="list-group list-group-flush">
								<?php 
									$i=1;
									$ex=$link->query("SELECT * FROM sites WHERE status=1 ORDER BY mcode, barangay");
									while($rs=mysqli_fetch_assoc($ex)){
										echo "<a href=\"site_details.php?sites=".(int)$rs["sid"]."\" class=\"list-group-item list-group-item-action\">
												<b>$i.</b> ".$rs["mcode"]." - ".$rs["barangay"]." <span class='text-muted small'>(".$rs["place"].")</span>
											  </a>";
										$i++;
									}
								?>
							</div>
						</div>
					</div>
				</div>
				
				<div class="col-lg-6">
					<div class="dashboard-card p-0 mb-4">
						<div class="p-3 bg-danger text-white rounded-top d-flex justify-content-between align-items-center">
							<h5 class="m-0"><i class="fa fa-exclamation-triangle"></i> Offline Sites</h5>
							<span class="badge bg-light text-danger" style="font-size:1.1rem;"><?php echo number_format($rawDWN); ?></span>
						</div>
						<div class="list-container">
							<div class="list-group list-group-flush">
								<?php 
									$i=1;
									$ex=$link->query("SELECT * FROM sites WHERE status=0 ORDER BY mcode, barangay");
									while($rs=mysqli_fetch_assoc($ex)){
										echo "<a href=\"site_details.php?sites=".(int)$rs["sid"]."\" class=\"list-group-item list-group-item-action\">
												<b>$i.</b> ".$rs["mcode"]." - ".$rs["barangay"]." <span class='text-muted small'>(".$rs["place"].")</span>
											  </a>";
										$i++;
									}
								?>
							</div>
						</div>
					</div>
				</div>
			</div>

		</div>
	</section>
</main>

<script>
	// Chart.js Configuration
	document.addEventListener("DOMContentLoaded", function() {
		// Doughnut Chart
		const ctxDoughnut = document.getElementById('doughnutChart').getContext('2d');
		new Chart(ctxDoughnut, {
			type: 'doughnut',
			data: {
				labels: ['Online', 'Offline'],
				datasets: [{
					data: [<?php echo $rawACT; ?>, <?php echo $rawDWN; ?>],
					backgroundColor: ['#28a745', '#dc3545'],
					borderWidth: 0,
					hoverOffset: 4
				}]
			},
			options: {
				responsive: true,
				maintainAspectRatio: false,
				plugins: {
					legend: { position: 'bottom' }
				},
				cutout: '70%'
			}
		});

		// Stacked Bar Chart
		const ctxBar = document.getElementById('barChart').getContext('2d');
		new Chart(ctxBar, {
			type: 'bar',
			data: {
				labels: <?php echo json_encode($muniLabels); ?>,
				datasets: [
					{
						label: 'Online',
						data: <?php echo json_encode($muniActive); ?>,
						backgroundColor: '#28a745',
					},
					{
						label: 'Offline',
						data: <?php echo json_encode($muniInactive); ?>,
						backgroundColor: '#dc3545',
					}
				]
			},
			options: {
				responsive: true,
				maintainAspectRatio: false,
				scales: {
					x: { stacked: true },
					y: { stacked: true, beginAtZero: true }
				},
				plugins: {
					legend: { position: 'bottom' }
				}
			}
		});
	});

	// Auto refresh after 5 minutes
	setTimeout(function(){
		window.location.reload();
	}, 300000);

	// Countdown timer
	function countdown(elementName, minutes, seconds){
	  var element, endTime, hours, mins, msLeft, time;
	  function twoDigits(n){ return (n <= 9 ? "0" + n : n); }
	  function updateTimer(){
		msLeft = endTime - (+new Date);
		if (msLeft < 1000) {
		  if(element) element.innerHTML = "00:00";
		} else {
		  time = new Date(msLeft);
		  hours = time.getUTCHours();
		  mins = time.getUTCMinutes();
		  if(element) element.innerHTML = (hours ? hours + ':' + twoDigits(mins) : mins) + ':' + twoDigits(time.getUTCSeconds());
		  setTimeout(updateTimer, time.getUTCMilliseconds() + 500);
		}
	  }
	  element = document.getElementById(elementName);
	  endTime = (+new Date) + 1000 * (60*minutes + seconds) + 500;
	  updateTimer();
	}
	countdown("countdown", 5, 0);
</script>

<?php require("footer.php");?>
