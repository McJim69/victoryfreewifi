<?php
	require("connect.php");
	
	$querys = $link->query("SELECT MAX(device_id) FROM device");
	$result = $querys->fetch_array();
	$device = $result[0]+1;	
?>

<form action="device_add_proc.php" method="POST">
	<div class="form-group" style="width:380px;text-align:center;padding:10px">
		<div>
			<h3 class="text-success">ADD DEVICE = ID <?php echo $device;?></h3>
			<input type="hidden" name="device_id" value="<?php echo $device;?>">
		</div>
		<div style="margin-top:10px">
			<div style="text-align:left">&nbsp;Device Code</div>
			<input type="text" class="form-control" name="device_code" placeholder="Device Code" required >
		</div>
		<div style="margin-top:10px">
			<div style="text-align:left">&nbsp;Device Name</div>
			<input type="text" class="form-control" name="device_name" placeholder="Device Name" required >
		</div>	
		<div style="margin-top:10px">
			<div style="text-align:left">&nbsp;Device Category</div>
			<select class="form-control" name="device_category" required >
				<option value="" selected="1">Categories</option>
				<?php										
					$ex=$link->query("select cat_name from category group by cat_name order by cat_name")or die(mysqli_error($link));										
					while($rs=mysqli_fetch_array($ex)){
						echo "<option ";
					if($_GET["barangays"]===$rs[0])
						echo "selected";
						echo">$rs[0]</option>";
					}
				?>
			</select>
		</div>		
		<div style="margin-top:10px">
			<div style="text-align:left">&nbsp;Device Stock</div>
			<input type="number" class="form-control" name="device_stock" placeholder="Device Stock" required >
		</div>
		<div style="margin-top:10px">
			<div style="text-align:left">&nbsp;Minimum Stock</div>
			<input type="number" class="form-control" name="min_stock" placeholder="Minimum Stock" required >
		</div>	
		<div style="margin-top:10px">
			<div style="text-align:left">&nbsp;Units</div>
			<select class="form-control" name="device_unit" required >
				<option value="" selected="1">Units</option>
				<?php										
					$ex=$link->query("select nm_unit from unit group by nm_unit order by nm_unit")or die(mysqli_error($link));										
					while($rs=mysqli_fetch_array($ex)){
						echo "<option ";
					if($_GET["barangays"]===$rs[0])
						echo "selected";
						echo">$rs[0]</option>";
					}
				?>
			</select>
			<input type="hidden" name="img" value="0">
		</div>	
		<div style="margin-top:10px">
			<div style="text-align:left">&nbsp;Description</div>
			<textarea class="form-control" name="description" placeholder="Type here the device description" required ></textarea>
		</div>			
		<div style="margin-top:20px;margin-bottom:5px">
			<input type="submit" onclick="jump('devices.php')" style="width:70px" value="Reset" class="btn btn-sm btn-primary"> &nbsp; &nbsp;
			<a href="javascript:history.back()" style="width:70px" class="btn btn-sm btn-danger">Cancel</a> &nbsp; &nbsp;
			<input type="submit" name="addDevice" style="width:70px" value="Submit" class="btn btn-sm btn-success">
		</div>
	</div>	
</form>