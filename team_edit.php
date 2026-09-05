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
				
	$org="";
	if($_GET["ciso_team"]!="")
		$org=" and cid='".$_GET["ciso_team"]."' ";
												
	$ex = $link->query("select * from ciso_team where cid=cid $org order by cid limit $from,$to ");

	while($rs = mysqli_fetch_array($ex)){	

	$ex = $link->query("select * from ciso_team where ciso_team.cid='$rs[0]' and ciso_team.cid=ciso_team.cid ");
	$ii=1;
?>

<section id="contact" class="contact">
	<div class="container">
		<div class="text-center" style="margin-top:-40px;border-radius:5px;font-size:20px;font-weight:bold">
			<?php
				echo"<img style='height:200px;border-radius:50%;border:5px solid #bbb' ";
				if(file_exists("assets/img/team/$rs[0].jpg")){			
					echo" src='assets/img/team/$rs[0].jpg? ".date("h:i:s")." ' />";
				}else{
					echo" src='assets/img/user.png' style='opacity:.5' />";
				}
			?>				
		</div>
	
			<?php
					
				while($rs = mysqli_fetch_array($ex)){	
				
				echo"	
				<form action='team_edit_proc.php' method='POST' enctype='multipart/form-data'>
					<div class='mt-5 mt-lg-0' style='width:380px;text-align:center'>			
						<div class='row'>				
							<div style='padding-top:10px' class='form-group mt-3 mt-md-0'>
								<input type='hidden' class='form-control' name='cid' value='$rs[0]' />
								<input type='text' class='form-control' name='fullname' value='".$rs["fullname"]."' placeholder='Full Name' required >
							</div>
							<div style='padding-top:10px' class='form-group mt-3 mt-md-0'>
								<input type='text' class='form-control' name='position' value='".$rs["position"]."' placeholder='Position' required >
							</div>
							<div style='padding-top:10px' class='form-group mt-3 mt-md-0'>
								<input type='text' class='form-control' name='description' value='".$rs["description"]."' placeholder='Job Description' required >
							</div>
							<div style='padding-top:10px' class='form-group mt-3 mt-md-0'>
								<input type='text' class='form-control' name='socialmedia' value='".$rs["socialmedia"]."' placeholder='Facebook Account Link' required >
							</div>
							<div style='padding-top:10px' class='form-group mt-3 mt-md-0'>
								<input type='text' class='form-control' name='phonenumber' value='".$rs["phonenumber"]."' placeholder='Cell Phone Number' required >
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