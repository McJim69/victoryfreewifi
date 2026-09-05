<section id="contact" class="contact">
	<div class="container" data-aos="fade-up">
	<div style="text-align:center;margin-top:-50px;"><img src="assets/img/cron.png" height="130" width="130"></div>
	<div class="section-title" style="margin-top:20px">
	<h4>Add Device Category</h4>
		<form action='category_add_proc.php' method='POST' enctype='multipart/form-data'>
			<div class='mt-5 mt-lg-0' data-aos='fade-right' data-aos-delay='100' style='width:300px;text-align:center'>			
				<div class='row' style="margin-bottom:-70px"> 
					<div class='form-group mt-3'>
						<input class="form-control" type="text" name="cat_name" placeholder="Category Name" required >
					</div>
					<div class='form-group mt-3'>
						<input type='submit' class='btn btn-danger form-control' name='bSave' value='Submit' >
					</div>		
				</div>				
			</div>
		</form>
	</div>
</section>