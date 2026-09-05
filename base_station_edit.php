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
				
	$base="";
	if($_GET["base_stations"]!="")
		$base=" and bst_id='".$_GET["base_stations"]."' ";
												
	$ex = $link->query("select * from base_stations where bst_id=bst_id $base order by bst_id limit $from,$to ");

?>

<section id="contact" class="contact">
	<div class="container">
		<div class="text-center" style="margin-top:-50px">
			<img style="height:100px" src="assets/img/logo_2.png"/>
		</div>
			
		<?php
					
			while($rs=mysqli_fetch_array($ex)){	

			echo"
			<center style='margin-top:10px'>
				<h5>".$rs["station_name"]."</h5>
			</center>
			
				<form action='base_station_edit_proc.php' method='POST' enctype='multipart/form-data'>
					<div class='mt-5 mt-lg-0' style='width:300px'>	
						<input type='hidden' name='bst_id' value='".$rs[0]."'>		
						<div class='row'>				
							<div style='padding-top:10px' class='form-group mt-3 mt-md-0'>Coordinates	
								<input type='text' class='form-control' name='coordinates' value='".$rs["coordinates"]."' placeholder='Coordinates' >							
							</div>						
						</div>
						<div class='row'>				
							<div style='padding-top:10px' class='form-group mt-3 mt-md-0'>Tower Height	
								<input type='text' class='form-control' name='tower_height' value='".$rs["tower_height"]."' placeholder='Tower Height' >							
							</div>						
						</div>
						<div class='row'>				
							<div style='padding-top:10px' class='form-group mt-3 mt-md-0'>Elevation	
								<input type='text' class='form-control' name='elevation' value='".$rs["elevation"]."' placeholder='Elevation' >							
							</div>						
						</div>	
						<div class='row'>				
							<div style='padding-top:10px' class='form-group mt-3 mt-md-0'>IP Address
								<input type='text' class='form-control' name='ip_address' value='".$rs["ip_address"]."' placeholder='IP Address' >							
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
				</form>";
			}			
		?>					
	</div>
</section>