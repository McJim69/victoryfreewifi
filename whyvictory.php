<?php require("sites_charts2.php");?>

<section id="cta" class="cta" style="padding:60px;background:linear-gradient(rgba(255, 0, 0, 0.8), rgba(255, 200, 200, 0.6)), url(assets/img/colab-bg.jpg?<?php date("his");?>) fixed center center;">
	<div class="container">
		<div class="row" data-aos="zoom-in">
			<div class="col-lg-12 text-center text-lg-left">
				<h3>Why Victory Free WiFi?</h3>
				<p> 
					The Victory Free WiFi project was launched in response to the COVID-19 pandemic, a time when internet 
					access became essential for sustaining government operations, programs, and services. The education 
					sector, in particular, adopted distance learning systems to comply with health and safety regulations.
				</p>
				<p>	
					Initiated on April 2021, by the Communication and Information Services Office (CISO) in partnership 
					with the DICT provincial office, the project is now fully operational and entering Phase 2. 
					This next phase focuses on expanding installations to public schools, ensuring broader 
					access to reliable internet for students and educators.
				</p>
			</div>
		</div>
		<div class="col-lg-12 text-center">
			<a class="cta-btn align-middle" rel="facebox" href="reportModal.php">View Report</a>
		</div>
	</div>
	<br>
	<div class="container text-center" data-aos="fade-up">
		<div class="col-lg-12" style="opacity:.8">
			<div class="row">
				<div class="col-lg-6" style="padding:20px">
					<div style="background:#fff;padding:20px;#fff;border-radius:10px;box-shadow: rgba(0, 0, 0, 0.16) 0px 3px 6px, rgba(0, 0, 0, 0.23) 0px 3px 6px;">
						<div id="chartContainer1" style="height:280px;"></div>
					</div>
				</div>
				<div class="col-lg-6" style="padding:20px">
					<div style="background:#fff;padding:20px;#fff;border-radius:10px;box-shadow: rgba(0, 0, 0, 0.16) 0px 3px 6px, rgba(0, 0, 0, 0.23) 0px 3px 6px;">
						<div id="chartContainer2" style="height:280px;"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>