<?php
	require("connect.php");
	require("header.php");
	
	if(!isset($_SESSION['user'])){
		header("location:index.php");
		exit();
	}
?>

<style>
	.dev-card{
		margin:15px;
		padding:15px;
		color:#545454;		
		background:#eee;
		border-radius:10px;
		box-shadow: rgba(0, 0, 0, 0.16) 0px 3px 6px, rgba(0, 0, 0, 0.23) 0px 3px 6px;
	}
	.dev-card:hover{
		color:#fff;
		margin:15px;
		padding:15px;
		background:darkred;
		border-radius:10px;
		box-shadow: rgba(0, 0, 0, 0.3) 0px 19px 38px, rgba(0, 0, 0, 0.22) 0px 15px 12px;
	}
</style>

<body>

<?php require("menunav.php");?>

<script>setActive("admin");</script>
<script>setActive("categories");</script>

<main id="main">
	<section id="breadcrumbs" class="breadcrumbs" >
		<div class="container" style="margin-bottom:-15px">
			<ol>
				<li><a href="index.php">Home</a></li>
				<li><a href="admin.php">Admin</a></li> 
				<li>Categories</li> 
			</ol>
			<h2>Categories &nbsp; 
				<a rel="facebox" href="category_add.php">
					<button class="btn-rollout" style="margin-bottom:5px">Add Category</button>
				</a>
				<a href="devices.php">
					<button class="btn-rollout" style="margin-bottom:5px">Show Device List</button>
				</a>
			</h2>
		</div>
	</section>
	<section style="margin-top:-60px;margin-bottom:-20px;min-height:550px;" >
		<div class="container" data-aos="fade-up" style='margin-bottom:-40px'>
			<div class="row justify-content-center" style="text-align:center;padding:20px">		
			<?php		
				$i=1;
				
				$cat_id="";
				$cat_nm="";
					
				$ex = $link->query("SELECT * FROM category");
				while($rs=mysqli_fetch_array($ex)){	

					$cat_id=$rs["cat_id"];
					$cat_nm=$rs["cat_name"];

				echo"
				<div class='col-lg-2 dev-card' data-aos='fade-up' data-aos-delay='100' id='div_$rs[0]'>									
					<div class='row'>
						<div class='style='margin-top:10px'>
							<img class='alogo' width='100' height='100' onclick=\"jump('devices.php?categories=$cat_nm')\" ";
								if(file_exists("assets/img/categories/$rs[0].png")){			
									echo" src='assets/img/categories/$rs[0].png?".date("h:i:s")."'/>";
								}else{
									echo" src='assets/img/device.png?".date("h:i:s")."' />";
								}
							echo"
						</div>
						<div>
							<h4>$cat_nm</h4>
							<form action='#' method='POST' enctype='multipart/form-data'>
								<div style='margin-top:-5px;opacity:.8'>";
									if($_SESSION["type"]=="Admin"){	
									echo"
										<a rel='facebox' href='category_edit.php?category=$rs[0]' title='Edit'>
											<i class='btn-inst fa fa-edit bg-info'></i>
										</a>
										<a href='' onclick=\"deleteCategory('$rs[0]')\">
											<i class='btn-inst fa fa-trash bg-danger' title='Delete'></i>
										</a>";
									}else{
										echo"<input class='btn success' onclick=\"jump('devices.php?categories=$cat_nm')\" value='Go to Device List' style='width:150px;margin:5px;padding:2px'>";									
									}
							echo"</div>
							</form>
						</div>			
					</div>
				</div>";
				$i++;
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
	function deleteCategory(cat_id){	
		if(confirm("Are you sure you want to Remove this Team Member?")){
			xmlhttp.onreadystatechange=function(){
				if (xmlhttp.readyState==4 && xmlhttp.status==200){
					if(xmlhttp.responseText=="Success"){
						$("#div_"+cat_id).animate({
					opacity:0
					},500);
				}else{
					alert(xmlhttp.responseText);
					}
					$("#div_"+cat_id).animate({
					opacity:0
					},500);
				}
			}					
			xmlhttp.open("GET","delete_category.php?cat_id="+cat_id,true);
			xmlhttp.send();
		}
	}
</script>

<script>	
	if ( window.history.replaceState ) {
	  window.history.replaceState( null, null, window.location.href );
	}
</script>