<?php
	require("connect.php");
	require("header.php");
	error_reporting(0);
?>

<body>

<?php require("menunav.php");?>

<script>setActive("team");</script>
<script>setActive("admin");</script>
<script>setActive("installers");</script>

<main id="main">
	<section id="breadcrumbs" class="breadcrumbs" >
		<div class="container" style="margin-bottom:-15px">
			<ol>
				<li><a href="index.php">Home</a></li>
				<li><a href="admin.php">Admin</a></li> 
				<li>Installers</li> 
			</ol>
			<h2>Installers &nbsp; 
				<?php 
					if(isset($_SESSION['user'])){	
					echo"
						<a rel='facebox' href='installers_add.php'>
							<button class='btn-rollout' style='margin-bottom:5px'>
								Add Installer
							</button>
						</a>
						<a href='accomplishment.php'>
							<button class='btn-rollout' style='margin-bottom:5px'>
								Accomplishment
							</button>
						</a>";
					}
				?>
			</h2>
		</div>
	</section>
	<section style="min-height:500px;" >
		<div class="container" data-aos="fade-up">
			<div class="row justify-content-center">		
				<?php					
					$ex = $link->query("SELECT * FROM installer");
					while($rs=mysqli_fetch_array($ex)){						
					$tcod=$rs["tcode"];
					$fcod=$rs["fieldcode"];
					$lead=$rs["leader"];
					$cpno=$rs["phone"];
					$face=$rs["facebook"];
					$timg=$rs["photo"];	

					if(isset($_POST["b_upImg_$rs[0]"])){
						move_uploaded_file($_FILES["b_file_$rs[0]"]["tmp_name"], "assets/img/installers/$rs[0].jpg");
						$link->query("update installer set photo=1 where tid='$rs[0]'");
						jump("");
					}	
				echo"
				<div class='admin-box col-lg-3' style='margin:10px' data-aos='fade-up' data-aos-delay='100' id='div_$rs[0]'>									
					<div class='row'>
						<div class='admin-logo col-lg-2'>
						<a href='$face' target='_blank'>
							<img class='alogo' style='100px;aspect-ratio:2/2'";
								if(file_exists("assets/img/installers/$rs[0].jpg")){			
									echo" src='assets/img/installers/$rs[0].jpg?".date("h:i:s")."'/>";
								}else{
									echo" src='assets/img/user.png?".date("h:i:s")."' />";
								}
							echo"</a>
						</div>
						<div class='admin-content col-lg-2'>
							<h6 class='fa' style='letter-spacing:2px'>
								$tcod 
								$fcod
							</h6>
							<div><small>$lead</small></div>
							<div style='margin-bottom:5px'><small><i class='fa fa-phone'></i> $cpno</small></div>
							<form action='#' method='POST' enctype='multipart/form-data'>
								<div style='margin-bottom:-5px'>";
								if(isset($_SESSION['user'])){
									echo"
									<a href='$face' title='Facebook' target='_blank'>
										<i class='btn-inst ri-facebook-fill bg-primary'></i>
									</a>
									<input type=file name='b_file_$rs[0]' id='b_file_$rs[0]' style='display:none' onchange=\"if(this.value!='')$('#b_upImg_$rs[0]').click();\"/> 
									<input type=submit name='b_upImg_$rs[0]' id='b_upImg_$rs[0]' value='Upload' style='display:none'/> 
									<a onclick=\"$('#b_file_$rs[0]').click();\" title='Change Photo'>
										<i class='btn-inst ri-user-fill bg-success' style='cursor:pointer'></i>
									</a>
									<a rel='facebox' href='installers_edit.php?installer=$rs[0]' title='Edit'>
										<i class='btn-inst ri-edit-fill bg-info'></i>
									</a>";
								}
									if($_SESSION["type"]=="Admin"){	
									echo"
										<a onclick=\"deleteTeam('$rs[0]')\" title='Remove'>
											<i class='btn-inst ri-close-fill bg-danger' style='cursor:pointer'></i>
										</a>";
									}
								echo"
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
	function deleteTeam(tid){	
		if(confirm("Are you sure you want to Remove this Team Leader?")){
			xmlhttp.onreadystatechange=function(){
				if (xmlhttp.readyState==4 && xmlhttp.status==200){
					if(xmlhttp.responseText=="Success"){
						$("#div_"+tid).animate({
					opacity:0
					},500);
				}else{
					alert(xmlhttp.responseText);
					}
					$("#div_"+tid).animate({
					opacity:0
					},500);
				}
			}					
			xmlhttp.open("GET","delete_installer.php?tid="+tid,true);
			xmlhttp.send();
		}
	}
</script>

<script>	
	if ( window.history.replaceState ) {
	  window.history.replaceState( null, null, window.location.href );
	}
</script>