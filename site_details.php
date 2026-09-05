<?php 
	error_reporting(0);
	require("connect.php");
	require("header.php");	

	$rec=1;

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
						
	$site="";
	if($_GET["sites"]!="")
		$site=" and sid='".$_GET["sites"]."' ";
												
	$ex = $link->query("select * from sites where sid=sid $site order by sid limit $from,$to ");
	  
	while($rs = mysqli_fetch_array($ex)){	
	$ex = $link->query("select * from sites where sites.sid='$rs[0]' and sites.sid=sites.sid ");
	
	while($rs = mysqli_fetch_array($ex)){	
	
	$cont = $rs[0];
	$roll = sprintf("%04d", $cont);

	$conp = $rs["cont_person"];
	$conp = ucwords(strtolower($conp));

	$ex2=$link->query("select * from placement p where p.pcode='".$rs["place"]."'");
	$rs2=mysqli_fetch_array($ex2);		
	$place=$rs2["pname"];
		
	$ex3=$link->query("select mname from municipality m where m.mcode='".$rs["mcode"]."'");
	$rs3=mysqli_fetch_array($ex3);		
	$ctm=$rs3[0];
	
	// Ensure $rs["timest"] exists and is valid
	$timestamp = isset($rs["timest"]) ? $rs["timest"] : null;
	$elapse = "Unknown";

	if ($timestamp) {
		$dateTime = DateTime::createFromFormat("Y-m-d H:i:s", $timestamp);
		$now = new DateTime();

		if ($dateTime) {
			$timeDifference = $dateTime->diff($now);
			$formattedTime = $timeDifference->format("%y Y %m M %d D %h H %i Min");
			$formattedTime = preg_replace('/\b0+\s+\w+\b/', '', $formattedTime);
			$elapse = trim($formattedTime);
		}
	}

	// Site Status Update
	$hosts = (!empty($rs["ip_address"])) ? $rs["ip_address"] : "0.0.0.0";
	$ports = 443;
	$waitTime = 1;

	// Suppress warning and handle connection result
	$fp = @fsockopen($hosts, $ports, $errCode, $errStr, $waitTime);

	$status = $fp ? 1 : 0;
	$color = $status ? "darkgreen" : "darkred";
	$img = $status ? "green-circle.png" : "red-circle.png";
	$blink = $status ? " class='blinking'" : "";

	$link->query("UPDATE sites SET status = $status WHERE sid='" . $link->real_escape_string($rs[0]) . "'");

	$stats = "<img style='margin:-2px 0 0 0;padding:0;height:20px;width:20px;z-index:22' src='assets/img/$img'$blink>";
	$statc = "color:$color";
	$statb = "background:$color";
	$statr = "solid $color";
	$statx = ($status ? "Online!" : "Offline!") . " &nbsp; $elapse";

	if ($fp) {
		fclose($fp);
	}

	$divID="div_$rs[0]";
?>


<body>

<?php require("menunav.php");?>

<script>setActive("sites");</script>

<main id="main <?php echo $divID;?>" style="margin-bottom:-50px">
    <section class="breadcrumbs" >
		<div class="container">
			<ol>
				<li><a href="index.php">Home</a></li>
				<li><a href="sites.php">Sites</a></li>
				<li>Site Details</li>
			</ol>
			<?php 
				echo"<h2 style='$statc'>".$rs["mcode"]."-".$rs["barangay"]."-".$rs["place"]." &nbsp &nbsp &nbsp &nbsp  &nbsp ";
					if(isset($_SESSION['user'])){
						echo"<a rel='facebox' href='site_edit.php?sites=$rs[0]'><button class='btn btn-sm btn-success'>Update Site</button></a> &nbsp; ";
						echo"<button class='btn btn-sm btn-primary' onclick=\"jump('site_certificate.php?sites=$rs[0]')\">Certificate</button> &nbsp; ";
					}
					if (isset($_SESSION["type"]) && $_SESSION["type"] === "Admin") {	
						echo"<button class='btn btn-sm btn-danger' onclick=\"site_delete('$rs[0]')\">Delete Site</button>";
					}
				echo"</h2>";
			?>
		</div>
	</section>
	<section class="portfolio-details" >
		<div class="container" style="margin-top:-20px">
			<div class="portfolio-details-container">
				<div class="owl-carousel portfolio-details-carousel" style="<?php echo $statb;?>;padding:8px;border-radius:8px">
				<?php
					echo"<img class='sites-image' ";
					if(file_exists("assets/img/sites/primary/$rs[0].jpg"))		
					echo " src='assets/img/sites/primary/$rs[0].jpg?".date("h:i:s")."' />";
					else echo" src='assets/img/sample2.jpg' />";
					
					echo"<img class='sites-image' ";
					if(file_exists("assets/img/sites/linkplan/$rs[0].jpg"))	
					echo "src='assets/img/sites/linkplan/$rs[0].jpg?".date("h:i:s")."' />";
					else echo"src='assets/img/sample1.jpg?".date("h:i:s")."' />";

					echo"<img class='sites-image' ";
					if(file_exists("assets/img/sites/speedtest/$rs[0].jpg"))	
					echo "src='assets/img/sites/speedtest/$rs[0].jpg?".date("h:i:s")."' />";
					else echo"src='assets/img/speedtest.jpg' />";

					echo"<img class='sites-image' ";
					if(file_exists("assets/img/sites/additional/$rs[0].jpg"))	
					echo "src='assets/img/sites/additional/$rs[0].jpg?".date("h:i:s")."' />";
					else echo"src='assets/img/sample3.jpg' />";
				?>
				</div>
				
				<div class="portfolio-info" style="min-width:300px;border:2px <?php echo $statr;?>;
					padding:16px 16px 0 16px;border-radius:8px;background:#fff rgba(255, 000, 000, 0.2)">
					<div style="border-radius:5px;color:#fff;text-align:center;<?php echo $statb;?>;font-size:20px">
						<b>Site Information</b>
					</div>
					<div style="margin-top:5px">
						<span>
						<?php 
							echo" <div style='text-align:center'><b style='$statc'> $statx</b></div>";
							if(isset($_SESSION['user'])){
								echo"IP Address : ";
								if($rs["ip_address"]==""){ 
									echo"Not Detected"; 
								}else{
									echo"<a href='https://$hosts' title='Go to IP Address' target='_blank'>";
									echo"<b>$hosts</b></a>"; 
								}
							}
						?>
						</span>
					</div>
					<ul>			
					<?php 
						$ex4=$link->query("select stn_coordinates from barangays where barangay='".$rs["barangay"]."' and mcode='".$rs["mcode"]."' ")or die(mysqli_error($link));
						$rs4=mysqli_fetch_array($ex4);
						
						if($ctm!=="") echo"<li style='margin:0'>Municipality : <strong>$ctm</strong></li>";

						if($rs["barangay"]!=="") echo"<li style='margin:0'>Barangay	: <strong>".$rs["barangay"]."</strong></li>";

						if($place!=="") echo"<li style='margin:0'>Place : <strong>".$place."</strong></li>";					

						if($rs4[0]!=="") echo"<li style='margin:0'>Coordinates : <strong>".$rs4[0]."</strong></li>";

						if($rs["link_ap_bst"]!=="") echo"<li style='margin:0'>Link AP STN : <strong>".$rs["link_ap_bst"]."</strong></li>";						

						if($rs["inst_date"]!=="") echo"<li style='margin:0'>Installed On : <strong>".$rs["inst_date"]."</strong></li>";					

						if($rs["installer"]!=="") echo"<li style='margin:0'>Installer : <strong>".$rs["installer"]."</strong></li>";	

						if($rs["cont_person"]!=="" and $rs["cont_person"]!=="None") echo"<li style='margin:0'>Contact Person : <strong>$conp</strong></li>";

						if($rs["cont_number"]!=="" and $rs["cont_number"]!=="None") echo"<li style='margin:0'>Contact Number : <strong>".$rs["cont_number"]."</strong></li>";					

						if (isset($rs["ap_mesh"])) echo"<li style='margin:0'>AP Mesh MAC: <strong>".$rs["ap_mesh"]."</strong></li>";					

						if($rs["repair_date"]==!NULL) echo"<li style='margin:0'>Last Maintenance : <strong>".$rs["repair_date"]."</strong></li>";								 

						if($rs["remarks"]==!NULL) echo"<li style='margin:0'>Remarks : <strong>".$rs["remarks"]."</strong><br>";

						if(isset($_SESSION['user'])){ 
							echo"<div class='text-center'>";
							echo"<a rel='facebox' href='site_edit.php?sites=$rs[0]'><button style='width:100px;margin-top:10px' class='btn btn-sm btn-success'>Update</button></a> &nbsp; ";
							echo"<button style='width:100px;margin-top:10px' class='btn btn-sm btn-danger'   onclick=\"jump('sites_add_dev.php?sites=$rs[0]')\">Add Device</button> &nbsp; ";
							echo"<button style='width:100px;margin-top:10px' class='btn btn-sm btn-primary' onclick=\"jump('site_certificate.php?sites=$rs[0]')\">Certificate</button>";
							echo"</div>";
						} 
					?>
					</ul>
				</div>
			</div>
			
			<div class="portfolio-description">
			<?php
				echo"<h2 style='$statc'>Site Devices"; 
				if(isset($_SESSION['user'])){
				echo" <a href='sites_add_dev.php?sites=$rs[0]' class='btn btn-sm btn-danger'>Add Device</a>";}
				echo"</h2>";
				echo"<div style='overflow-x:auto'>";

				$exd=$link->query("SELECT * FROM sites_detail WHERE site_id='".$rs[0]."' ");
					if ($exd->num_rows > 0) {

					echo"<table class='table table-responsive'>
						<thead style='border:1px $statr;$statb;color:#eee'>
							<tr>
								<th style='$statb; text-align:center'>#</th>
								<th style='$statb'>DevName</th>
								<th style='$statb'>Category</th>
								<th style='$statb'>DevCode</th>
								<th style='$statb'>MAC / SN</th>
								<th style='$statb; text-align:center'>Action</th>
							</tr>
						</thead>";
						
						while($rsd=mysqli_fetch_array($exd)){
						
						$dname = $rsd["device_name"];
						$dcate = $rsd["device_category"];
						$dcode = $rsd["device_code"];
						$macsn = $rsd["serial_mac"];
						$clean = str_replace(":", "", $macsn);
						
						$cls="style='font-size:15px;border-bottom:1px $statr;height:20px;padding:4px'";
						
							echo"<tbody style='border:1px $statr'>";
								if($i%2==0) echo"<tr class='odd' id='tr_".$rsd[0]."' >"; else echo"<tr class='even' id='tr_".$rsd[0]."' >";
								echo"
									<td $cls class='text-center'>$i</small></td>
									<td $cls>$dname</td>
									<td $cls>$dcate</td>
									<td $cls>$dcode</td>
									<td $cls>$clean</td>
									<td $cls class='text-center'>
										<a rel='facebox' href='device_view.php?sites_detail=$rsd[0]' title='View'><img src='assets/img/_view.png' style='height:17px;margin-top:-5px'/></a>&nbsp;&nbsp; ";
											if (isset($_SESSION["type"]) && $_SESSION["type"] === "Admin") {
												echo"<input onclick=\"device_delete('$rsd[0]');\" type=image src='assets/img/_delete.png' style='margin-bottom:-6px;height:23px;padding:0' title='Delete'/>";
											}	
										echo"
									</td>";
								echo"</tr>";
							echo"</tbody>";
							$i++;
						}	
					} else { echo "<i>No Device found.</i>"; 
						if(isset($_SESSION['user'])){
							echo" Please <a href='sites_add_dev.php?sites=$rs[0]'><b>Add Device</b></a> to this Site."; 
						}
					}
					echo"</table>";
				echo"</div>";
			?>
			</div>
		</div>
	</section>
</main>

<?php } } require("footer.php");?>

</body>

</html>

<script>
	function site_delete(sid){	
		if(confirm("Are you sure you want to Remove this Site?")){
			xmlhttp.onreadystatechange=function(){
				if (xmlhttp.readyState==4 && xmlhttp.status==200){
					if(xmlhttp.responseText=="Success"){
					$("#div_"+sid).animate({
					opacity:0
					},500);
				}else{
					alert(xmlhttp.responseText);
				}
					$("#div_"+sid).animate({
						opacity:0
					},500);
				}
			}					
			xmlhttp.open("GET","delete_site.php?sid="+sid,true);
			xmlhttp.send();
		}
	}

	function device_delete(id){	
		if(confirm("Are you sure you want to Remove this Device?")){
			xmlhttp.onreadystatechange=function(){
				if (xmlhttp.readyState==4 && xmlhttp.status==200){
					if(xmlhttp.responseText=="Success"){
						$("#tr_"+id).animate({
					opacity:0
					},500);
				}else{
					alert(xmlhttp.responseText);
					}
					$("#tr_"+id).animate({
					opacity:0
					},500);
				}
			}					
			xmlhttp.open("GET","delete_device.php?id="+id,true);
			xmlhttp.send();
		}
	}
</script>