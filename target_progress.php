<?php require("sites_charts.php");?>

<style>
	.responsive-two-column-grid {
		display:block;
	}

	@media (min-width:768px) {
		.responsive-two-column-grid {
			display: grid;
			grid-template-columns: 1fr 1fr;
		}
	}
</style>	

<section id="faq" class="faq" style='background:#bbb'>
	<div class="container" data-aos="fade-up" style="text-align:center">
        <div class="section-title">
          <h2>ROLLOUT PROGRESS</h2>
        </div>
		<div style="text-align:center;margin-top:-50px;padding:20px">
			<a rel="facebox" href="reportModal.php">
			<button class='btn-rollout btn-sm btn-light text-primary' style="margin-top:5px">
				View Summary Report
			</button>
			</a>
		</div>
		<div class="responsive-two-column-grid" style='background:#fff;border-radius:10px'>
			<div id="chartContainer1" style="margin:20px;height:200px"></div>
			<div id="chartContainer2" style="margin:20px;height:200px"></div>
		</div>
	</div>
</section>
