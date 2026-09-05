<?php
	require("connect.php");

	$value=$_GET['value'];

	if(isset($_POST["b_search"])){
		$value=$_POST["t_search"];
	}
				
	$rec=2000;
	$p=$_GET['page'];
	if($p>1){
		$to=$rec;
		$from=($p*$rec)-$rec;
		$i=(($p-1)*$rec)+1;
	}else{
		$to=$rec;
		$from=0;
		$i=1;
		$p=1;
	}

	$ex=$link->query("select * from sites_detail d where 
	   (d.id like'%".$value."%' or
		d.site_id like'%".$value."%' or
		d.device_code like'%".$value."%' or
		d.device_name like'%".$value."%' or
		d.serial_mac like'%".$value."%') order by device_name LIMIT $from,$to ");

	while($rs=mysqli_fetch_array($ex)){	
		$did=$rs["id"];
		$sid=$rs["site_id"];
		$cod=$rs["device_code"];
		$mac=$rs["serial_mac"];
			
		$macrep=preg_replace('/[^a-zA-Z0-9_ %\[\]\.\(\)%&-]/s', '', $mac);
		$macupr=strtoupper($macrep);
			
		$update = $link->query("update sites_detail set serial_mac = replace(serial_mac, '$mac', '$macupr')  where id='$did'");
		
		if($update==TRUE){
			echo "<script>alert('Device Data Updated Successfully!');
			window.location='admin.php'</script>";			
		} else {
			$error = mysqli_error($link);
		}
	}					
?>

<?php require("header.php"); ?>

<body>

<?php require("menunav.php"); ?>

<script>setActive("admin");</script>

<main id="main"><br><br><br><br>
	<section id="contact" class="contact">
		<div class="container" data-aos="fade-up" style="text-align:center">
			<img src="assets/img/error.png" height="250"><br><br>
			<h3 class='text-primary'>Something went wrong :</h3>
			<h4 class='text-danger text-center'>

			<?php echo $error; ?>

			</h4>
			<h4>PLEASE TRY AGAIN</h4>
		
			<h7 class="text-uppercase">Need help? Check us on Facebook</h7>
			<h6 class="text-primary">
				<i class="icofont-facebook"></i><a href="https://www.facebook.com/jcmcyberworks">www.facebook.com/jcmcyberworks</a>
			</h6><br>
			<h1 class="text-primary"><button style="font-size:20px" class="btn btn-success" onclick="javascript:history.back()">Retry</button></h1>
		</div>
	</section>
</main>

<?php require("footer.php"); ?>

</body>

</html>