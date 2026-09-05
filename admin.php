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

<main id="main">
	<section id="breadcrumbs" class="breadcrumbs" >
		<div class="container">
			<ol>
				<li><a href="index.php">Home</a></li>
				<li>Admin</li> 
				<li>Settings</li> 
			</ol>
			<h2>Settings &nbsp; 
				<a rel="facebox" href="admin_add.php">
					<button class="btn-rollout" style="margin-bottom:5px">
						Add Settings
					</button>
				</a>
			</h2>
		</div>
	</section>
	<section style="margin-top:-39px;min-height:515px;" >
		<div class="container" data-aos="fade-up">
			<div class="row justify-content-center">		
				<?php					
					$exa=$link->query("SELECT * FROM admin");
					while($rsa=mysqli_fetch_array($exa)){						
					$tit=$rsa["title"];
					$img=$rsa["image"];
					$lnk=$rsa["link0"];
					$lk1=$rsa["link1"];
					$lk2=$rsa["link2"];
					$lk3=$rsa["link3"];
				?>
				<div class="admin-box align-items-center mobile col-lg-3" data-aos="fade-up" data-aos-delay="100">									
					<div class="row">
						<div class="admin-logo col-lg-2">
							<a class="adlink" <?php echo $lnk;?> >
								<img class="alogo" src="<?php echo $img;?>">
							</a>
						</div>
						<div class="admin-content col-lg-2">
							<a class="adcontit" <?php echo $lnk;?>><h5 class='text-uppercase'><?php echo $tit;?></h5></a>
							<ul style="margin-bottom:0">
								<small>
									<li><a class="adlink" <?php echo $lk1;?></a></li>
									<li><a class="adlink" <?php echo $lk2;?></a></li>
									<li><a class="adlink" <?php echo $lk3;?></a></li>
								</small>
							</ul>
						</div>
					</div>
				</div>
				
				<?php } ?>
				
			</div>		
		</div>
	</section>
</main>

<?php require("footer.php");?>

</body>

</html>

<script>
	function deleteTeam(cid){	
		if(confirm("Are you sure you want to Remove this Team Member?")){
			xmlhttp.onreadystatechange=function(){
				if (xmlhttp.readyState==4 && xmlhttp.status==200){
					if(xmlhttp.responseText=="Success"){
						$("#div_"+cid).animate({
					opacity:0
					},500);
				}else{
					alert(xmlhttp.responseText);
					}
					$("#div_"+cid).animate({
					opacity:0
					},500);
				}
			}					
			xmlhttp.open("GET","delete_team.php?cid="+cid,true);
			xmlhttp.send();
		}
	}
</script>

<script>	
	if ( window.history.replaceState ) {
	  window.history.replaceState( null, null, window.location.href );
	}
</sript>