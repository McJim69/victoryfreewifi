<form action='login_proc.php' method='POST' enctype='multipart/form-data'>
<section style="width:300px">
	<div style="margin:-40px 5px -40px 5px">
		<div style="text-align:center" data-aos="fade-up">			
			<div>
				<img src="assets/img/logo_2.png?<?php date("h:i:s")?>" style="width:150px">
				<br><h4 class="text-danger">Admin Login</h4>

				<div class="form-group" style="margin-top:10px">
					<input class="form-control" type="text" name="user" placeholder="User Name" required >
				</div>
				<div class="form-group" style="margin-top:10px">
					<input type='password' class='form-control' name="pass" placeholder='Password' required >
				</div>
				<div class="form-group" style="margin-top:10px">
					<input type='submit' class='btn btn-danger form-control' name='login' value='Log In' >
				</div>
				<div class="form-group" style="margin-top:10px">
					<small>Not registered? &nbsp; <a href='register.php'>Signup for Victory</a></small>
				</div>	
			</div>
		</div>
	</div>
</section>
</form>	