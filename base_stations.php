<?php 
	require("connect.php");

//  Safe defaults for GET/POST
//	$value = $_GET['value'] ?? "";
//	$base  = "";

//	if (!empty($_GET["base_stations"])) {
//		$base = " AND bst_id='" . $link->real_escape_string($_GET["base_stations"]) . "' ";
//	}

//	if (isset($_POST["b_search"])) {
//		$value = $_POST["t_search"] ?? "";
//	}

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
			$base ORDER BY station_name LIMIT $from,$to";

	$ex = $link->query($sql);
	
	require("header_all.php");
	require("menunav.php");
?>

<script>setActive("bst");</script>
<script>setActive("sites");</script>

<style>
	body {background: rgba(255, 000, 000, 0.2) url(assets/img/about-bg.png);}
	.bsbox {
		padding: 15px;
		background: #fff;
		position: relative;
		border-radius: 8px;
		border: 1px solid #ddd;
		box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
		transition: all 0.3s ease;
	}
	.bsbox:hover {	
		box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
		transform: translateY(-5px);
		border-color: #007bff;
	}
	.bsbox-controls {
		position: absolute;
		top: 56.1%;
		left: 50%;
		transform: translate(-50%, -50%);
		opacity: 0;
		transition: opacity 0.3s ease;
		width: 80%;
		background:#bbb;
		width:90%;
		padding:0;
		text-align: center;
		z-index: 10;
	}
	.bsbox:hover .bsbox-controls {
		opacity: 1;
	}
</style>
	
<?php require("menunav.php");?>	
	<div class="container" style="position: sticky; top: 120px; z-index: 1020;">
		<div class="row">
			<div class="col-lg-3">
				<form method="post" enctype="multipart/form-data">
				<div class="btn-group" style="width:100%;box-shadow: rgba(0, 0, 0, 0.16) 0px 3px 6px, rgba(0, 0, 0, 0.23) 0px 3px 6px">
					<input style="width:75%;border:1px solid #bbb;padding:4px 4px 4px 12px" type="text" class="btn btn-light" placeholder="Type a keyword" name="t_search" id="t_search" value="<?php if (!empty($_POST["t_search"])) {echo htmlspecialchars($_POST["t_search"]);}?>">
					<button style="width:25%;border:1px solid #bbb;padding:4px" type="submit" class="btn btn-danger" name="b_search">
						<i class="fa fa-search text-white"></i>
					</button>		
				</div>
				</form>	
			</div>	
			<div class="col-lg-3">
				<div class="btn-group" style="width:100%;box-shadow: rgba(0, 0, 0, 0.16) 0px 3px 6px, rgba(0, 0, 0, 0.23) 0px 3px 6px">
					<?php
						if($_SESSION["type"]=="Admin"){
							echo"
							<button onclick=\"jump('base_station_add.php')\" style='width:75%;border:1px solid #bbb;padding:4px' class='btn btn-light'>
								+ADD Base Station
							</button>
							<button onclick=\"jump('base_station_add.php')\" style='width:25%;border:1px solid #bbb;padding:4px' class='btn btn-danger'>
								<i class='fa fa-tower-cell text-white'></i>
							</button>";
						}else{
							echo"
							<button onclick=\"jump('sites_list.php')\" style='width:75%;border:1px solid #bbb;padding:4px' class='btn btn-light'>
								Installation List
							</button>
							<button onclick=\"jump('sites_list.php')\" style='width:25%;border:1px solid #bbb;padding:4px' class='btn btn-danger'>
								<i class='fa fa-wifi text-white'></i>
							</button>
							";
						}
					?>	
				</div>
			</div>			
			<div class="col-lg-3">
				<div class="btn-group" style="width:100%;box-shadow: rgba(0, 0, 0, 0.16) 0px 3px 6px, rgba(0, 0, 0, 0.23) 0px 3px 6px">
					<?php
						if($_SESSION["type"]=="Admin"){
							echo"
							<button onclick=\"jump('addRollout.php')\" style='width:75%;border:1px solid #bbb;padding:4px' class='btn btn-light'>
								+ADD Installation
							</button>
							<button onclick=\"jump('addRollout.php')\" style='width:25%;border:1px solid #bbb;padding:4px' class='btn btn-danger'>
								<i class='fa fa-wifi text-white'></i>
							</button>";
						}else{
							echo"
							<button style='width:75%;border:1px solid #bbb;padding:4px' class='btn btn-light'>
								<a style='color:#000' rel='facebox' href='reportModal2.php'>Summary Report</a>
							</button>
							<button style='width:25%;border:1px solid #bbb;padding:4px' class='btn btn-danger'>
								<a rel='facebox' href='reportModal2.php'><i class='fas fa-edit text-white'></i></a>
							</button>
							";
						}
					?>						
				</div>
			</div>
			<div class="col-lg-3">
				<div class="btn-group" style="width:100%;box-shadow: rgba(0, 0, 0, 0.16) 0px 3px 6px, rgba(0, 0, 0, 0.23) 0px 3px 6px">
					<?php
						if($_SESSION["type"]=="Admin"){
							echo"
							<button onclick=\"jump('backup.php')\" style='width:75%;border:1px solid #bbb;padding:4px' class='btn btn-light'>
								Initialize Backup
							</button>
							<button onclick=\"jump('backup.php')\" style='width:25%;border:1px solid #bbb;padding:4px' class='btn btn-danger'>
								<i class='fa fa-database text-white'></i>
							</button>";
						}else{
							echo"
							<button onclick=\"jump('installers.php')\" style='width:75%;border:1px solid #bbb;padding:4px' class='btn btn-light'>
								View Technicians
							</button>
							<button onclick=\"jump('installers.php')\" style='width:25%;border:1px solid #bbb;padding:4px' class='btn btn-danger'>
								<i class='fa fa-cog text-white'></i>
							</button>
							";
						}
					?>						
				</div>
			</div>				
		</div>
	</div>
