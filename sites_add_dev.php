<?php
	error_reporting(0);
	require("connect.php");
	require("header_all.php");

	if(!isset($_SESSION['user'])){
		header("location:index.php");
		exit();
	}
	
    function fill_device($pdo){
		$output= '';
		$select = $pdo->prepare("SELECT * FROM device order by device_id");
		$select->execute();
		$result = $select->fetchAll();

		foreach($result as $row){
			$output.='<option value="'.$row['device_id'].'">'.$row["device_name"].'</option>';
		}	return $output;
	}
	
	$site="";
	if($_GET["sites"]!="")
	$site=" sid='".$_GET["sites"]."' ";

	$qry = $pdo->prepare("SELECT * FROM sites WHERE $site");
	$qry->execute();
	$res = $qry->fetchAll();

	foreach($res as $rows){
		$sitID = $rows['sid'];
		$insta = $rows['inst_date'];
		$munic = $rows['mcode'];
		$sname = $rows['barangay'];
		$place = $rows['place'];
	}
    
    if(isset($_POST['rollSave'])){
		$arr_devid = $_POST['deviceid'];
		$arr_dcode = $_POST['devicecode'];
		$arr_dcate = $_POST['devicecategory'];
		$arr_dname = $_POST['devicename'];
		$arr_stock = $_POST['devicestock'];
		$arr_dvqty = $_POST['quantity'];
		$arr_dunit = $_POST['deviceunit'];
		$arr_snmac = $_POST['serial_mac'];

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
			
		if($sitID!=null){
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
						:idate)");

					$insert->bindParam(':sitid', $sitID);
					$insert->bindParam(':devid', $arr_devid[$i]);
					$insert->bindParam(':dcode', $arr_dcode[$i]);
					$insert->bindParam(':dcate', $arr_dcate[$i]);
					$insert->bindParam(':dname', $arr_dname[$i]);
					$insert->bindParam(':dvqty', $arr_dvqty[$i]);
					$insert->bindParam(':dunit', $arr_dunit[$i]);
					$insert->bindParam(':snmac', $arr_snmac[$i]);
					$insert->bindParam(':idate', $insta);

					$insert->execute();
				}
				echo"<script>location.href='site_details.php?sites=$sitID';</script>";
			}
		}				
	}
?>

<body>

<?php require("menunav.php");?>

<script>setActive("sites");</script>

<form action="#" method="POST">

<main class="main">
    <section style="margin-top:100px;min-height:610px;">
		<div class="container">
			<h2 class="text-success">
				ADD Device :
				<?php echo $munic;?>
				<?php echo $sname;?>
				<?php echo $place;?>
			</h2>
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