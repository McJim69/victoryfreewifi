<?php 
	require("connect.php");
	require("header.php");	
?>

<script>
	function printF(){		
		getID('topbar').style.display='none';
		getID('header').style.display='none';
		getID('breadcrumbs').style.display='none';
		getID('portfolio-details').style.display='none';
		getID('toprint').style.display='block';
		getID('footer').style.display='none';
		$(".report-header").css("display","none");
		$(".back-to-top").css("display","none");

	window.print();
		getID('topbar').style.display='block';
		getID('header').style.display='block';
		getID('breadcrumbs').style.display='block';
		getID('portfolio-details').style.display='block';
		getID('toprint').style.display='none';
		getID('footer').style.display='block';
		$(".report-header").css("display","block");
		$(".back-to-top").css("display","block");
	}
</script>

<body>

<?php require("menunav.php");?>

<script>setActive("docs");</script>

<?php	
	$dphotos="Photos-Onsites";
	$summrep="Summary-Report";
	$rep2025="Status Report 2025";
	$rep2023="Status Report 2023";
	$servers="Core NET Topology";	
	$sites24="Installation Map 2024";
	$sites25="Installation Map 2025";	
	$project="Project Profile 2020";
	$propose="Project Profile 2022";
	
	if(isset($_POST["selected"])){
	   $post=$_POST["selected"];
	}else{
	   $post="Select Document";	
	}
?>
	
<form action="documents.php" method="post" enctype="multipart/form-data">

<section id="breadcrumbs" class="breadcrumbs" >
	<div class="container" style="margin-bottom:-10px">
		<ol>
			<li><a href="index.php">Home</a></li>
			<li><a href="documents.php">Documents</a></li>
		</ol>
		<h2>Documents &nbsp;
			<select style="text-align:left;padding:5px;border:1px solid #bbb" class="btn btn-sm btn-light" type="submit" name="selected" onchange="this.form.submit()">
				<option value="" selected="1"><?php echo $post;?></option>
				<option value="<?php echo $dphotos;?>"><?php echo $dphotos;?></option>
				<option value="<?php echo $summrep;?>"><?php echo $summrep;?></option>
				<option value="<?php echo $rep2025;?>"><?php echo $rep2025;?></option>
				<option value="<?php echo $rep2023;?>"><?php echo $rep2023;?></option>
				<option value="<?php echo $servers;?>"><?php echo $servers;?></option>
				<option value="<?php echo $project;?>"><?php echo $project;?></option>
				<option value="<?php echo $propose;?>"><?php echo $propose;?></option>
				<option value="<?php echo $sites24;?>"><?php echo $sites24;?></option>
				<option value="<?php echo $sites25;?>"><?php echo $sites25;?></option>				
			</select>
			<?php 
				if(isset($_POST["selected"])){
					echo"<input  
						type='button' 
						value='Clear' 
						class='btn btn-sm btn-light'
						style='border:1px solid #bbb' 
						onclick=\"jump('documents.php')\"
					>";
				}
			?>
		</h2>
		<div id="downloadProgress" class="progress" style="height: 25px; display: none;">
		  <div class="progress-bar progress-bar-striped progress-bar-animated bg-danger"
			   role="progressbar" style="width: 0%;" 
			   aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
			0%
		  </div>
		</div>		
	</div>
</section>
		
<?php 
	if(isset($_POST['selected'])){ 
	echo"<div style='margin-top:-30px'>";
		if($post==$dphotos){ 
			require("docsPhotos.php");
		}
		if($post==$summrep){ 
			require("docsReport.php");
		}
		if($post==$rep2025){ 
			require("docsStatus2025.php");
		}
		if($post==$rep2023){ 
			require("docsStatus2023.php");
		}		
		if($post==$servers){
			require("servers_topology.php");
		}
		if($post==$sites24){
			$file="UISP-Import-2024";
			require("docsInstMaps.php");
		}
		if($post==$sites25){
			$file="UISP-Import-2025";
			require("docsInstMaps.php");
		}		
		if($post==$project){ 
			require("docsProfile1.php");	
		}
		if($post==$propose){ 
			require("docsProfile2.php");
		}
	echo"</div>";
	}else{
?>

<section id="portfolio-details" class="portfolio-details">
<div class="container">
<div class="portfolio-details-container">

<div class="documents">

<p style="text-align:justify"><span style="font-size: 24px;"><strong>VICTORY FREE WIFI</strong></span></p>
<p style="text-align:justify"><span style="font-size: 14px;"><strong>Internet sa Barangay Project</strong></span></p>
<p style="text-align:justify">
	<span style="font-size: 20px;"><strong>RATIONALE<br></strong></span>
</p>
<p style="margin-top:-15px;text-align:justify">
	Over the past 4 years, the Victory Free WiFi project has transformed digital connectivity across 27 municipalities and more than 400 barangays, 
	bridging the digital divide and empowering communities through accessible internet services.
</p>
<p style="text-align:justify">
	<span style="font-size: 20px;"><b>Project Overview</b></span>
</p>
<p style="margin-top:-15px;text-align:justify">
	Launched on April 2021 with the mission to democratize internet access, Victory Free WiFi aimed to provide reliable, high-speed wireless connectivity 
	to underserved and geographically isolated areas. By strategically deploying public WiFi hotspots in government centers, schools, health facilities, 
	and public spaces, the project has become a cornerstone of digital inclusion.	
</p>
<p style="text-align:justify">
	<span style="font-size: 20px;"><b>Key Achievements</b></span>
</p>
<p style="margin-top:-15px;text-align:justify">
	<ul>
		<li><b>Coverage Expansion</b>: Successfully installed and maintained WiFi infrastructure across 27 municipalities and almost 400 barangays.</li>
		<li><b>Community Impact</b>:
			<ul>
				<li>Enabled remote learning for thousands of students.</li>
				<li>Facilitated access to government services and telehealth.</li>
				<li>Supported local businesses with digital tools and online presence.</li>
			</ul>
		</li>
		<li><b>Sustainability</b>: Leveraged partnerships with BLGUs, NGOs, and private sector stakeholders to ensure long-term viability and maintenance.</li>
	</ul>
</p>
<p style="text-align:justify">
	<span style="font-size: 20px;"><b>Technology & Infrastructure</b></span>
</p>
<p style="margin-top:-15px;text-align:justify">
	<ul>
		<li>Over 600 devices installed across more than 600 sites.</li>
		<li>Deployed solar-powered base stations in off-grid locations.</li>
		<li>Allocates 4Gbps dedicated lease line bandwidth from PLDT-iGate.</li>
		<li>Utilized AirFiber backhaul and Rocket Radio uplinks to reach remote areas.</li>
		<li>Implemented centralized network monitoring and bandwidth optimization.</li>
	</ul>
</p>	
<p style="text-align:justify">
	<span style="font-size: 20px;"><b>Outcomes & Future Directions</b></span>
</p>
<p style="margin-top:-15px;text-align:justify">
	<ul>
		<li>Educational Aide: Students can easily access the learning materials they need.</li>
		<li>Digital Literacy Growth: Increased tech adoption and digital skills among residents.</li>
		<li>Economic Uplift: Boosted local economies through e-commerce and online employment.</li>
		<li>Next Phase: Plans to expand to additional barangays and integrate smart city applications.</li>
	</ul>
</p>
<p style="text-align:justify">
	The Victory Free WiFi project stands as a model for inclusive digital transformation, proving that with vision, 
	collaboration, and innovation, connectivity can become a public good that uplifts entire communities.	
</p>
</div>

<?php } ?>

</div>

</div>
</div>
</section>

<?php require("footer.php");?>

</body>

</html>
