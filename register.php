<?php 
	require("connect.php");
	require("header.php");

	if(isset($_POST["bSave"])){
		
	$insert = $link->query("INSERT INTO users VALUES (0,
		'".$_POST["fname"]."',
		'".$_POST["mname"]."',
		'".$_POST["lname"]."',
		'".$_POST["email"]."',
		'".$_POST["phone"]."',
		'".$_POST["username"]."',
		'".$_POST["password"]."',
		'".$_POST["account"]."', 0, 0)");	

		if(($insert)==TRUE){
			header("location:register_ok.php");
		}else{
			header("location:header_error3.php");
		}	
	}
?>

<body>

<?php require("menunav.php");?>

<script>setActive("register");</script>

<main id="main"><br><br><br><br>

<section id="contact" class="contact">
   <div class="container" style="border:1px solid #bbb;width:400px;align:center;padding:20px;border-radius:5px;background:#eee">
	<div style="text-align:center;margin-top:-10px"><br><img src="assets/img/logo_2.png?<?php date("h:i:s")?>" height="150"></div>
	<div class="section-title" style="margin:10px">
		<h3 class="text-primary">Register User Account</h3><br>
	</div>
	     <div class="row" style="margin-top:-70px">
			<div class="mt-5 mt-lg-0" style="padding:15px;align:center">
				<form action="" method="post" enctype="multipart/form-data">
					<div class="row">
						<div class="col-md-6 form-group mt-3 mt-md-0">
							<input style="margin:5px 0 5px 0" class="form-control" type="text" name="fname" placeholder="First Name" required >
							<input style="margin:5px 0 5px 0" class="form-control" type="hidden" name="mname" placeholder="Middle Name" >
						</div>
						<div class="col-md-6 form-group mt-3 mt-md-0">
							<input style="margin:5px 0 5px 0" class="form-control" type="text" name="lname" placeholder="Family Name" required >
						</div>	
					</div>	
					<div class="row">
						<div class="col-md-6 form-group mt-3 mt-md-0">
							<input style="margin:5px 0 5px 0" class="form-control" type="email" name="email" placeholder="Email Address" required >
						</div>
						<div class="col-md-6 form-group mt-3 mt-md-0">
							<input style="margin:5px 0 5px 0" class="form-control" type="text" name="phone" placeholder="Phone Number" required >
						</div>
					</div>
					<div class="row"> 
						<div class="col-md-6 form-group mt-3 mt-md-0">
							<input style="margin:5px 0 5px 0" class="form-control" type="text" name="username" placeholder="Username"  required >				
						</div>
						<div class="col-md-6 form-group mt-3 mt-md-0">
							<input style="margin:5px 0 5px 0" class="form-control" type="password" name="password" placeholder="Password"  required >
						</div>
					</div>
					<div class="row" style="display:none"> 
						<div class="col-md-6 form-group mt-3 mt-md-0">
							<select style="margin:5px 0 5px 0" class="form-control" name="account" >
								<option value="">Account Type</option>
								<option value="Users">Users</option>
								<option value="Admin">Admin</option>
								<option value="Leader">Leader</option>
							</select>
						</div>
					</div>	
					<div class="row text-center">
						<div class="col-md-6 form-group mt-3 mt-md-0">
							<input style="margin:5px 0 5px 0" class="form-control btn btn-primary" type="submit" name="bSave" value="Submit" >
						</div>
						<div class="col-md-6 form-group mt-3 mt-md-0">
							<input style="margin:5px 0 5px 0" class="form-control btn btn-danger" onclick="jump('index.php')" value="Cancel" >
						</div>
					</div>	
				</form>
			</div>
		</div>
	</div>
</section><!-- End Contact Section -->

</main><!-- End #main -->

<?php require("footer.php");?>

</body>

</html>