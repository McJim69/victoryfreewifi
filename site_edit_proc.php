<?php
	require("connect.php");	

	if(isset($_POST['upDate'])){	
		$sid 		 = $_POST['sid'];
		$mcode 		 = $_POST['mcode'];
		$barangay 	 = $_POST['barangay'];
		$place 		 = $_POST['place'];
		$coordinates = $_POST['coordinates'];
		$link_ap_bst = $_POST['link_ap_bst'];
		$inst_date 	 = $_POST['inst_date'];
		$installer 	 = $_POST['installer'];
		$ip_address  = $_POST['ip_address'];
		$repair_date = $_POST['repair_date'];
		$repair_team = $_POST['repair_team'];
		$cont_person = $_POST['cont_person'];
		$cont_number = $_POST['cont_number'];
		$remarks 	 = $_POST['remarks'];

	$update = $link->query("UPDATE sites set
		sid 		 = '$sid',
		mcode 		 = '$mcode',
		barangay 	 = '$barangay',
		place 		 = '$place',
		coordinates  = '$coordinates',
		link_ap_bst  = '$link_ap_bst',
		inst_date 	 = '$inst_date',
		installer 	 = '$installer',
		ip_address 	 = '$ip_address',
		repair_date  = '$repair_date',
		repair_team  = '$repair_team',
		cont_person  = '$cont_person',
		cont_number  = '$cont_number',
		remarks 	 = '$remarks' where sid = '$sid'");

		if(($update)== TRUE){
			echo"<script>location.href='site_details.php?sites=".$sid."';</script>";
		}else{
			$error=mysqli_error($link);
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