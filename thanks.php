<?php 
	require("connect.php"); 
	require("header.php"); 	
?>

<body>

<?php require("menunav.php");?>

<main id="main"><br><br><br><br>

<!-- Installation Section -->
	<section id="contact" class="contact">
		<div class="container" data-aos="fade-up" style="text-align:center">
			<img src="assets/img/success1.png" height="350">
			<h4 class="text-primary">Your message was sent successfully.</h4>
			<h5 class="text-uppercase"><b>Thank you for getting in touch!</b></h5>
			<h6 class="text-primary">
				We appreciate your interest on our services. One of our colleagues will get back to you shortly.
			</h6>
			<h1 class="text-success"><button style="font-size:20px" class="btn btn-success" onclick="jump('index.php')">Continue</button></h1>
		</div>
	</section><!-- End Contact Section -->
</main><!-- End #main -->

<?php require("footer.php");?>

</body>

</html>