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
?>

<section id="contact" class="contact">
	<div class="container" data-aos="fade-up">
	<div style="text-align:center;margin-top:-50px;"><img src="assets/img/logo_2.png" height="120"></div>
	<div class="section-title">
	<h4>Add Installer</h4>
		<form action='installers_add_proc.php' method='POST' enctype='multipart/form-data'>
			<div class='mt-5 mt-lg-0' data-aos='fade-right' data-aos-delay='100' style='width:333px;text-align:center'>			
				<div class='row' style="margin-bottom:-75px"> 
					<div class='form-group mt-3'>
						<select class="form-control" name="tcode" required >
							<option value="">Select Team</option><?php echo fill_team($pdo);?>
						</select>
					</div>
					<div class='form-group mt-3'>
						<select class="form-control" name="fieldcode" required >
							<option value="">Select Field</option><?php echo fill_field($pdo);?>
						</select>
					</div>
					<div class='form-group mt-3'>
						<input class="form-control" type="text" name="leader" placeholder="Leader Name" required >
					</div>
					<div class='form-group mt-3'>
						<input class="form-control" type="text" name="phone" placeholder="Phone Number" required >
					</div>
					<div class='form-group mt-3'>
						<input class="form-control" type="text" name="facebook" placeholder="Facebook Link" required >
					</div>
					<div class='form-group mt-3'>
						<input type='submit' class='btn btn-primary form-control' name='bSave' value='Submit' >
					</div>		
				</div>				
			</div>
		</form>
	</div>
</section>