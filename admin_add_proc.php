<?php 
	require("connect.php");

	if(isset($_POST["bSave"])){
		
	$insert = $link->query("INSERT INTO  admin VALUES (0,
		'".$_POST["title"]."',
		'".$_POST["image"]."',
		'".$_POST["link1"]."',
		'".$_POST["link2"]."',
		'".$_POST["link3"]."')");	

		if(($insert)==TRUE){
			echo"<script>history.back();</script>";
		}else{
			$error = mysqli_error($link);
		//
	//
?>

<?php require("header.php"); ?>

<body>

<?php require("menunav.php"); ?>

<script>setActive("login");</script>

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