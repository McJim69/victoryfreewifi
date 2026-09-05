<?php 
	require("connect.php"); 
	
	$rec = 1;
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
	$ii=1;
?>

<div style="width:350px;text-align:center">

<form action="site_edit_proc.php" method="POST" enctype="multipart/form-data">

<?php		
	while($rs = mysqli_fetch_array($ex)){	

	$hosts = $rs["ip_address"];

	if ($rs["status"] == 1) {
		$statb = "success"; 			// green button for online
		$statc = "color:darkgreen";     // green text for online
		$statx = "Online!";				// text for online
	} else {
		$statb = "danger";  			// red button for offline
		$statc = "color:darkred";       // red text for offline
		$statx = "Offline!";			// text for offline
	}

	echo"	
		<div class='text-center' style='font-size:20px;font-weight:bold'>
			".$rs["mcode"]." ".$rs["barangay"]." ".$rs["place"]."
		</div>
		<div>Site Status: $statx</div>			
	
		<div class='mt-5 mt-lg-0'>			
			<div class='row'>				
				<div style='padding-top:5px' class='col-md-6 form-group mt-3 mt-md-0'>
					<input type='hidden' class='form-control' name='sid' value='$rs[0]' />
					<input type='text' class='form-control' name='mcode' value='".$rs["mcode"]."' required >
				</div>
				<div style='padding-top:5px' class='col-md-6 form-group mt-3 mt-md-0'>
					<input type='text' class='form-control' name='barangay' value='".$rs["barangay"]."' required >
				</div>
				<div style='padding-top:5px' class='col-md-6 form-group mt-3 mt-md-0'>
					<select type='text' class='form-control' name='place' >
						<option value='".$rs["place"]."'>".$rs["place"]."</option>";
						$ex2=$link->query("select pcode from placement order by pcode")or die(mysqli_error($link));										
						while($rs2=mysqli_fetch_array($ex2)){
							echo"<option>$rs2[0]</option>";
						}
						echo"
					</select>
				</div>
				<div style='padding-top:5px' class='col-md-6 form-group mt-3 mt-md-0'>";
					$ex4=$link->query("select stn_coordinates from barangays where barangay='".$rs["barangay"]."' and mcode='".$rs["mcode"]."' ")or die(mysqli_error($link));
					$rs4=mysqli_fetch_array($ex4);
					echo"				
					<input type='text' class='form-control' name='coordinates' value='".$rs4[0]."' placeholder='Coordinates' required />
				</div>
				<div style='padding-top:5px' class='col-md-6 form-group mt-3 mt-md-0'>
					<select type='text' class='form-control' name='installer' required>
						<option value='".$rs["installer"]."'>";
							if($rs["installer"]=="NULL" || $rs["installer"]==""){
								echo"Installer";
							}else{
								echo $rs["installer"];
							}	echo"
						</option>";						
						$ex2=$link->query("select leader from installer")or die(mysqli_error($link));										
						while($rs2=mysqli_fetch_array($ex2)){
							$leader=$rs2[0];
							$leader=ucwords(strtolower($leader));
							echo"<option>$leader</option>";
						}
						echo"
					</select>
				</div>
				<div style='padding-top:5px' class='col-md-6 form-group mt-3 mt-md-0'>
					<input onfocus=\"(this. type='date')\" class='form-control' placeholder='Installation Date' name='inst_date' value='".$rs["inst_date"]."' />
				</div>			
				<div style='padding-top:5px' class='col-md-6 form-group mt-3 mt-md-0'>
					<input type='text' class='form-control' placeholder='Contact Person' name='cont_person' value='".$rs["cont_person"]."' />
				</div>
				<div style='padding-top:5px' class='col-md-6 form-group mt-3 mt-md-0'>
					<input type='text' class='form-control' placeholder='Contact Number' name='cont_number' value='".$rs["cont_number"]."' />
				</div>
				<div style='padding-top:5px' class='col-md-6 form-group mt-3 mt-md-0'>
					<select type='text' class='form-control' name='repair_team' >
						<option value='".$rs["repair_team"]."'>";
							if($rs["repair_team"]=="NULL" || $rs["repair_team"]==""){
								echo"Repair Team";
							}else{
								echo $rs["repair_team"];
							}	echo"
						</option>";						
						$ex2=$link->query("select leader from installer")or die(mysqli_error($link));										
						while($rs2=mysqli_fetch_array($ex2)){
							$leader=$rs2[0];
							$leader=ucwords(strtolower($leader));
							echo"<option>$leader</option>";
						}
						echo"
					</select>
				</div>
				<div style='padding-top:5px' class='col-md-6 form-group mt-3 mt-md-0'>
					<input onfocus=\"(this. type='date')\" placeholder='Repair Date' class='form-control' name='repair_date' value='".$rs["repair_date"]."' />
				</div>
				<div style='padding-top:5px' class='col-md-6 form-group mt-3 mt-md-0'>
					<input type='text' class='form-control' placeholder='Station IP Address' name='ip_address' value='".$rs["ip_address"]."' />
				</div>
				<div style='padding-top:5px' class='col-md-6 form-group mt-3 mt-md-0'>
					<select type='text' class='form-control' name='link_ap_bst' >
						<option value='".$rs["link_ap_bst"]."'>";
							if($rs["link_ap_bst"]==""){
								echo"Select AP STN";
							}else{
							echo $rs["link_ap_bst"];
							}
						echo"</option>";
						$ex2=$link->query("select station_name from base_stations order by station_name")or die(mysqli_error($link));										
						while($rs2=mysqli_fetch_array($ex2)){
							echo"<option>$rs2[0]</option>";
						}
						echo"
					</select>
				</div>
			</div>
			<div class='row'>
				<div class='form-group mt-3'>
					<input type='text' class='form-control' placeholder='Remarks' name='remarks' value='".$rs["remarks"]."' />
				</div>
			</div>
			<div class='row'>
				<div class='form-group mt-3'>
					<div style='text-align:center'>
						<button type='SUBMIT' class='form-control btn btn-$statb' name='upDate'>Update</button>
					</div>
				</div>
			</div>
		</div>";
		}		
	}			
?>					

</form>

</div>
