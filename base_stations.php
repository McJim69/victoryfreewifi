<?php 
	require("connect.php");
	require("header_all.php");

	// Safe defaults for GET/POST
	$value = $_GET['value'] ?? "";
	$base  = "";

	if (!empty($_GET["base_stations"])) {
		$base = " AND bst_id='" . $link->real_escape_string($_GET["base_stations"]) . "' ";
	}

	if (isset($_POST["b_search"])) {
		$value = $_POST["t_search"] ?? "";
	}

	$rec = 200;
	$p   = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;

	if ($p > 1) {
		$to   = $rec;
		$from = ($p * $rec) - $rec;
		$i    = (($p - 1) * $rec) + 1;
	} else {
		$to   = $rec;
		$from = 0;
		$i    = 1;
		$p    = 1;
	}

	$sql = "SELECT * FROM base_stations b 
			WHERE (b.bst_id LIKE '%".$link->real_escape_string($value)."%' 
			OR b.station_name LIKE '%".$link->real_escape_string($value)."%') 
			$base 
			ORDER BY bst_id DESC 
			LIMIT $from,$to";

	$ex = $link->query($sql);
?>

<style>
	.bsbox{
		padding:15px;
		background:#eee;
		position:relative;
		border-radius:5px;
		box-shadow: rgba(0, 0, 0, 0.16) 0px 3px 6px, rgba(0, 0, 0, 0.23) 0px 3px 6px;
	}
	.bsbox:hover{	
		color:#eee;
		background:darkred;
	}
</style>

<body style="background: rgba(255, 000, 000, 0.2) url(assets/img/about-bg.png)">

<?php require("menunav2.php");?>

<script>setActive("bst");</script>

<script>setActive("sites");</script>

<main id="main" style="margin-top:155px;min-height:560px">
	<div class="container">
		<div class="row">
		
		<?php 
			$val = '';
			$rep = '';
			$val = ucwords(strtolower($value));

			if (isset($_POST["t_search"]) && $_POST["t_search"] === $value) {
				$rep = "<b style='color:#0014d0;background:#ffa0a0'>$val</b>";
			}
						
			if ($ex->num_rows > 0) {
				
			while($rs=mysqli_fetch_array($ex)){
				
			$bsid=$rs[0];
			$base=$rs["station_name"];
			$coor=$rs["coordinates"];
			$hght=$rs["tower_height"];
			$elev=$rs["elevation"];	
			$ipad=$rs["ip_address"];	
			
			$bsap=$link->query("select count(link_ap_bst) from sites where link_ap_bst='$base'");
			$totb=mysqli_fetch_array($bsap);
			$tots=number_format($totb[0]);
		?>
			<div class="col-lg-3" style="margin-top:20px" id="div_<?php echo $bsid;?>">			
				<div 
					class="bsbox"
					id="div_<?php echo $bsid;?>"
					onmouseout="getID('div_controls_<?php echo $bsid;?>').style.visibility='hidden'" 
					onmousemove="getID('div_controls_<?php echo $bsid;?>').style.visibility='visible'"				
				>	
				<div style="padding:5px;background:#bbb;border-radius:5px">
					<b class="text-secondary"> &nbsp;<?php echo $i ;?>.</b> 
					<strong class="text-dark"> 
						<?php echo str_replace($val,$rep,$base) ;?>
					</strong>
				</div>
				<?php
					if (isset($_SESSION["type"]) && $_SESSION["type"] === "Admin" && isset($bsid)) {
						echo "<center>
							<div style='width:100%;position:absolute;top:75px;visibility:hidden' id='div_controls_$bsid'>
								<a rel='facebox' href='base_station_edit.php?base_stations=".(int)$bsid."'>
									<input style='width:80px' class='btn btn-sm btn-warning' value='Update'>
								</a> &nbsp;
								<input style='margin-top:3px;width:80px' class='btn btn-sm btn-warning' value='Delete' onclick=\"deleteBase('$bsid');\">
							</div>
						</center>";
					}
				?>
				<div style="margin:5px">
					<div>Elevation: <?php echo $elev;?></div>
					<div>Coordinates: <?php echo $coor;?></div>
					<div>Tower Height: <?php echo $hght;?></div>
					<div>IP Address: <?php echo $ipad;?></div>
				</div>
				<div style="color:#545454;padding:5px;background:#bbb">
					&nbsp; Connected Stations: <strong class="text-dark"><?php echo $tots;?></strong>
				</div>
				<div style="color:#545454;padding:5px;background:#eee;height:120px;overflow:auto">
					<?php
						$j=1;
						$bast=$link->query("select * from sites where link_ap_bst='$base'");
						while($rsbs=mysqli_fetch_array($bast)){	
							$said=$rsbs[0];
							$muni=$rsbs["mcode"];
							$brgy=$rsbs["barangay"];
							$site=$rsbs["place"];
							echo"
								<div>$j.
									<a href='site_details.php?sites=$said'>$muni-$brgy-$site</a>
								</div>";
							$j++;
						}
					?>
				</div>
			</div>	
		</div>	
		<?php $i++; } ?>
		</div>
		<?php 
			}else{
				echo"
				<div class='row justify-content-center'>
					<div class='col-lg-3 text-center' style='color:#fff;background-color:darkred;opacity:.5;margin-top:50px;padding:5px;border-radius:10px'>
						<img src='assets/img/no_records.jpg' style='width:100%'>
						<div style='margin-top:15px;font-size:20px'>
							Can't find <b>$value</b>...<br>No records found!
						</div>
					</div>
				</div>";
			}		
		?>		
	</div><br>
</main>

<?php require("footer.php");?>

</body>

</html>

<script>
	function deleteBase(bst_id){	
		if(confirm("Are you sure you want to Remove this Base Station?")){
			xmlhttp.onreadystatechange=function()
			{
				if (xmlhttp.readyState==4 && xmlhttp.status==200){
					if(xmlhttp.responseText=="Success"){
						$("#div_"+bst_id).animate({
					opacity:0
					},500);
				}else{
					alert(xmlhttp.responseText);
					}
					$("#div_"+bst_id).animate({
					opacity:0
					},500);
				}
			}					
			xmlhttp.open("GET","delete_base_station.php?bst_id="+bst_id,true);
			xmlhttp.send();
		}
	}

</script>
