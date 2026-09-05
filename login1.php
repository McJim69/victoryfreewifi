<?php 
	require("connect.php"); 
	require("header.php"); 
	
	if(isset($_POST["login"])){
		$ex=$link->query("SELECT * FROM users WHERE 
			(username='".$_POST["user"]."' OR email='".$_POST["mail"]."') 
			AND password='".$_POST["pass"]."' ");
		
		if($rs=mysqli_fetch_array($ex)){
			
			$exx=$link->query("SELECT * from validity WHERE validity>'".date("Y-m-d")."'");
		
			$exx=$link->query("SELECT * from users WHERE status=1 AND usrid='$rs[0]'");

			if($rs1=mysqli_fetch_array($exx)){
				$_SESSION["usid"]=$rs[0];
				$_SESSION["mail"]=$rs["email"];
				$_SESSION["phon"]=$rs["phone"];
				$_SESSION["user"]=$rs["username"];
				$_SESSION["pass"]=$rs["password"];	
				$_SESSION["fnam"]=$rs["fname"];	
				$_SESSION["mnam"]=$rs["mname"];	
				$_SESSION["lnam"]=$rs["lname"];	
				$_SESSION["type"]=$rs["account"];

			echo"<script>window.location = 'index.php';</script>";
			
			}else
				$m="<div style='margin-top:-40px;color:#FFF;background:#ff0000;padding:10px;border-radius:5px'>
				<b>ACCESS DENIED</b> ! Either Validity or Your Account is Inactive.
				</div><br><br>";
	
			}else
				$m="<div style='margin-top:-40px;color:#FFF;background:#ff0000;padding:10px;border-radius:5px'>
				<b>ACCESS DENIED</b> ! <br>Invalid User's Credentials.
				</div><br><br>";
		$err=1;
	}
?>

<body>

<?php require("menunav.php");?>

<script>setActive("login");</script>

<main id="main" style="min-height:682px;">
	<section id="contact" class="contact">
		<div class="container" data-aos="fade-up" style="margin-top:90px;padding:20px;border-radius:5px;width:333px;text-align:center;border:1px solid #bbb;background:#eee">
		<img src="assets/img/logo_2.png" height="120px">
			<div class="section-title" style="">
				<div><small><a href="#">Enter either Email or Username</a></small></div><br>
			</div> 
			<div class="row" data-aos="fade-center" data-aos-delay="100">
				<div class="mt-5 mt-lg-0" data-aos="fade-right" data-aos-delay="100">
				  <?php echo $m ;?> 
					<form action="login1.php" method="post" enctype="multipart/form-data">
						<div class='row' style='margin-top:-55px'> 
							<div class='form-group mt-3'>
								<input class="form-control" type="email" name="mail" placeholder="Email" >
							</div>		
							<div class='form-group mt-3'>
								<input class="form-control" type="text" name="user" placeholder="Username" >
							</div>		
							<div class='form-group mt-3'>
								<input type='password' class='form-control' name="pass" placeholder='Password' required >
							</div>		
							<div class='form-group mt-3'>
								<input type='submit' class='btn btn-primary form-control' name='login' value='Log In'/>
							</div>		
							<div class='form-group mt-3'>
								<small>Not registed? &nbsp; <a href='register.php'>Signup for Victory</a></small>
							</div>
						</div>
						
					</form>
				</div>
			</div>
		</div>
	</section>
</main>

<?php require("footer.php");?>

</body>

</html>