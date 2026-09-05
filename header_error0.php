<?php 
	require("connect.php"); 
	require("header.php"); 	
?>

<body>

<?php require("menunav.php");?>

<script>setActive("login");</script>

<main id="main"><br><br><br><br>
	<section id="contact" class="contact techbg">
		<div class="container" data-aos="fade-up" style="text-align:center">
			<img src="assets/img/error.png" height="240"><br><br><br>
			<h1 class="text-danger">ACCESS DENIED: Inactive Account!</h1>
		
			<h7 class="text-primary text-uppercase">Need help? Contact us on Email</h7>
			<h6 class="text-primary">admin@victoryfreewifi.net<i class="icofont-facebook"></i>
				</h6>
			<h1 class="text-primary"><button style="font-size:20px" class="btn btn-success" onclick="javascript:history.back()">Continue</button></h1>
		</div>
	</section><!-- End Contact Section -->
</main><!-- End #main -->

<?php require("footer.php");?>

</body>

</html>