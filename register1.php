<form action='register_proc.php' method='POST' enctype='multipart/form-data'>
<section style="width:300px">
	<div style="margin:-40px 5px -40px 5px">		
		<div class="text-center">
			<img src="assets/img/logo_2.png?<?php date("h:i:s")?>" style="height:120px">
			<br><h4 class="text-danger">Register User Account</h4>
		</div>
		<div class="form-group">
			<input style="margin:5px 0 5px 0" class="form-control" type="text" name="fname" placeholder="First Name" required >
			<input style="margin:5px 0 5px 0" class="form-control" type="hidden" name="mname" placeholder="Middle Name" >
		</div>
		<div class="form-group">
			<input style="margin:5px 0 5px 0" class="form-control" type="text" name="lname" placeholder="Family Name" required >
		</div>	
		<div class="form-group">
			<input style="margin:5px 0 5px 0" class="form-control" type="email" name="email" placeholder="Email Address" required >
		</div>
		<div class="form-group">
			<input style="margin:5px 0 5px 0" class="form-control" type="text" name="phone" placeholder="Phone Number" required >
		</div>
		<div class="form-group">
			<input style="margin:5px 0 5px 0" class="form-control" type="text" name="username" placeholder="Username"  required >				
		</div>
		<div class="form-group">
			<input style="margin:5px 0 5px 0" class="form-control" type="password" name="password" placeholder="Password"  required >
		</div>
		<div class="form-group">
			<select style="margin:5px 0 5px 0" class="form-control" name="account" >
				<option value="">Account Type</option>
				<option value="Users">Users</option>
				<option value="Admin">Admin</option>
				<option value="Leader">Leader</option>
			</select>
		</div>
		<div class="form-group">
			<input style="margin:5px 0 5px 0" class="form-control btn btn-danger" type="submit" name="bSave" value="Submit" >
		</div>	
	</div>
</section>
</form>