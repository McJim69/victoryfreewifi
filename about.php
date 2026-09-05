<?php 
	require("connect.php");
	require("header_all.php");
	require("sites_count.php");
?>

<script>
	function printF(){		
		getID('topbar').style.display='none';
		getID('header').style.display='none';
		getID('main').style.display='none';
		getID('about').style.display='none';
		getID('footer').style.display='none';
		getID('print-header').style.display='block';
		getID('print-footer').style.display='block';
		$(".fixed-top").css("display","none");
		$(".back-to-top").css("display","none");
		$(".container").css("display","none");
		$(".back-to-top").css("display","none");
		$(".report-header").css("display","none");
		$(".close").css("display","none");
		$(".hid").css("display","none");

	window.print();
		getID('topbar').style.display='block';
		getID('header').style.display='block';
		getID('main').style.display='block';
		getID('about').style.display='block';
		getID('footer').style.display='block';
		getID('print-header').style.display='none';
		getID('print-footer').style.display='none';
		$(".fixed-top").css("display","block");
		$(".container").css("display","block");
		$(".back-to-top").css("display","block");
		$(".report-header").css("display","block");
		$(".close").css("display","block");
		$(".hid").css("display","block");
	}
</script>

<body>

<?php require("menunav.php");?>
 
<script>setActive("about");</script>

<?php 
	$total = $allBAR;
	$brgys = $totBAR;
	$percent1 = ($brgys / $total) * 100;
	$percent2 = 100 - ($percent1);
	$dataPoints1 = array(
		array("label" => "Accomplishment", "y" => $percent1),
		array("label" => "Target Remaining", "y" => $percent2)
	);
?>

<script>
	window.onload = function () {
		var chart1 = new CanvasJS.Chart("chartContainer1", {
			animationEnabled: true,
		//	title: { text: "Out of <?php echo $allBAR;?> Barangays" },
			data: [{
				type: "doughnut",
				yValueFormatString: "#,##0.00\"%\"",
				indexLabel: "{label} ({y})",
				dataPoints: <?php echo json_encode($dataPoints1, JSON_NUMERIC_CHECK); ?>
			}]
		});
		chart1.render();
	}
</script>

<main id="main">
    <section id="about" class="about">
		<div class="container" data-aos="fade-up">
			<div class="section-title">
				<h2>About Us</h2>
				<p>Victory Free WiFi - Internet sa Barangay Project</p>
			</div>
			<div class="row content">
				<div class="col-lg-6">
					<p style="text-align:justify">
						<strong>Internet sa Barangay Project</strong> is a provincial broadband initiative led by the 
						project-oriented former Governor Victor J. Yu during his term, and continued by former Congresswoman 
						Divina Grace C. Yu, who now serves as Governor. Both leaders are dedicated to advancing this project, 
						emphasizing the government’s commitment to providing free internet access—even in remote communities.	
					</p>
					<p style="text-align:justify">
						<strong>Scope of Service.</strong>
						Currently, over 8,000 average daily users, with over 2,000 concurrent users, benefit from our services. 
						Nevertheless, we continue to strive for service quality and expand our reach, particularly in 
						remote communities. Our ultimate goal is to serve the 600+ barangay centers and schools across the province.			
					</p>
				</div>
				<div class="col-lg-6 pt-4 pt-lg-0">
					<p style="text-align:justify">
						<strong>Zamboanga del Sur</strong> comprises <b><?php echo $allMUN;?></b> municipalities with a total of <b><?php echo $allBAR;?></b> barangays. 
						The Victory Free WiFi initiative currently has deployed 
						<b><?php echo $totALL;?></b> sites, serving 
						<b><?php echo $totMUN;?></b> municipalities and 
						<b><?php echo $totBAR;?></b> barangays. 
					</p>
					<div class="row">
						<div class="col">
							<ul>
							  <li><i class="ri-check-double-line"></i>Brgy/Mun Halls: <strong><?php echo $totBMH ;?></strong></li>
							  <li><i class="ri-check-double-line"></i>Public Schools: <strong><?php echo $totSCH ;?></strong></li>
							  <li><i class="ri-check-double-line"></i>Other Stations: <strong><?php echo $totOTH ;?></strong></li>
							</ul>
							<a rel="facebox" href="reportModal.php" >
								<button class='btn-rollout btn-sm btn-light text-primary' style="margin-top:5px">
									View Reports
								</button> 
							</a>

						</div>
						<div class="col">
							<div id="chartContainer1" style="height:160px;width:100%;"></div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</main>
	
<?php require("footer.php");?>

</body>

</html>