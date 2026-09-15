<?php
	error_reporting(0);
	require("connect.php");
    
	$querys = $link->query("SELECT MAX(bst_id) FROM base_stations");
	$result = $querys->fetch_array();
	$baseID = $result[0]+1;
		
    if (isset($_POST['baseSave'])) {

		$stn_id = $_POST['stn_id'];
		$bname = $_POST['station_name'];
		$stype = $_POST['station_type'];
		$coord = $_POST['coordinates'];	
		$heigh = $_POST['tower_height'];	
		$eleva = $_POST['elevation'];
		$ipadd = $_POST['ip_address'];

		$insert = $pdo->prepare("INSERT INTO base_stations (
			stn_id,
			station_name,
			station_type,
			coordinates,
			tower_height,
			elevation,
			ip_address)
			
			VALUES (
			
			:stnid,
			:bname,
			:stype,
			:coord,
			:heigh,
			:eleva,
			:ipadd)");

			$insert->bindParam(':stnid', $stn_id);
			$insert->bindParam(':bname', $bname);
			$insert->bindParam(':stype', $stype);
			$insert->bindParam(':coord', $coord);
			$insert->bindParam(':heigh', $heigh);
			$insert->bindParam(':eleva', $eleva);
			$insert->bindParam(':ipadd', $ipadd);			
	
			$insert->execute();
			$new_bst_id = $pdo->lastInsertId();

		echo "<script>location.href='base_stations.php?base_stations=$new_bst_id';</script>";
	}
	
	// Fetch all sites for the dropdown
	$sitesQuery = $link->query("SELECT sid, mcode, barangay, place, coordinates, ip_address FROM sites ORDER BY mcode, barangay");
	$sitesList = [];
	while ($s = $sitesQuery->fetch_assoc()) {
		$sitesList[] = $s;
	}
	
	require("header_all.php");
	require("menunav.php");
?>

<script>setActive("sites");</script>

<form action="#" method="POST">
<!-- Hidden fields to store stn_id and station_name -->
<input type="hidden" name="stn_id" id="hidden_stn_id" value="">
<input type="hidden" name="station_name" id="hidden_station_name" value="">

<main class="main" style="min-height:614px;">
    <section style="margin-top:90px;">
		<div class="container"><h2 class="text-success">ADD BASE STATION <?php echo $baseID;?></h2>
			<div class="container" style="padding-bottom:15px;background:#eee;border:1px solid #bbb;border-radius:5px">	
				<div class="row" style="margin-top:10px">					
					
					<div class="col-lg-6" style="margin-top:5px">
						<small class="text-success"> Select Source Site
							<select class="form-control" id="siteSelector" required onchange="autoFillSite()">
								<option value="" selected>-- Choose an existing site --</option>
								<?php
									foreach ($sitesList as $site) {
										$label = $site['mcode'] . " - " . $site['barangay'] . " (" . $site['place'] . ")";
										echo "<option value='{$site['sid']}'>{$label}</option>";
									}
								?>
							</select>
						</small>
					</div>

					<div class="col-lg-6" style="margin-top:5px">
						<small class="text-success"> Select BS Type
							<select class="form-control" name="station_type" required>
								<option value="" selected>-- Choose BS Type --</option>
								<option value="ABS">Wide-Area Base Station</option>
								<option value="MBS">Municipal Base Station</option>
								<option value="BBS">Barangay Base Station</option>
								<option value="REL">Small Relay Station</option>
							</select>
						</small>
					</div>	
					<div class="col-lg-3" style="margin-top:5px">
						<small class="text-success"> Coordinates
							<input class="form-control" name="coordinates" id="input_coordinates" placeholder="Coordinates">
						</small>
					</div>	
					<div class="col-lg-3" style="margin-top:5px">
						<small class="text-success"> Tower Height
							<input class="form-control" name="tower_height" id="input_tower_height" placeholder="Tower Height">
						</small>
					</div>	
					<div class="col-lg-3" style="margin-top:5px">
						<small class="text-success"> Elevation
							<input class="form-control" name="elevation" id="input_elevation" placeholder="Elevation">
						</small>
					</div>
					<div class="col-lg-3" style="margin-top:5px">
						<small class="text-success"> IP Address
							<input class="form-control" name="ip_address" id="input_ip_address" placeholder="IP Address">
						</small>
					</div>					
					
					<div class="col-12 box-footer text-center" style="margin-top:30px">	
						<input style="margin-right:20px;width:100px" type="button" onclick="location.reload()" value="Reset" class="btn btn-sm btn-primary">
						<a href="javascript:history.back()" style="width:100px" class="btn btn-sm btn-danger">Cancel</a>
						<input style="margin-left:20px;width:100px" type="submit" name="baseSave" value="Submit" class="btn btn-sm btn-success">
					</div>
				</div>
			</div>
		</div>
    </section>
</main>

</form>

<script>
	// Pass PHP array to Javascript
	const sitesData = <?php echo json_encode($sitesList); ?>;

	function autoFillSite() {
		const selector = document.getElementById('siteSelector');
		const sid = selector.value;
		
		if (!sid) {
			// Clear fields if nothing selected
			document.getElementById('hidden_stn_id').value = "";
			document.getElementById('hidden_station_name').value = "";
			document.getElementById('input_coordinates').value = "";
			document.getElementById('input_elevation').value = "";
			document.getElementById('input_ip_address').value = "";
			return;
		}

		// Find the selected site in our JSON array
		const site = sitesData.find(s => s.sid == sid);
		
		if (site) {
			document.getElementById('hidden_stn_id').value = site.sid;
			document.getElementById('hidden_station_name').value = site.mcode + " - " + site.barangay + " (" + site.place + ")";
			
			// Fill the inputs if data exists, otherwise leave empty
			document.getElementById('input_coordinates').value = site.coordinates ? site.coordinates : "";
			document.getElementById('input_ip_address').value = site.ip_address ? site.ip_address : "";
			
			// Tower height and elevation aren't on standard sites, so we leave them blank
			document.getElementById('input_elevation').value = ""; 
			// document.getElementById('input_tower_height').value = ""; 
		}
	}
</script>

<?php require("footer.php"); ?>