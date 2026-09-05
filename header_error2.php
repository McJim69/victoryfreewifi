<?php 
	require("connect.php"); 
	require("header.php"); 	
?>

<body>

<?php require("menunav.php");?>

<script>setActive("login");</script>

<main id="main"><br><br><br><br>

<!-- Installation Section -->
	<section id="contact" class="contact techbg">
		<div class="container" data-aos="fade-up" style="text-align:center">
			<img src="assets/img/error.png" height="240"><br><br>
			<h1 class="text-danger">Invalid User's Credential</h1>
			<h3>Please try again.</h3>
		
			<h7 class="text-primary text-uppercase">Need help? Check us on Facebook</h7>
			<h6 class="text-primary">
				<i class="icofont-facebook"></i><a href="https://www.facebook.com/jcmcyberworks">www.facebook.com/jcmcyberworks</a>
			</h6>
			<h1 class="text-primary"><button style="font-size:20px" class="btn btn-success" onclick="jump('login1.php')">Retry</button></h1>
		</div>
	</section><!-- End Contact Section -->
</main><!-- End #main -->

<?php require("footer.php");?>

</body>

</html>