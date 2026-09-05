<section id="portfolio-details" class="portfolio-details">
	<div class="container">
		<div class="portfolio-details-container">
			<?php 
				echo"
				<span style='font-size:30px' class='report-header'><b>$post</b></span> &nbsp;
				<span>(Sites without Photos are Excluded)</span>";
			?>
		</div><br>
		<div class="row justify-content-center">	
			<?php
				$dir = "assets/img/sites/primary/";
				$img = glob($dir . "*.{jpg,png,gif}", GLOB_BRACE);

				foreach($img as $image) {
				$file = $image;
				$fwex = pathinfo($file, PATHINFO_FILENAME);

				$ex=$link->query("select * from sites where sid = $fwex") or die(mysqli_error($link));
				while($rs=mysqli_fetch_array($ex)){	

				echo"
					<div class='col-lg-3'>
						<div style='margin-bottom:20px;text-align:center'>
							<a href='site_details.php?sites=$rs[0]'>
								<img width='100%' height='150px' 
								src='assets/img/sites/primary/$rs[0].jpg' 
								style='border-radius:5px;box-shadow:rgba(0,0,0,0.35) 0px 5px 15px'><br>
								<small><b>".$rs["mcode"]." ".$rs["barangay"]." ".$rs["place"]."</b></small>
							</a>
						</div>
					</div>";		
					}
				}
			?>
		</div>
	</div>
</section>