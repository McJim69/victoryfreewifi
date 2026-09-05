<?php 
	require("connect.php"); 
	
	$error="";
	
	if (isset($_POST["login"])) {
		$user = $link->real_escape_string($_POST["user"]);
		$pass = $link->real_escape_string($_POST["pass"]);

		$query = "SELECT * FROM users WHERE username='$user' AND password='$pass'";
		$ex = $link->query($query) or die(mysqli_error($link));
		
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
				$_SESSION["lnam"]=$rs["lname"];	
				$_SESSION["type"]=$rs["account"];

				echo"<script>window.location = 'index.php';</script>";
			}else			

			$error = "Account NOT Activated.";

		}else

	$error = "Either wrong username or password.<h4>PLEASE TRY AGAIN</h4>";			
?>

<?php require("header.php"); ?>

<body>

<?php require("menunav.php"); ?>

<script>setActive("team");</script>

<main id="main"><br><br><br><br>
	<section id="contact" class="contact">
		<div class="container" data-aos="fade-up" style="text-align:center">
			<img src="assets/img/error.png" height="250"><br><br>
			<h3 class='text-primary'>Something went wrong:</h3>
			<h3 class='text-danger text-center'><?php echo $error; ?></h3>
			
			<h7 class="text-uppercase">Need help? Contact the webmaster</h7>
			<h6 class="text-primary">
				<i class="icofont-email"></i> admin@victoryfreewifi.net<br>
				<i class="icofont-phone"></i> +639776848642
			</h6><br>
			<a rel="facebox" href="login.php">
				<h1 class="text-primary"><button style="font-size:20px" class="btn btn-success">Retry</button></h1>
			</a>
		</div>
	</section>
</main>

	<?php require("footer.php"); } ?>

</body>

</html>