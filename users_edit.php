<?php 
	require("connect.php"); 
	
	$rec=1;

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
				
	$usr="";
	if($_GET["users"]!="")
		$usr=" and usrid='".$_GET["users"]."' ";
												
	$ex = $link->query("select * from users where usrid=usrid $usr order by usrid limit $from,$to ");

	while($rs = mysqli_fetch_array($ex)){	

	$ex = $link->query("select * from users u where u.usrid='$rs[0]' and u.usrid=u.usrid ");
	$ii=1;
?>

<section id="contact" class="contact">
	<div class="container">
		<div class="text-center" style="margin-top:-50px">
			<?php
				echo"<img style='height:180px;width:180px;border-radius:100%;border:5px solid #bbb' ";
				if(file_exists("assets/img/users/$rs[0].jpg")){			
					echo" src='assets/img/users/$rs[0].jpg? ".date("h:i:s")." ' />";
				}else{
					echo" src='assets/img/user.png' style='opacity:.5' />";
				}
			?>				
		</div>
	
			<?php
					
				while($rs = mysqli_fetch_array($ex)){	

				if($rs["status"]==1){
					$stats="Active";
				}else{
					$stats="Inactive";
				}
				echo"	
				<form action='users_edit_proc.php' method='POST' enctype='multipart/form-data'>
					<div class='mt-5 mt-lg-0' style='width:350px;text-align:center'>			
						<div class='row'>				
							<div style='padding-top:5px' class='col-md-6 form-group mt-3 mt-md-0'>
								<input type='hidden' class='form-control' name='usrid' value='$rs[0]' />
								<input type='text' class='form-control' name='fname' value='".$rs["fname"]."' placeholder='First Name' required >
							</div>
							<div style='padding-top:5px' class='col-md-6 form-group mt-3 mt-md-0'>
								<input type='text' class='form-control' name='lname' value='".$rs["lname"]."' placeholder='Last Name' required >
							</div>
							<div style='padding-top:5px' class='col-md-6 form-group mt-3 mt-md-0'>
								<input type='text' class='form-control' name='email' value='".$rs["email"]."' placeholder='Email Address' required >
							</div>
							<div style='padding-top:5px' class='col-md-6 form-group mt-3 mt-md-0'>
								<input type='text' class='form-control' name='phone' value='".$rs["phone"]."' placeholder='Phone Number' required >
							</div>
							<div style='padding-top:5px' class='col-md-6 form-group mt-3 mt-md-0'>
								<input type='text' class='form-control' name='username' value='".$rs["username"]."' placeholder='User Name' required >
							</div>
							<div style='padding-top:5px' class='col-md-6 form-group mt-3 mt-md-0'>
								<input type='text' class='form-control' name='password' value='".$rs["password"]."' placeholder='Password' required >
							</div>
							<div style='padding-top:5px' class='col-md-6 form-group mt-3 mt-md-0'>
								<select class='form-control' name='account' required >
									<option value='".$rs["account"]."'>".$rs["account"]."</option>
									<option value='Users'>Users</option>
									<option value='Admin'>Admin</option>
									<option value='Leader'>Leader</option>
								</select>
							</div>
							<div style='padding-top:5px' class='col-md-6 form-group mt-3 mt-md-0'>
								<select class='form-control' name='status' required >
									<option value='".$rs["status"]."'>$stats</option>
									<option value='1'>Active</option>
									<option value='0'>Disable</option>
								</select>
							</div>
						</div>
						<div class='row' style='margin-bottom:-45px'>
							<div class='form-group mt-3'>
								<div style='text-align:center'>
									<button type='SUBMIT' class='form-control btn btn-danger' name='upDate'>Update</button>
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