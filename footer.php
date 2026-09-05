<?php require("sites_count.php");?>

<footer id="footer" style="background:darkred">
<!-- Footer Top -->
<div class="footer-top" style="position:relative;background:linear-gradient(rgba(255, 0, 0, 0.2), rgba(255, 0, 0, 0.4))">	
	<div style="position:absolute;top:0px;left:20px;">
		<img src="assets/img/rplogo2.png" height="200">
	</div>
	<div style="position:absolute;top:35px;right:40px;">
		<img src="assets/img/zdslogo2.png" height="125">
	</div>
	<div class="container">
		<div class="row">					
			<div class="col-xs-12 col-sm-6 col-lg-3" style="margin-bottom:20px">
				<h4>Additional Links</h4>
				<div style="margin-top:-10px"><i class="bx bx-chevron-right"></i> <a class="alink" href="index.php">Home</a></div>
				<div><i class="bx bx-chevron-right"></i> <a class="alink" href="disclaimer.php">Disclaimer</a></div>
				<div><i class="bx bx-chevron-right"></i> <a class="alink" href="privacy.php">Privacy Policy</a></div>
				<div><i class="bx bx-chevron-right"></i> <a class="alink" href="terms.php">Terms of Service</a></div>
			</div>
			<div class="col-xs-12 col-sm-6 col-lg-3" style="margin-bottom:20px">
				<h4><i class="fa text-warning" style="letter-spacing: 2px"><?php echo $totALL ;?></i> Sites & Counting </h4>
				<div style="margin-top:-10px">Barangay/Municipal Halls <b class="text-warning"><?php echo $totBMH ;?></b></div>
				<div>Elem/Senior/High Schools <b class="text-warning"><?php echo $totSCH ;?></b></div>
				<div>Other Public Place Stations <b class="text-warning"><?php echo $totOTH ;?></b></div>
				<div><a href="sites_list.php" class="alink">See More...</a></div>
			</div>
			<div class="col-xs-12 col-sm-6 col-lg-3" style="margin-bottom:20px">
				<h4>Contact Us</h4>
				<div style="margin-top:-10px">Capitol Compound, Urro Street</div>
				<div>Pagadian City 7016, Phillipines</div>
				<div>
					<strong><i class="icofont-envelope"></i> Email:</strong> admin@victoryfreewifi.net
				</div>
				<div>
					<strong><i class="icofont-web"></i> Website:</strong> 
					<a class="alink" href="https://victoryfreewifi.net" target="_blank">www.victoryfreewifi.net</a>
				</div>
			</div>
			<div class="col-xs-12 col-sm-6 col-lg-3" style="margin-bottom:20px">
				<h4>About the Project</h4>
				<div style="margin-top:-10px">Victory Free WiFi, dubbed 'Internet sa Barangay,' is an initiative of the Zamboanga del Sur Provincial Government launched on April 21, 2021.</div>					
			</div>
		</div>
	</div>
</div>

<!-- Footer Bottom -->
    <div class="container">
      <div class="copyright">
         Maintained 2021-<?php echo date("Y");?> by <strong><a href="#">
		 PGO-CISO-VFW</a> </strong> Pagadian Cty - Zamboanga del Sur - Philippines
      </div>
    </div>
</footer>

<!--
<a href="#" class="back-to-top"><i class="ri-arrow-up-line"></i></a>
-->

<div id="preloader"></div>

<!-- Vendor JS Files -->
<script src="assets/vendor/jquery/jquery.min.js"></script>
<script src="assets/vendor/jquery.easing/jquery.easing.min.js"></script>

<!-- Bootstrap bundle (includes Popper) -->
<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- Plugins that depend on jQuery -->
<script src="assets/vendor/owl.carousel/owl.carousel.min.js"></script>
<script src="assets/vendor/venobox/venobox.min.js"></script>
<script src="assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>

<!-- Utility scripts -->
<script src="assets/vendor/php-email-form/validate.js"></script>
<script src="assets/vendor/aos/aos.js"></script>

<!-- Template Main JS File -->
<script src="assets/js/main.js"></script>
