<?php 
	require("connect.php");

	if (isset($_POST['upDate'])) {

	$update = $link->query("UPDATE base_stations set
		bst_id 	 		= '".$_POST['bst_id']."',
		coordinates 	= '".$_POST['coordinates']."',
		tower_height 	= '".$_POST['tower_height']."',
		elevation		= '".$_POST['elevation']."',
		ip_address		= '".$_POST['ip_address']."' where bst_id = '".$_POST['bst_id']."'");

		if(($update) === TRUE){
			echo"<script>location.href='base_stations.php?base_stations=".$_POST['bst_id']."';</script>";
		}else{
			$error = mysqli_error($link);
		//
	//
?>

<?php require("header.php"); ?>

<body>

<?php require("menunav2.php"); ?>

<script>setActive("bst");</script>

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