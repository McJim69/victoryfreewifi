<?php
	require("connect.php");
	require("header_all.php");

    function fill_device($pdo){
		$output= '';
		$select = $pdo->prepare("SELECT * FROM device order by device_id");
		$select->execute();
		$result = $select->fetchAll();

		foreach($result as $row){
			$output.='<option value="'.$row['device_id'].'">'.$row["device_name"].'</option>';
		}	return $output;
	}

    function base_station($pdo){
		$output= '';
		$select = $pdo->prepare("SELECT * FROM base_stations order by station_name");
		$select->execute();
		$result = $select->fetchAll();

		foreach($result as $row){
			$output.='<option value="'.$row['station_name'].'">'.$row["station_name"].'</option>';
		}	return $output;
	}
    
    if(isset($_POST['rollSave'])){

		$querys = $link->query("SELECT MAX(sid) FROM sites");
		$result = $querys->fetch_array();
		$siteID = $result[0]+1;

		$ex=$link->query("SELECT * FROM barangays WHERE barangay='".$_POST["barangay"]."' and mcode='".$_POST["mcode"]."'");
		$rs=mysqli_fetch_array($ex);
		
		$sidno = $siteID;
		$mcode = $_POST['mcode'];	
		$baran = $_POST['barangay'];	
		$barid = $rs['bid'];	
		$place = $_POST['place'];	
		$coord = $rs['stn_coordinates'];	
		$blink = $_POST['link_ap_bst'];	
		$itype = $_POST['inst_type'];	
		$idate = $_POST['inst_date'];	
		$insta = $_POST['installer'];	
		$ipadd = $_POST['ip_address'];
		$repdt = ''; 
		$reptm = '';
		$contp = $_POST['cont_person'];	
		$contn = $_POST['cont_number'];	
		$remar = $_POST['remarks']; 
		$stats = 1;
		$linkp = 0; 
		$primg = 0; 
		$adimg = 0; 
		$spimg = 0;
	
		$arr_devid = $_POST['deviceid'];
		$arr_dcode = $_POST['devicecode'];
		$arr_dcate = $_POST['devicecategory'];
		$arr_dname = $_POST['devicename'];
		$arr_stock = $_POST['devicestock'];
		$arr_dvqty = $_POST['quantity'];
		$arr_dunit = $_POST['deviceunit'];
		$arr_snmac = $_POST['serial_mac'];
		$ins_dates = $_POST['inst_date'];

		if($arr_dcode == ""){
			echo'
			<script type="text/javascript">
				jQuery(function validation(){
					swal("Warning", "Please Add + Device Form", "warning", {
						button: "Continue",
					});
				});
			</script>';
		} else {

		$insert = $pdo->prepare("INSERT INTO sites (
			sid,
			mcode,
			barangay,
			bid,
			place,
			coordinates,
			link_ap_bst,
			inst_type,
			inst_date,
			installer,
			ip_address,
			repair_date,
			repair_team,
			cont_person,
			cont_number,
			remarks,
			status,
			link_plan,
			loc_img,
			add_img,
			speedtest)
			
			VALUES (
			
			:sidno,
			:mcode,
			:baran,
			:barid,
			:place,
			:coord,
			:blink,
			:itype,
			:idate,
			:insta,
			:ipadd,
			:repdt,
			:reptm,
			:contp,
			:contn,
			:remar,
			:stats,
			:linkp,
			:primg,
			:adimg,
			:spimg)");

			$insert->bindParam(':sidno', $sidno);
			$insert->bindParam(':mcode', $mcode);
			$insert->bindParam(':baran', $baran);
			$insert->bindParam(':barid', $barid);
			$insert->bindParam(':place', $place);
			$insert->bindParam(':coord', $coord);
			$insert->bindParam(':blink', $blink);
			$insert->bindParam(':itype', $itype);
			$insert->bindParam(':idate', $idate);
			$insert->bindParam(':insta', $insta);
			$insert->bindParam(':ipadd', $ipadd);
			$insert->bindParam(':repdt', $repdt);
			$insert->bindParam(':reptm', $reptm);
			$insert->bindParam(':contp', $contp);
			$insert->bindParam(':contn', $contn);
			$insert->bindParam(':remar', $remar);
			$insert->bindParam(':stats', $stats);
			$insert->bindParam(':linkp', $linkp);
			$insert->bindParam(':primg', $primg);
			$insert->bindParam(':adimg', $adimg);
			$insert->bindParam(':spimg', $spimg);	
	
			$insert->execute();
			
			$link->query("INSERT INTO  sites_status VALUES (0, '".$sidno."', '".$ipadd."', 1, CURRENT_TIMESTAMP)");	

			if($siteID!=null){
				for($i=0; $i<count($arr_devid); $i++){

				$rem_qty = $arr_stock[$i] - $arr_dvqty[$i];

					if($rem_qty<0){
					echo'
						<script type="text/javascript">
							jQuery(function validation(){
								swal("Warning", "Input Data", "warning", {
									button: "Continue",
								});
							});
						</script>';
					}else{
						$update = $pdo->prepare("UPDATE device SET device_stock = '$rem_qty' WHERE device_id='".$arr_devid[$i]."'");
						$update->execute();
					}

					$insert = $pdo->prepare("INSERT INTO sites_detail (
						site_id, 
						device_id, 
						device_code, 
						device_category,
						device_name, 
						device_qty, 
						device_unit, 
						serial_mac, 
						inst_date) 
						
						VALUES (
						
						:sitid, 
						:devid, 
						:dcode, 
						:dcate,
						:dname, 
						:dvqty, 
						:dunit, 
						:snmac, 
						:idate)
					");

					$insert->bindParam(':sitid', $siteID);
					$insert->bindParam(':devid', $arr_devid[$i]);
					$insert->bindParam(':dcode', $arr_dcode[$i]);
					$insert->bindParam(':dcate', $arr_dcate[$i]);
					$insert->bindParam(':dname', $arr_dname[$i]);
					$insert->bindParam(':dvqty', $arr_dvqty[$i]);
					$insert->bindParam(':dunit', $arr_dunit[$i]);
					$insert->bindParam(':snmac', $arr_snmac[$i]);
					$insert->bindParam(':idate', $ins_dates);

					$insert->execute();
				}
				echo"<script>location.href='site_details.php?sites=".$siteID."';</script>";
			}
		}
	}
