<?php 
	require("connect.php"); 

    function fill_team($pdo){
	$output= '';
	$select = $pdo->prepare("SELECT * FROM inst_team order by tid");
	$select->execute();
	$result = $select->fetchAll();
	foreach($result as $row){
		$output.='<option value="'.$row['tcode'].'">'.$row["tcode"].'</option>';
	}
		return $output;
	}

    function fill_field($pdo){
	$output= '';
	$select = $pdo->prepare("SELECT * FROM inst_code order by fid");
	$select->execute();
	$result = $select->fetchAll();
	foreach($result as $row){
		$output.='<option value="'.$row['fieldcode'].'">'.$row["fieldname"].'</option>';
	}
		return $output;
	}
		
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
					
	$inst="";
	if($_GET["installer"]!="")
		$inst=" and tid='".$_GET["installer"]."' ";
												
	$ex = $link->query("select * from installer where tid=tid $inst order by tid limit $from,$to ");

	while($rs = mysqli_fetch_array($ex)){	

	$ex = $link->query("select * from installer where installer.tid='$rs[0]' and installer.tid=installer.tid ");
	$ii=1;
?>

<section id="contact" class="contact">
	<div class="container">
		<div class="text-center" style="margin-top:-50px">
			<?php
				echo"<img style='height:180px;width:180px;border-radius:100%;border:5px solid #bbb' ";
				if(file_exists("assets/img/installers/$rs[0].jpg")){			
					echo" src='assets/img/installers/$rs[0].jpg? ".date("h:i:s")." ' />";
				}else{
					echo" src='assets/img/user.png' style='opacity:.5' />";
				}
			?>				
		</div>
	
			<?php
					
				while($rs = mysqli_fetch_array($ex)){	
				
				echo"	
				<form action='installers_edit_proc.php' method='POST' enctype='multipart/form-data'>
					<div class='mt-5 mt-lg-0' style='width:380px;text-align:center'>			
						<div class='row'>				
							<div style='padding-top:10px' class='form-group mt-3 mt-md-0'>
								<input type='hidden' class='form-control' name='tid' value='$rs[0]' />
								<input type='text' class='form-control' name='leader' value='".$rs["leader"]."' placeholder='Team Leader's Name' required >
							</div>
							<div style='padding-top:10px' class='form-group mt-3 mt-md-0'>
								<select class='form-control' name='tcode' required >
									<option value='".$rs["tcode"]."'>".$rs["tcode"]."</option>
									".fill_team($pdo)."
								</select>
							</div>
							<div style='padding-top:10px' class='form-group mt-3 mt-md-0'>
								<select class='form-control' name='fieldcode' required >
									<option value='".$rs["fieldcode"]."'>".$rs["fieldname"]."</option>
									".fill_field($pdo)."
								</select>
							</div>
							<div style='padding-top:10px' class='form-group mt-3 mt-md-0'>
								<input type='text' class='form-control' name='facebook' value='".$rs["facebook"]."' placeholder='Facebook Account Link' required >
							</div>
							<div style='padding-top:10px' class='form-group mt-3 mt-md-0'>
								<input type='text' class='form-control' name='phone' value='".$rs["phone"]."' placeholder='Cell Phone Number' required >
							</div>
						</div>
						<div class='row' style='margin-bottom:-45px'>
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