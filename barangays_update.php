<?php 
	require("connect.php");
				
	$ex=$link->query("select * from barangays order by bid");
								
	while($rs=mysqli_fetch_array($ex)){		
		$exs = $link->query("select * from sites where bid='".$rs[0]."' and mcode='".$rs["mcode"]."' ");
	
		while($rss=mysqli_fetch_array($exs)){
			
			$update = $link->query("update barangays set win = 'Yes' where bid='".$rss["bid"]."'");
			$update = $link->query("update barangays set los = 'Yes' where bid='".$rss["bid"]."'");
		
			if($update==TRUE){
				echo "<script>alert('Barangays Data Updated Successfully!');
				window.location='admin.php'</script>";			
			}else{
				$error = mysqli_error($link);
			}
		}
	}	
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