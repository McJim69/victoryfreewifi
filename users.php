<?php
	require("connect.php");
	require("header.php");
	
	if(!isset($_SESSION['user'])){
		header("location:index.php");
		exit();
	}
?>

<body>

<?php require("menunav.php");?>

<script>setActive("admin");</script>
<script>setActive("users");</script>

<main id="main">
	<section id="breadcrumbs" class="breadcrumbs" >
		<div class="container" style="margin-bottom:-15px">
			<ol>
				<li><a href="index.php">Home</a></li>
				<li><a href="admin.php">Admin</a></li> 
				<li>Users</li> 
			</ol>
			<h2>Users &nbsp; 
				<a rel="facebox" href="register1.php">
					<button class="btn-rollout" style="margin-bottom:5px">
						Add User
					</button>
				</a>
			</h2>
		</div>
	</section>
	<section style="margin-top:-45px;min-height:535px" >
		<div class="container" data-aos="fade-up">
			<div class="row justify-content-center">		
				<?php					
					$ex = $link->query("SELECT * FROM users");
					while($rs=mysqli_fetch_array($ex)){						

					if($rs["status"]==1){
						$stats="Active";
						$color="style='color:green'";
					}else{
						$stats="Inactive";
						$color="style='color:red'";
					}

					$first = $rs["fname"];
					$fname = ucwords(strtolower($first));
					
					$lastn = $rs["lname"];
					$lname = ucwords(strtolower($lastn));

					$email=$rs["email"];
					$phone=$rs["phone"];
					$usern=$rs["username"];
					$pword=$rs["password"];
					$accnt=$rs["account"];
					$photo=$rs["photo"];	

					if(isset($_POST["b_upImg_$rs[0]"])){
						move_uploaded_file($_FILES["b_file_$rs[0]"]["tmp_name"], "assets/img/users/$rs[0].jpg");
						$link->query("update users set photo=1 where usrid='$rs[0]'");
						jump("");
					}	
				
				echo"
				<div class='admin-box align-items-center mobile col-lg-3' data-aos='fade-up' data-aos-delay='100' id='div_$rs[0]'>									
					<div class='row'>
						<div class='admin-logo col-lg-2'>
							<img class='alogo' width='100' height='100'";
								if(file_exists("assets/img/users/$rs[0].jpg")){			
									echo" src='assets/img/users/$rs[0].jpg?".date("h:i:s")."'/>";
								}else{
									echo" src='assets/img/user.png?".date("h:i:s")."' />";
								}
							echo"
						</div>
						<div class='admin-content col-lg-2'>
							<h6 $color>$fname $lname</h6>
							<div style='margin-top:-5px'><small>User: $usern &bull; $accnt</small></div>
							<div style='margin-bottom:5px'><small><i class='fa fa-phone'></i> $phone</small></div>
							<form action='#' method='POST' enctype='multipart/form-data'>
								<div style='margin-top:-5px'>
									<input type=file name='b_file_$rs[0]' id='b_file_$rs[0]' style='display:none' onchange=\"if(this.value!='')$('#b_upImg_$rs[0]').click();\"/> 
									<input type=submit name='b_upImg_$rs[0]' id='b_upImg_$rs[0]' value='Upload' style='display:none'/> 
									<a onclick=\"$('#b_file_$rs[0]').click();\" title='Change Photo'>
										<i class='btn-admin fa fa-image bg-success' style='cursor:pointer'></i>
									</a>
									<a rel='facebox' href='users_edit.php?users=$rs[0]' title='Edit'>
										<i class='btn-admin fa fa-edit bg-info'></i>
									</a>";
									if($_SESSION["type"]=="Admin"){
									echo"<a onclick=\"deleteUser('$rs[0]')\" title='Remove'>
										 <i class='btn-admin fa fa-trash bg-danger' style='cursor:pointer'></i>";
									}
									echo"
									</a>
								</div>
							</form>
						</div>			
					</div>
				</div>";
				} 
			?>

			</div>		
		</div>
	</section>
</main>

<?php require("footer.php");?>

</body>

</html>

<script>
	function deleteUser(usrid){	
		if(confirm("Are you sure you want to Remove this User User?")){
			xmlhttp.onreadystatechange=function(){
				if (xmlhttp.readyState==4 && xmlhttp.status==200){
					if(xmlhttp.responseText=="Success"){
						$("#div_"+usrid).animate({
					opacity:0
					},500);
				}else{
					alert(xmlhttp.responseText);
					}
					$("#div_"+usrid).animate({
					opacity:0
					},500);
				}
			}					
			xmlhttp.open("GET","delete_user.php?usrid="+usrid,true);
			xmlhttp.send();
		}
	}
</script>

<script>	
	if ( window.history.replaceState ) {
	  window.history.replaceState( null, null, window.location.href );
	}
</script>