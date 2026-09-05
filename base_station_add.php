<?php
	error_reporting(0);
	require("connect.php");
	require("header_all.php");
    
	$querys = $link->query("SELECT MAX(bst_id) FROM base_stations");
	$result = $querys->fetch_array();
	$baseID = $result[0]+1;
		
    if(isset($_POST['baseSave'])){

		$bstid = $baseID;
		$bname = "".$_POST['mcode']." ".$_POST['barangay']." ".$_POST['base_type']."";	
		$coord = $_POST['coordinates'];	
		$heigh = $_POST['tower_height'];	
		$eleva = $_POST['elavation'];
		$ipadd = $_POST['ip_address'];

		$insert = $pdo->prepare("INSERT INTO base_stations (
			bst_id,
			station_name,
			coordinates,
			tower_height,
			elevation,
			ip_address)
			
			VALUES (
			
			:bstid,
			:bname,
			:coord,
			:heigh,
			:eleva,
			:ipadd)");

			$insert->bindParam(':bstid', $bstid);
			$insert->bindParam(':bname', $bname);
			$insert->bindParam(':coord', $coord);
			$insert->bindParam(':heigh', $heigh);
			$insert->bindParam(':eleva', $barid);
			$insert->bindParam(':ipadd', $ipadd);			
	
			$insert->execute();

		echo"<script>location.href='base_stations.php?base_stations=$baseID';</script>";
	}
?>

<body>

<?php require("menunav.php");?>

<script>setActive("sites");</script>

<form action="#" method="POST">

<main class="main">
    <section style="margin-top:90px;min-height:614px;">
		<div class="container"><h2 class="text-success">ADD BASE STATION <?php echo $baseID;?></h2>
			<div class="container" style="padding-bottom:15px;background:#eee;border:1px solid #bbb;border-radius:5px">	
				<div class="row" style="margin-top:10px">					
					<div class="col-lg-4" style="margin-top:5px">
						<small class="text-success"> Municipality
							<select class="form-control" name="mcode" required onchange="jump('?&barangays=<?php echo $_GET['barangays'] ?? ''; ?>&municipality='+this.value)">
								<option value="" selected>Municipality</option>
								<?php
									$barangay    = $_GET["barangays"]   ?? "";
									$municipality = $_GET["municipality"] ?? "";

									if ($barangay === "" || $barangay === "Barangays") {
										$ex2 = $link->query("SELECT mcode FROM barangays GROUP BY mcode ORDER BY mcode") 
											   or die(mysqli_error($link));
									} else {
										$barangay = $link->real_escape_string($barangay);
										$ex2 = $link->query("SELECT mcode FROM barangays WHERE barangay='$barangay' GROUP BY mcode ORDER BY mcode") 
											   or die(mysqli_error($link));
									}

									while ($rs2 = $ex2->fetch_assoc()) {
										if (!empty($rs2["mcode"])) {
											$mcode    = htmlspecialchars($rs2["mcode"]);
											$selected = ($municipality === $mcode) ? "selected" : "";
											echo "<option value=\"$mcode\" $selected>$mcode</option>";
										}
									}
								?>
							</select>
						</small>
					</div>
					<div class="col-lg-4" style="margin-top:5px">
						<small class="text-success"> Barangay
							<select class="form-control" name="barangay" required onchange="jump('?&barangays='+this.value+'&municipality=<?php echo $_GET['municipality'] ?? ''; ?>')">
								<option value="" selected>Barangays</option>
								<?php
									$municipality     = $_GET["municipality"] ?? "";
									$selectedBarangay = $_GET["barangays"]   ?? "";

									$municipalityEscaped = $link->real_escape_string($municipality);

									if ($municipality === "" || $municipality === "Municipality") {
										$query = "SELECT DISTINCT barangay FROM barangays ORDER BY barangay";
									} else {
										$query = "SELECT DISTINCT barangay FROM barangays WHERE mcode='$municipalityEscaped' ORDER BY barangay";
									}

									$ex2 = $link->query($query) or die(mysqli_error($link));

									while ($rs2 = $ex2->fetch_assoc()) {
										if (!empty($rs2["barangay"])) {
											$barangay = htmlspecialchars($rs2["barangay"]);
											$selected = ($selectedBarangay === $barangay) ? "selected" : "";
											echo "<option value=\"$barangay\" $selected>$barangay</option>";
										}
									}
								?>
							</select>
						</small>
					</div>
					<div class="col-lg-4" style="margin-top:5px">
						<small class="text-success"> Base Type
							<select class="form-control" name="base_type" required>
								<option value="">Base Station Type</option>
								<option value="REL">REL (Small Relay Station)</option>								
								<option value="BST">RBS (Relay Base Station)</option>
								<option value="BST">BST (Barangay Base Station)</option>
								<option value="MBS">MBS (Municipal Base Station)</option>								
							</select>
						</small>
					</div>			  
					<div class="col-lg-4" style="margin-top:5px">
						<small class="text-success"> Coordinates
							<input class="form-control" name="coordinates" placeholder="Coordinates">
						</small>
					</div>	
					<div class="col-lg-4" style="margin-top:5px">
						<small class="text-success"> Tower Height
							<input class="form-control" name="tower_height" placeholder="Tower Height">
						</small>
					</div>	
					<div class="col-lg-4" style="margin-top:5px">
						<small class="text-success"> Elevation
							<input class="form-control" name="Elevation" placeholder="Elevation">
						</small>
					</div>
					<div class="col-lg-4" style="margin-top:5px">
						<small class="text-success"> IP Address
							<input class="form-control" name="ip_address" placeholder="IP Address">
						</small>
					</div>					
					<div class="box-footer text-center" style="margin:20px">	
						<input style="margin-right:20px;width:100px" type="submit" onclick="jump('base_station_add.php')" value="Reset" class="btn btn-sm btn-primary">
						<a href="javascript:history.back()" style="width:100px" class="btn btn-sm btn-danger">Cancel</a>
						<input style="margin-left:20px;width:100px" type="submit" name="baseSave" value="Submit" class="btn btn-sm btn-success">
					</div>
				</div>
			</div>
		</div>
    </section>
</main>

</form>

<?php require("footer.php"); ?>