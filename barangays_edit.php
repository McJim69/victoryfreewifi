<?php 
	require("connect.php"); 

    function fill_baseAP($pdo){
		$output= '';
		$select = $pdo->prepare("SELECT * FROM base_stations order by station_name");
		$select->execute();
		$result = $select->fetchAll();
		foreach($result as $row){
			$output.='<option value="'.$row['station_name'].'">'.$row["station_name"].'</option>';
		}	return $output;
	}

	$rec=1;

	$p=$_GET['page'];
		if($p>1){
			$to=$rec;
			$from=($p*$rec)-$rec;
			$i=(($p-1)*$rec)+1;
		}else{
			$to=$rec;
			$from=0;
			$i=1;
			$p=1;
		}			
				
	$bar="";
	if($_GET["barangays"]!="")
		$bar=" and bid='".$_GET["barangays"]."' ";
												
	$ex = $link->query("select * from barangays where bid=bid $bar order by bid limit $from,$to ");

	while($rs=mysqli_fetch_array($ex)){	

	$ex = $link->query("select * from barangays b where b.bid='$rs[0]' and b.bid=b.bid ");
	$ii=1;
?>

<section id="contact" class="contact">
	<div class="container">
		<div class="text-center" style="margin-top:-50px">
			<img style='height:100px' src='assets/img/logo_2.png'/>
		</div>
			
			<?php
					
				while($rs=mysqli_fetch_array($ex)){	

				$ex3=$link->query("select * from municipality m where m.mcode='".$rs["mcode"]."'");
				$rs3=mysqli_fetch_array($ex3);		
				$ctm=$rs3["mname"];

				echo"
				<center style='margin-top:10px'>
					<h5>".$rs["barangay"]."<br><x style='text-transform:uppercase'>$ctm</x></h5>
				</center>
				
				<form action='barangays_edit_proc.php' method='POST' enctype='multipart/form-data'>
					<div class='mt-5 mt-lg-0' style='width:300px'>			
						<div class='row' style='margin-top:-10px'>				
							<div style='padding-top:10px' class='form-group mt-3 mt-md-0'>Base Station Link 
								<input type='hidden' class='form-control' name='bid' value='$rs[0]' />
								<select class='form-control' name='ap_link_bst' required >";
									if($rs["ap_link_bst"]!==""){ 
										$apl=$rs["ap_link_bst"];
									}else{
										$apl="Select Base Station";
									}
									echo"
									<option value='$apl'>$apl</option>
									".fill_baseAP($pdo)."
								</select>
							</div>
							<div style='padding-top:10px' class='form-group mt-3 mt-md-0'>Base Station Coordinates	
								<input type='text' class='form-control' name='bst_coordinates' value='".$rs["bst_coordinates"]."' placeholder='Base Station Coordinates' required >							
							</div>						
						</div>
						<div class='row' style='margin-bottom:-40px'>
							<div class='form-group mt-3'>
								<div style='text-align:center'>
									<button type='SUBMIT' class='form-control btn btn-primary' name='upDate'>Update</button>
								</div>
							</div>
						</div>
					</div> 
				</form>";
			  }		
		   }			
		?>					
	</div>
</section>