?>

<body>

<?php require("menunav.php");?>

<script>setActive("sites");</script>

<form action="#" method="POST">

<main class="main">
    <section style="margin-top:90px;min-height:614px;">
		<div class="container"><h2 class="text-success">ADD ROLLOUT</h2>
			<div class="container" style="padding-bottom:15px;background:#eee;border:1px solid #bbb;border-radius:5px">	
				<div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-5 row-cols-xl-5" style="margin-top:10px">					
					<div class="col" style="margin-top:5px">
						<small class="text-success">&nbsp;<i class="fa fa-car"></i> Municipality
							<select class="form-control" name="mcode" required onchange="jump('?&barangays=<?php echo $_GET['barangays'] ?? ''; ?>&municipality='+this.value)">
								<option value="" selected>Municipality</option>
								<?php
									$barangay     = $_GET["barangays"]   ?? "";
									$municipality = $_GET["municipality"] ?? "";

									if ($barangay === "" || $barangay === "Barangays") {
										$stmt = $link->prepare("SELECT DISTINCT mcode FROM barangays ORDER BY mcode");
									} else {
										$stmt = $link->prepare("SELECT DISTINCT mcode FROM barangays WHERE barangay = ? ORDER BY mcode");
										$stmt->bind_param("s", $barangay);
									}

									$stmt->execute();
									$result = $stmt->get_result();

									while ($rs2 = $result->fetch_assoc()) {
										if (!empty($rs2["mcode"])) {
											$mcode    = htmlspecialchars($rs2["mcode"]);
											$selected = ($municipality === $mcode) ? "selected" : "";
											echo "<option value=\"$mcode\" $selected>$mcode</option>";
										}
									}

									$stmt->close();
								?>
							</select>
						</small>
					</div>
					<div class="col" style="margin-top:5px">
						<small class="text-success">&nbsp;<i class="fa fa-motorcycle"></i> Barangay
							<select class="form-control" name="barangay" required >
								<option value="" selected="1">Barangays</option>
								<?php
									$municipality    = $_GET["municipality"] ?? "";
									$selectedBarangay = $_GET["barangays"]   ?? "";

									if ($municipality === "" || $municipality === "Municipality") {
										$stmt = $link->prepare("SELECT DISTINCT barangay FROM barangays ORDER BY barangay");
									} else {
										$stmt = $link->prepare("SELECT DISTINCT barangay FROM barangays WHERE mcode = ? ORDER BY barangay");
										$stmt->bind_param("s", $municipality);
									}

									$stmt->execute();
									$result = $stmt->get_result();

									while ($row = $result->fetch_assoc()) {
										if (!empty($row["barangay"])) {
											$barangay = htmlspecialchars($row["barangay"]);
											$selected = ($selectedBarangay === $barangay) ? "selected" : "";
											echo "<option value=\"$barangay\" $selected>$barangay</option>";
										}
									}

									$stmt->close();
								?>
							</select>
						</small>
					</div>
					<div class="col" style="margin-top:5px">
						<small class="text-success">&nbsp;<i class="fa fa-wrench"></i> Installation Type
							<select class="form-control" name="inst_type" required>
								<option value="">Installation Type</option>
								<option value="New">New</option>
								<option value="Repair">Repair</option>
								<option value="Upgrade">Upgrade</option>
							</select>
						</small>
					</div>			  
					<div class="col" style="margin-top:5px">
						<small class="text-success">&nbsp;<i class="fa fa-users"></i> Installer Team
							<select class="form-control" name="installer" required>
								<option value="">Installer Team</option>
								<?php
									$ex2=$link->query("select leader from installer where tid=tid order by leader")or die(mysqli_error($link));										
									while($rs=mysqli_fetch_array($ex2)){
									$leader=$rs[0];
									$leader=ucwords(strtolower($leader));
										echo"<option value='$leader'>$leader</option>";
									}
								?>
							</select>
						</small>
					</div>	
					<div class="col" style="margin-top:5px">
						<small class="text-success">&nbsp;<i class="fas fa-laptop"></i> Station IP Address
							<input class="form-control" name="ip_address" placeholder="IP Address">
						</small>
					</div>	
					<div class="col" style="margin-top:5px">
						<small class="text-success">&nbsp;<i class="fa fa-home"></i> Place of Installation
							<select class="form-control" name="place" required>
								<option value="">Installation Place</option>
								<?php
									$ex2=$link->query("select * from placement where pid=pid order by pname")or die(mysqli_error($link));										
									while($rs=mysqli_fetch_array($ex2)){
										$place=$rs["pcode"];
										$pname=$rs["pname"];
										echo"<option value='$place'>$pname</option>";
									}
								?>
							</select>
						</small>
					</div>
					<div class="col" style="margin-top:5px">
						<small class="text-success">&nbsp;<i class="fa fa-calendar"></i> Installation Date
							<input class="form-control" name="inst_date" placeholder="Installation Date" onfocus="(this.type='date')" required>
						</small>
					</div>
					<div class="col" style="margin-top:5px">
						<small class="text-success"><i class="fa fa-user"></i> Contact Person
							<input class="form-control" type="text" name="cont_person" placeholder="Contact Person" required>
						</small>
					</div>
					<div class="col" style="margin-top:5px">
						<small class="text-success">&nbsp;<i class="fa fa-phone"></i> Contact Number
							<input class="form-control" type="text" name="cont_number" placeholder="Contact Number" required>
						</small>
					</div>
					<div class="col" style="margin-top:5px">
						<small class="text-success">&nbsp;<i class="fa fa-tower-cell"></i> Link to MBS/Relay Station
							<select class="form-control" name="link_ap_bst" required>
								<option value="">Select MBS/Relay Station</option><?php echo base_station($pdo);?>
							</select>
						</small>
					</div>
					<div class="col" style="margin-top:5px;display:none">
						<small class="text-success">&nbsp;<i class="fa fa-comments"></i> Remarks
							<input class="form-control" type="text" name="remarks" placeholder="Remarks">
						</small>
					</div>
				</div>	
			</div>
			<div class="container">	
				<div class="row" style="overflow-x:auto;margin-top:10px;background:#eee;border:1px solid #bbb;border-radius:5px">
					<table class="table table-responsive" id="myRollout">
						<thead style="background:#bbb">
							<tr>
								<th class="text-success"></th>
								<th class="text-success">Name</th>
								<th class="text-success">Code</th>
								<th class="text-success">Category</th>
								<th class="text-success">Stock</th>
								<th class="text-success">Qty</th>
								<th class="text-success">Unit</th>
								<th class="text-success">SN/MAC</th>
								<th class="text-success">
									<button type="button" name="addRollout" class="btn btn-success btn-sm btn_addRollout" required>
										<span><i class="fa fa-plus"></i></span>
									</button>
								</th>
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
					<div class="box-footer text-center" style="margin-bottom:15px">	
						<input style="margin-right:20px;width:100px" type="submit" onclick="jump('addRollout.php')" value="Reset" class="btn btn-sm btn-primary">
						<a href="javascript:history.back()" style="width:100px" class="btn btn-sm btn-danger">Cancel</a>
						<input style="margin-left:20px;width:100px" type="submit" name="rollSave" value="Submit" class="btn btn-sm btn-success">
					</div>
				</div>
			</div>	
		</div>
    </section>
