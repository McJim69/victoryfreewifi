<section id="contact" class="contact">
	<div class="container">
	<div style="text-align:center;margin-top:-40px;"><img src="assets/img/logo_2.png" height="150" width="170"></div>
	<div class="section-title" style="margin:10px">
		<h3 class="text-primary">Add Admin Settings</h3><br>
	</div>
		<form action='admin_add_proc.php' method='POST' enctype='multipart/form-data' style='margin-top:-70px;' >
			<div class='mt-5 mt-lg-0' style='width:350px;text-align:center;margin-bottom:-40px;padding-right:10px'>			
				<div class="row">
					<div class="form-group mt-3">
						<input type="text" style="margin:-5px 10px 0 5px" class="form-control" name="title" placeholder="Category Title" required>
					</div>
					<div class="form-group mt-3">
						<input type="text" style="margin:-5px 10px 0 5px" class="form-control" name="image" placeholder="Image / Logo URL" required>
					</div>
					<div class="form-group mt-3">
						<input type="text" style="margin:-5px 10px 0 5px" class="form-control" name="link1" placeholder="Content Link 1" required>
					</div>
					<div class="form-group mt-3">
						<input type="text" style="margin:-5px 10px 0 5px" class="form-control" name="link2" placeholder="Content Link 2" required>
					</div>
					<div class="form-group mt-3">
						<input type="text" style="margin:-5px 10px 0 5px" class="form-control" name="link3" placeholder="Content Link 3" required>
					</div>
					<div class="form-group mt-3">
						<input style="margin:5px 10px 0 5px" class="form-control btn btn-primary" type="submit" name="bSave" value="Submit">
					</div>
				</div>	
			</div>	
		</form>
	</div>	
</section>