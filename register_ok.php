<?php 
	require("connect.php"); 
	require("header.php"); 	
?>

<body>

<?php require("menunav.php");?>

<script>setActive("login");</script>

<main id="main"><br><br><br><br>

<!-- Installation Section -->
	<section id="contact" class="contact">
		<div class="container" data-aos="fade-up" style="text-align:center">
			<img src="assets/img/success1.png" height="350">
			<h4 class="text-primary">
				Your account has been created successfully. <br>
				Check your Email Address for activation.
			</h4>
			<h7 class="text-uppercase">Need help? Contact the webmaster</h7>
			<h6 class="text-primary">
				<i class="icofont-email"></i> admin@victoryfreewifi.net<br>
				<i class="icofont-phone"></i> +639776848642
			</h6><br>
			<h1 class="text-success"><button style="font-size:20px" class="btn btn-success" onclick="javascript:history.back()">Continue</button></h1>
		</div>
	</section><!-- End Contact Section -->
</main><!-- End #main -->

<?php require("footer.php");?>

</body>

</html>