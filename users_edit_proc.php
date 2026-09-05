<?php 
	require("connect.php");

	if(isset($_POST['upDate'])){	
	
		$usrid 	 = $_POST['usrid'];
		$fname 	 = $_POST['fname'];
		$lname 	 = $_POST['lname'];
		$email 	 = $_POST['email'];
		$phone 	 = $_POST['phone'];
		$usern 	 = $_POST['username'];
		$pword 	 = $_POST['password'];
		$accnt 	 = $_POST['account'];
		$stats 	 = $_POST['status'];

	$update = $link->query("UPDATE users set
		usrid 	 = '$usrid',
		fname 	 = '$fname',
		lname 	 = '$lname',
		email	 = '$email',
		phone    = '$phone',
		username = '$usern',
		password = '$pword',
		account  = '$accnt',
		status   = '$stats' where usrid = '$usrid'");

		if(($update)== TRUE){
			echo"<script>history.back();</script>";
		}else{
			$error = mysqli_error($link);
		//
	//
?>

<?php require("header.php"); ?>

<body>

<?php require("menunav.php"); ?>

<script>setActive("team");</script>

<main id="main"><br><br><br><br>
	<section id="contact" class="contact">
		<div class="container" data-aos="fade-up" style="text-align:center">
			<img src="assets/img/error.png" height="250"><br><br>
			<h3 class='text-primary'>Something went wrong :</h3>
			<h4 class='text-danger text-center'>

			<?php echo $error; ?>

			</h4>
			<h4>PLEASE TRY AGAIN</h4>
		
			<h7 class="text-uppercase">Need help? Contact the webmaster</h7>
			<h6 class="text-primary">
				<i class="icofont-email"></i> admin@victoryfreewifi.net<br>
				<i class="icofont-phone"></i> +639776848642
			</h6><br>
			<h1 class="text-primary"><button style="font-size:20px" class="btn btn-success" onclick="javascript:history.back()">Retry</button></h1>
		</div>
	</section>
</main>

<?php require("footer.php"); } } ?>

</body>

</html>