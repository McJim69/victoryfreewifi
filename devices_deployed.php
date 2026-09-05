<?php
	require("connect.php");
	require("header.php");
?>

<body>

<?php 
	
	require("menunav.php");

	$value = isset($_GET['value']) ? $_GET['value'] : "";

	if(isset($_POST["b_search"])){
		$value=$_POST["t_search"];
	}

	$rec = 2000;
	$p = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;

	if ($p > 1) {
		$to = $rec;
		$from = ($p * $rec) - $rec;
		$i = (($p - 1) * $rec) + 1;
	} else {
		$to = $rec;
		$from = 0;
		$i = 1;
		$p = 1;
	}

	$ex=$link->query("select * from sites_detail d where 
	   (d.id like'%".$value."%' or
		d.site_id like'%".$value."%' or
		d.device_code like'%".$value."%' or
		d.device_name like'%".$value."%' or
		d.serial_mac like'%".$value."%') order by device_name LIMIT $from,$to ");
	//
?>

<script>setActive("admin");</script>
<script>setActive("sitess");</script>

<style>
	.devcon{
		padding:10px;
		cursor:pointer;
		font-size:14px;
		position:relative;
		border-radius:5px;
		border:1px solid #bbb;
		margin:8px -4px 8px -4px;
		box-shadow: rgba(60, 64, 67, 0.3) 0px 1px 2px 0px, rgba(60, 64, 67, 0.15) 0px 2px 6px 2px;
	}
	.devcon:hover{
		box-shadow: rgba(0, 0, 0, 0.19) 0px 10px 20px, rgba(0, 0, 0, 0.23) 0px 6px 6px;
		background: darkred;
		color:#fff;
	}
</style>

<main id="main">
	<section id="breadcrumbs" class="breadcrumbs" >
		<div class="container" style="margin-bottom:-10px">
			<ol>
				<li><a href="index.php">Home</a></li>
				<li><a href="admin.php">Admin</a></li> 
				<li><a href="categories.php">Category</a></li>
				<li><a href="devices.php">Devices</a></li> 
				<li><a href="devices_deployed.php">Deployed</a></li>
			</ol>
			<form method="post" enctype="multipart/form-data">
			<h2><a href="devices_deployed.php">Deployed Devices</a> &nbsp; 
				<input type="text" class="btn-device" placeholder="Enter a keyword" name="t_search" id="t_search" value="<?php if (!empty($_POST["t_search"])) { echo htmlspecialchars($_POST["t_search"]);}?>" required>
				<input type="submit" class="btn-rollout" name="b_search" value="Search" style="padding:8px">
			</h2>
		</div>
	</section>
	<section style="margin-top:-45px;min-height:550px;" >
		<div class="container" data-aos="fade-up">
			<div class="row">		
			<?php	
				$val1='';
				$val2='';
				$rep1='';
				$rep2='';
				
				$val1=ucwords(strtolower($value));
				$val2=ucwords(strtoupper($value));

				if (isset($_POST["t_search"]) && $_POST["t_search"] === $value) {
					$rep1 = "<b style='color:#0014d0;background:#ffa0a0'>" . htmlspecialchars($val1) . "</b>";
					$rep2 = "<b style='color:#0014d0;background:#ffa0a0'>" . htmlspecialchars($val2) . "</b>";
				}
				
				if ($ex->num_rows > 0) {				

				while($rs=mysqli_fetch_array($ex)){	
					$did=$rs["id"];
					$sid=$rs["site_id"];
					$dev=$rs["device_id"];
					$cod=$rs["device_code"];
					$mac=$rs["serial_mac"];
					
					$macrep=preg_replace('/[^a-zA-Z0-9_ %\[\]\.\(\)%&-]/s', '', $mac);
					$macupr=strtoupper($macrep);
					
					$update = $link->query("update sites_detail set serial_mac = replace(serial_mac, '$mac', '$macupr')  where id='$did'");
					
					$dev=$link->query("select * from device d where d.device_id='$dev' and d.device_id=d.device_id ");

				$exs=$link->query("select * from sites s where s.sid='$sid' ");
					while($rss=mysqli_fetch_array($exs)){	
					$mun=$rss["mcode"];
					$bar=$rss["barangay"];
					$plc=$rss["place"];
					require("define_place.php");		
				
				echo"
				<div onclick=\"jump('site_details.php?sites=$sid')\" class='col-lg-3 col-md-6' id='div_$rs[0]'>									
					<div class='devcon'>
						<div style='position:absolute;top:5px;right:5px'>
							<img src='assets/img/items/$cod.jpg' style='height:99px;border-radius:5px'>
						</div>
						<h6>$i. <b>".str_replace($val1,$rep1,$rs["device_name"])."</b></h6>
						Site: $mun $bar<br>
						Place: $place<br>
							MAC/SN: ".str_replace($val2,$rep2,$rs["serial_mac"])."
					</div>			
				</div>";
				$i++;
				
					}	
				  }					

				} else {

					echo"
					<div style='text-align:center;color:red;font-size:25px'><br>
						<div>Searching... <b class='blinking'>$value</b></div>
						<div><img src='assets/img/no_records.jpg' style='border-radius:10px;width:400px'></div>
						<div>No records found! &nbsp; 
							<span class='text-primary' style='cursor:pointer' onclick=\"jump('devices_deployed.php')\"><small>Refresh</small></span>
						</div>
					</div>";
				}	
			?>
			</form>
			</div>		
		</div>
	</section>
</main>

<?php require("footer.php");?>

</body>

</html>

<script>	
	if ( window.history.replaceState ) {
	  window.history.replaceState( null, null, window.location.href );
	}
</script>