</main>

</form>

<style>
	.addtr{
		background:#eee;
		color: #535353;
	}
	.addtr:hover{
		background:#fff79d; 
	}
	.addtd{
		padding:4px 4px 4px 10px;
		color:#23234;
		border:1px solid #bbb;
		border-radius:4px;
	}
	input[readonly]{
	background-color:#eee;
	}	
</style>

<script>
    $(document).ready(function(){
		$(document).on('click','.btn_addRollout', function(){
			var html='';
			html+='<tr class="addtr">';
			html+='<td><input  class="addtd devicename" type="hidden" name="devicename[]" readonly></td>';
			html+='<td><select class="addtd deviceid" required name="deviceid[]"><option value="">Select Item</option><?php echo fill_device($pdo);?></select></td>';
			html+='<td><input  class="addtd devicecode" type="text" name="devicecode[]" readonly></td>';
			html+='<td><input  class="addtd devicecategory" type="text" name="devicecategory[]" readonly></td>';
			html+='<td><input  class="addtd devicestock" type="text" name="devicestock[]" size="5" readonly></td>';
			html+='<td><input  class="addtd deviceqty" type="number" name="quantity[]" size="5" min="1" max="50" required></td>';
			html+='<td><input  class="addtd deviceunit" type="text" name="deviceunit[]" size="5" readonly></td>';
			html+='<td><input  class="addtd serial_mac" type="text" name="serial_mac[]" required></td>';
			html+='<td><button class="btn btn-danger btn-sm btn-remove" type="button" name="remove"><i class="fa fa-remove"></i></button></td>'

        $('#myRollout').append(html);

			$('.deviceid').on('change', function(e){
				var deviceid = this.value;
				var tr=$(this).parent().parent();
				$.ajax({
					url:"getdevice.php",
					method:"get",
					data:{id:deviceid},
					success:function(data){
						tr.find(".devicecode").val(data["device_code"]);
						tr.find(".devicecategory").val(data["device_category"]);
						tr.find(".devicename").val(data["device_name"]);
						tr.find(".devicestock").val(data["device_stock"]);
						tr.find(".deviceqty").val(0);
						tr.find(".deviceunit").val(data["device_unit"]);
						tr.find(".serial_mac").val();
						// calculate(0,0);
					}	
				})
			})
		})

		$(document).on('click','.btn-remove', function(){
			$(this).closest('tr').remove();
			calculate(0,0);
		})

		$("#myRollout").delegate(".deviceqty","keyup change", function(){
		var quantity = $(this);
		var tr=$(this).parent().parent();
			if((quantity.val()-0)>(tr.find(".devicestock").val()-0)){
				swal("Warning","Not Enough Stock","warning");
				quantity.val(1);
			}
		})
	});
</script>

<?php require("footer.php"); ?>