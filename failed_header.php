<?php require("header.php"); ?>

<body>

<?php require("menunav.php");?>

<script>setActive("login");</script>

<main id="main"><br><br><br><br>
	<section id="contact" class="contact techbg">
		<div class="container" data-aos="fade-up" style="text-align:center">

			<img src='assets/img/error.png' height='350'>
			<h3 class='text-primary'>Something went wrong</h3>
			<h4 class='text-danger text-center'>

			<?php	mysqli_error($link); ?>

			</h4>
			<h4>Please try again.</h4>
		
			<h7 class="text-primary text-uppercase">Need help? Check us on Facebook</h7>
			<h6 class="text-primary">
				<i class="icofont-facebook"></i><a href="https://www.facebook.com/jcmcyberworks">www.facebook.com/jcmcyberworks</a>
			</h6>
			<h1 class="text-primary"><button style="font-size:20px" class="btn btn-success" onclick="jump('index.php')">Continue</button></h1>
		</div>
	</section>
</main>

<?php require("footer.php");?>

</body>

</html>