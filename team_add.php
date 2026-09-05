<section id="contact" class="contact">
	<div class="container">
	<div style="text-align:center;margin-top:-40px;"><img src="assets/img/logo_2.png" height="150" width="170"></div>
	<div class="section-title" style="margin:10px">
		<h3 class="text-danger">Add Team Member</h3><br>
	</div>
		<form action='team_add_proc.php' method='POST' enctype='multipart/form-data' style='margin-top:-70px;' >
			<div class='mt-5 mt-lg-0' style='width:350px;text-align:center;margin-bottom:-40px;padding-right:10px'>			
				<div class="row">
					<div class="form-group mt-3">
						<input type="text" style="margin:-5px 10px 0 5px" class="form-control" name="position" placeholder="Position" required>
					</div>
					<div class="form-group mt-3">
						<input type="text" style="margin:-5px 10px 0 5px" class="form-control" name="fullname" placeholder="Fullname" required>
					</div>
					<div class="form-group mt-3">
						<input type="text" style="margin:-5px 10px 0 5px" class="form-control" name="description" placeholder="Job Description" required>
					</div>
					<div class="form-group mt-3">
						<input type="text" style="margin:-5px 10px 0 5px" class="form-control" name="phonenumber" placeholder="Cell Phone Number" required>
					</div>
					<div class="form-group mt-3">
						<input type="text" style="margin:-5px 10px 0 5px" class="form-control" name="socialmedia" placeholder="Facebook Account Link" required>
					</div>
					<div class="form-group mt-3">
						<input style="margin:5px 10px 0 5px" class="form-control btn btn-danger" type="submit" name="bSave" value="Submit">
					</div>
				</div>	
			</div>	
		</form>
	</div>	
</section>