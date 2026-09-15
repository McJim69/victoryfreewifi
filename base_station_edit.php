<?php 
	require("connect.php"); 
				
	$base="";
	if(isset($_GET["base_stations"]) && $_GET["base_stations"]!="")
		$base=" and bst_id='".(int)$_GET["base_stations"]."' ";
												
	$ex = $link->query("select * from base_stations where bst_id=bst_id $base limit 1");
	$rs = $ex->fetch_array();

	// Fetch all sites for the dropdown
	$sitesQuery = $link->query("SELECT sid, mcode, barangay, place, coordinates, ip_address FROM sites ORDER BY mcode, barangay");
	$sitesList = [];
	while ($s = $sitesQuery->fetch_assoc()) {
		$sitesList[] = $s;
	}
?>

<div style="width: 100%; max-width: 600px; padding: 15px;">
	<div class="text-center" style="margin-bottom: 20px;">
		<img style="height:60px" src="assets/img/logo_2.png"/>
	</div>
	<h4 class="text-success text-center">EDIT BASE STATION</h4>
	<h6 class="text-dark text-center" style="margin-bottom: 25px;">[ <?php echo htmlspecialchars($rs["station_name"]); ?> ]</h6>
	
	<form action='base_station_edit_proc.php' method='POST' enctype='multipart/form-data'>
		<input type='hidden' name='bst_id' value='<?php echo $rs[0]; ?>'>		
		<input type="hidden" name="stn_id" id="edit_hidden_stn_id" value="">
		<input type="hidden" name="station_name" id="edit_hidden_station_name" value="">

		<div class="row">
			<div class="col-lg-6" style="margin-top:5px">
				<small class="text-success"> Source Site (Select to auto-fill)
					<select class="form-control" id="edit_siteSelector" onchange="edit_autoFillSite()">
						<option value="" selected>-- Keep Current --</option>
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
				<small class="text-success"> Station Type
					<select class="form-control" name="station_type">
						<option value="">-- Keep Current --</option>
						<option value="ABS">Wide-Area Base Station</option>
						<option value="MBS">Municipal Base Station</option>
						<option value="BBS">Barangay Base Station</option>
						<option value="REL">Small Relay Station</option>
					</select>
				</small>
			</div>
		</div>
		<div class="row" style="margin-top: 10px;">
			<div class="col-lg-6" style="margin-top:5px">
				<small class="text-success"> Coordinates
					<input type="text" class="form-control" name="coordinates" id="edit_input_coordinates" value="<?php echo htmlspecialchars($rs["coordinates"]); ?>" placeholder="Coordinates">
				</small>
			</div>
			<div class="col-lg-6" style="margin-top:5px">
				<small class="text-success"> Tower Height
					<input type="text" class="form-control" name="tower_height" id="edit_input_tower_height" value="<?php echo htmlspecialchars($rs["tower_height"]); ?>" placeholder="Tower Height">
				</small>
			</div>
		</div>

		<div class="row" style="margin-top: 10px;">
			<div class="col-lg-6" style="margin-top:5px">
				<small class="text-success"> Elevation
					<input type="text" class="form-control" name="elevation" id="edit_input_elevation" value="<?php echo htmlspecialchars($rs["elevation"]); ?>" placeholder="Elevation">
				</small>
			</div>
			<div class="col-lg-6" style="margin-top:5px">
				<small class="text-success"> IP Address
					<input type="text" class="form-control" name="ip_address" id="edit_input_ip_address" value="<?php echo htmlspecialchars($rs["ip_address"]); ?>" placeholder="IP Address">
				</small>
			</div>
		</div>
		
		<div class="row" style="margin-top: 25px;">
			<div class="col-12 text-center">
				<button type="SUBMIT" class="btn btn-success" name="upDate">Update Base Station</button>
			</div>
		</div>
	</form>
</div>

<script>
	const edit_sitesData = <?php echo json_encode($sitesList); ?>;

	function edit_autoFillSite() {
		const selector = document.getElementById('edit_siteSelector');
		const sid = selector.value;
		
		if (!sid) {
			document.getElementById('edit_hidden_stn_id').value = "";
			document.getElementById('edit_hidden_station_name').value = "";
			return;
		}

		const site = edit_sitesData.find(s => s.sid == sid);
		
		if (site) {
			document.getElementById('edit_hidden_stn_id').value = site.sid;
			document.getElementById('edit_hidden_station_name').value = site.mcode + " - " + site.barangay + " (" + site.place + ")";
			
			document.getElementById('edit_input_coordinates').value = site.coordinates ? site.coordinates : "";
			document.getElementById('edit_input_ip_address').value = site.ip_address ? site.ip_address : "";
		}
	}
</script>