</header>

<main id="main" style="margin-top:120px;min-height:560px">
	<div class="container">
		<div class="row">
		<?php 
			$val = '';
			$rep = '';
			$val = ucwords(strtolower($value));

		//	if (isset($_POST["t_search"]) && $_POST["t_search"] === $value) {
		//		$rep = "<b style='color:#0014d0;background:#ffa0a0'>$val</b>";
		//	}
						
			if ($ex->num_rows > 0) {
				
			while($rs=mysqli_fetch_array($ex)){
				
			$bsid=$rs[0];
			$base=$rs["station_name"];
			$type=$rs["station_type"];	
			$ipad=$rs["ip_address"];	
			$coor=$rs["coordinates"];
			$hght=$rs["tower_height"];
			$elev=$rs["elevation"];	
			
			$bsap=$link->query("select count(link_ap_bst) from sites where link_ap_bst='$base'");
			$totb=mysqli_fetch_array($bsap);
			$tots=number_format($totb[0]);
		?>
			<div class="col-lg-3" style="margin-top:20px" id="div_<?php echo $bsid;?>">			
				<div class="bsbox">	
				<div class="text-truncate" style="padding:5px;background:#bbb;border-radius:5px">
					<b class="text-danger"> &nbsp;<?php echo $i ;?>.</b> 
					<strong class="text-dark"> 
						<?php echo str_replace($val,$rep,$base) ;?>
					</strong>
				</div>
				<?php
					if (isset($_SESSION["type"]) && $_SESSION["type"] === "Admin" && isset($bsid)) {
						echo "<div class='bsbox-controls'>
								<a rel='facebox' href='base_station_edit.php?base_stations=".(int)$bsid."'>
									<input style='width:80px' class='btn btn-sm btn-primary' value='Update'>
								</a> &nbsp;
								<input style='margin-top:3px;width:80px' class='btn btn-sm btn-danger' value='Delete' onclick=\"deleteBase('$bsid');\">
							</div>";
					}
				?>
				<div style="margin:5px">
					<div>Type: <b>
					<?php
						switch ($type) {
							case "ABS":	echo "Wide-Area Base Station"; break;
							case "MBS":	echo "Municipal Base Station"; break;
							case "BBS": echo "Barangay Base Station";  break;
							default:    echo "Small Relay Station";
						}
					?> </b>
					</div>
					<?php if($_SESSION["type"]=="Admin") { ?>
					<div>IP Address: <?php echo $ipad;?></div>
					<div>Coordinates: <?php echo $coor;?></div>
					<?php } ?>
					<div>Tower Height: <?php echo $hght;?></div>
					<div>Elevation: <?php echo $elev;?></div>
				</div>
				<div style="color:#121212;padding:5px;background:#bbb">
					&nbsp; Connected Stations: <strong class="text-danger"><?php echo $tots;?></strong>
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

<?php require("footer.php");?>
