<?php 
	require("connect.php"); 

    function fill_category($pdo){
		$output= '';
		$select = $pdo->prepare("SELECT * FROM category order by cat_name");
		$select->execute();
		$result = $select->fetchAll();

		foreach($result as $row){
			$output.='<option value="'.$row['cat_name'].'">'.$row["cat_name"].'</option>';
		}	return $output;
	}

    function fill_unit($pdo){
		$output= '';
		$select = $pdo->prepare("SELECT * FROM unit order by nm_unit");
		$select->execute();
		$result = $select->fetchAll();

		foreach($result as $row){
			$output.='<option value="'.$row['nm_unit'].'">'.$row["nm_unit"].'</option>';
		}	return $output;
	}
	
	$rec=1;

	$p = isset($_GET['page']) ? $_GET['page'] : 1;

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

	$dev="";
	if($_GET["device"]!="")
		$dev=" and device_id='".$_GET["device"]."' ";
												
	$ex = $link->query("select * from device where device_id=device_id $dev order by device_category limit $from,$to ");

	while($rs = mysqli_fetch_array($ex)){	

	$ex = $link->query("select * from device d where d.device_id='$rs[0]' and d.device_id=d.device_id ");
	$ii=1;

	while($rs = mysqli_fetch_array($ex)){	

	echo"
	<section id='contact' class='contact'>
		<div class='container'>
			<div class='text-center' style='margin-top:-40px;border-radius:5px;font-size:20px;font-weight:bold'>
				<img style='height:150px;border-radius:50%;border:5px solid #bbb' ";
				if(file_exists("assets/img/items/".$rs["device_code"].".jpg")){			
					echo" src='assets/img/items/".$rs["device_code"].".jpg? ".date("h:i:s")." ' />";
				}else{
					echo" src='assets/img/device.png' style='opacity:.5' />";
				}
	
			echo"	
			</div>
			<form action='device_edit_proc.php' method='POST' enctype='multipart/form-data'>
				<div class='mt-5 mt-lg-0' style='width:380px;text-align:left'>			
					<div class='row'>				
						<div style='padding-top:5px' class='form-group col'> <small> Device Category</small>
							<input type='hidden' class='form-control' name='device_id' value='$rs[0]' />
							<select class='form-control' name='device_category' required >
								<option value='".$rs["device_category"]."'>".$rs["device_category"]."</option>
								".fill_category($pdo)."
							</select>
						</div>
						<div style='padding-top:5px' class='form-group col'> <small> Device Code</small>
							<input type='text' class='form-control' name='device_code' value='".$rs["device_code"]."' placeholder='Device Code' required >
						</div>
					</div>
					<div class='row'>
						<div style='padding-top:5px' class='form-group col'> <small> Device Name</small>
							<input type='text' class='form-control' name='device_name' value='".$rs["device_name"]."' placeholder='Device Name' required >
						</div>
						<div style='padding-top:5px' class='form-group col'> <small> Device Stock</small>
							<input type='number' class='form-control' name='device_stock' value='".$rs["device_stock"]."' placeholder='Stock' required >
						</div>
					</div>
					<div class='row'>
						<div style='padding-top:5px' class='form-group col'> <small> Minimum Stock</small>
							<input type='number' class='form-control' name='min_stock' value='".$rs["min_stock"]."' placeholder='Minimun Stock' required >
						</div>
						<div style='padding-top:5px' class='form-group col'> <small> Device Unit</small>
							<select class='form-control' name='device_unit' required >
								<option value='".$rs["device_unit"]."'>".$rs["device_unit"]."</option>
								".fill_unit($pdo)."
							</select>
						</div>
					</div>	
					<div class='row'>
						<div style='padding-top:5px' class='form-group col'> <small> Description</small>
							<textarea class='form-control' name='description' required >".$rs["description"]."</textarea>
						</div>
					</div>
					<div class='row' style='margin-bottom:-40px'>
						<div class='form-group mt-3'>
							<div style='text-align:center'>
								<button type='SUBMIT' class='form-control btn btn-danger' name='upDate'>Update</button>
							</div>
						</div>
					</div>
				</div> 
			</form>
		</div>
	</section>";
	  }		
   }			
?>					
	