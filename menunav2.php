<?php error_reporting(0);?>
<style>
	.menuli{
		border-radius:5px;
	}

	.blinking {
		display:inline-block;  
		animation: blink 2s ease-in infinite;
	}

	@keyframes blink {
		from, to { opacity: 1 }
		50% { opacity: 0 }
	}
</style>

<!-- ======= Top Bar ======= -->
<div id="topbar" class="d-none d-lg-flex align-items-center fixed-top topbar-inner-pages">
	<div class="container d-flex align-items-center justify-content-between">
		<div class="contact-info">
			<ul>
				<li>
					<i class="icofont-home"></i><a href="index.php">Home</a> &nbsp; 
					<i class="icofont-envelope"></i><a href="#">admin@victoryfreewifi.net</a> &nbsp; 
					<i class="icofont-facebook"></i><a href="https://facebook.com/hashtag/victoryfreewifi" target="_blank">Facebook</a>
				</li>				
			</ul>
		</div>

		<div class='cta'>		
			<?php 
				//Log and Welcome User

				if (!isset($_SESSION['user'])) {
					echo "<i class='fa fa-lock text-warning'></i>
					<a style='background:transparent' rel='facebox' href='login.php'>
						<span class='blinking text-light' style='cursor:pointer'>               
							Login for Admin Privilege
						</span>
					</a>";	
				}else{			

				$exu=$link->query("SELECT * FROM users WHERE usrid='".$_SESSION["usid"]."' ");
				$uid=mysqli_fetch_array($exu);
				$usr=$uid[0];
				
					$uname=$_SESSION["user"];
					$uname=ucwords(strtolower($uname));

					$fname=$_SESSION["fnam"];
					$fname=ucwords(strtolower($fname));

					$mname=$_SESSION["mnam"];
					$mname=ucwords(strtolower($mname));

					$lname=$_SESSION["lnam"];
					$lname=ucwords(strtolower($lname));

					$atype=$_SESSION["type"];
					$atype=ucwords(strtolower($atype));					

					echo"
					<a style='background:transparent' rel='facebox' href='users_edit.php?users=$usr'>
						<span style='padding:0;margin:0'>
						
					<img style='border-radius:35px;width:35px;height:35px;' ";
					
					if(file_exists("assets/img/users/$usr.jpg")){			
						echo "src='assets/img/users/$usr.jpg'? ".date("h:i:s")." ' />";
					}else{
						echo "class='bg-default' src='assets/img/user.png? ".date("h:i:s")." '/>";
					}
						echo"<b class='text-light'> ".$fname." ".$lname."</b></a>
						</span>
					</a>"; 
				} 
				
				$qNew=$link->query("SELECT * FROM sites WHERE inst_date > DATE_SUB(CURDATE(), INTERVAL 1 WEEK) ");	
				$tNew=number_format(mysqli_num_rows($qNew),0);
				$aNew = mysqli_fetch_array($qNew);
				$news = $aNew ? $aNew[0] : '';

				if ($qNew->num_rows > 0) {
					echo"<span onclick=\"jump('sites.php')\" class='blinking text-light' style='cursor:pointer'>";
					echo"(<strong>$tNew</strong>) New Sites";
					echo"</span>";
				}
			?>	
		</div>
	</div>
</div>

<!-- ======= Header ======= -->
<header id="header" class="fixed-top header-inner-pages">
  <div class="container d-flex align-items-center justify-content-between" >
	<h1 class="logo">
		<a href="base_stations.php">BASE STATIONS</a>
	</h1>
		<nav class="nav-menu d-none d-lg-block text-uppercase">
			<ul>
				<li><a href='index.php' id='home' class='menuli'>Home</a></li>
				<li><a href='about.php' id='about' class='menuli'>About</a></li>
				<li class='drop-down'><a href='team.php' id='team' class='menuli'>Team</a>
					<ul>
						<li><a href='team.php'>Management</a></li>
						<li><a href='installers.php'>Team Leaders</a></li>
					</ul>
				</li>
				<li><a href='barangays.php' id='barangays' class='menuli'>Barangays</a></li>
				<!--<li><a href='base_stations.php' id='bst' class='menuli'>Base STN</a></li>-->
				<li class='drop-down'><a href='sites_list.php' id='sites' class='menuli'>Sites</a>
					<ul>
						<li><a href='sites_list.php'>List View</a></li>
						<li><a href='sites.php'>Card View</a></li>
						<li><a href='sites_status_mon.php'>Sites Status</a></li>
						<li><a id='bst' href='base_stations.php'>Base Stations</a></li>
					</ul>
				</li> 	
				<li><a href='documents.php' id='docs' class='menuli'>Docs</a></li>
				<li><a href='contact.php' id='contact' class='menuli'>Contact</a></li>

				<?php 
					if(isset($_SESSION['user'])){	
						echo"
						<li class='drop-down'><a href='admin.php' id='admin' class='menuli'>Admin</a>
							<ul>							
								<li><a href='users.php' id='users' class='menuli'>Users</a></li>
								<li><a href='accomplishment.php' id='rollout' class='menuli'>Rollout</a></li>
								<li><a href='servers_stats.php' id='servers' class='menuli'>Servers</a></li>
								<li><a href='backup.php' id='backup' class='menuli'>Backup</a></li>
								<li><a href='devices.php' id='devices' class='menuli'>Devices</a></li>
								<li><a href='categories.php' id='categories' class='menuli'>Category</a></li>
								<li><a href='installers.php' id='installers' class='menuli'>Installers</a></li>
								<li><a href='barangays.php' id='barangays' class='menuli'>Barangays</a></li>
							</ul>
						</li>
						<li><a onclick='sessionEnd()' class='menuli'>Logout</a></li>";
						
					}else{
						echo"<li><a rel='facebox' href='login.php' id='login' class='menuli'>Login</a></li>";
						echo"<li><a rel='facebox' href='register1.php' id='register' class='menuli'>Register</a></li>";	
					}
				?>
			</ul>
		</nav>
	</div>
	
	<div class="container">
		<div class="row">
			<div class="col-lg-3">
				<form method="post" enctype="multipart/form-data">
				<div class="btn-group" style="width:100%;box-shadow: rgba(0, 0, 0, 0.16) 0px 3px 6px, rgba(0, 0, 0, 0.23) 0px 3px 6px">
					<input style="width:75%;border:1px solid #bbb;padding:4px 4px 4px 12px" type="text" class="btn btn-light" placeholder="Type a keyword" name="t_search" id="t_search" value="<?php if (!empty($_POST["t_search"])) {echo htmlspecialchars($_POST["t_search"]);}?>">
					<button style="width:25%;border:1px solid #bbb;padding:4px" type="submit" class="btn btn-danger" name="b_search">
						<i class="fa fa-search text-white"></i>
					</button>		
				</div>
				</form>	
			</div>	
			<div class="col-lg-3">
				<div class="btn-group" style="width:100%;box-shadow: rgba(0, 0, 0, 0.16) 0px 3px 6px, rgba(0, 0, 0, 0.23) 0px 3px 6px">
					<?php
						if($_SESSION["type"]=="Admin"){
							echo"
							<button onclick=\"jump('base_station_add.php')\" style='width:75%;border:1px solid #bbb;padding:4px' class='btn btn-light'>
								+ADD Base Station
							</button>
							<button onclick=\"jump('base_station_add.php')\" style='width:25%;border:1px solid #bbb;padding:4px' class='btn btn-danger'>
								<i class='fa fa-tower-cell text-white'></i>
							</button>";
						}else{
							echo"
							<button onclick=\"jump('sites_list.php')\" style='width:75%;border:1px solid #bbb;padding:4px' class='btn btn-light'>
								Installation List
							</button>
							<button onclick=\"jump('sites_list.php')\" style='width:25%;border:1px solid #bbb;padding:4px' class='btn btn-danger'>
								<i class='fa fa-wifi text-white'></i>
							</button>
							";
						}
					?>	
				</div>
			</div>			
			<div class="col-lg-3">
				<div class="btn-group" style="width:100%;box-shadow: rgba(0, 0, 0, 0.16) 0px 3px 6px, rgba(0, 0, 0, 0.23) 0px 3px 6px">
					<?php
						if($_SESSION["type"]=="Admin"){
							echo"
							<button onclick=\"jump('addRollout.php')\" style='width:75%;border:1px solid #bbb;padding:4px' class='btn btn-light'>
								+ADD Installation
							</button>
							<button onclick=\"jump('addRollout.php')\" style='width:25%;border:1px solid #bbb;padding:4px' class='btn btn-danger'>
								<i class='fa fa-wifi text-white'></i>
							</button>";
						}else{
							echo"
							<button style='width:75%;border:1px solid #bbb;padding:4px' class='btn btn-light'>
								<a style='color:#000' rel='facebox' href='reportModal2.php'>Summary Report</a>
							</button>
							<button style='width:25%;border:1px solid #bbb;padding:4px' class='btn btn-danger'>
								<a rel='facebox' href='reportModal2.php'><i class='fas fa-edit text-white'></i></a>
							</button>
							";
						}
					?>						
				</div>
			</div>
			<div class="col-lg-3">
				<div class="btn-group" style="width:100%;box-shadow: rgba(0, 0, 0, 0.16) 0px 3px 6px, rgba(0, 0, 0, 0.23) 0px 3px 6px">
					<?php
						if($_SESSION["type"]=="Admin"){
							echo"
							<button onclick=\"jump('backup.php')\" style='width:75%;border:1px solid #bbb;padding:4px' class='btn btn-light'>
								Initialize Backup
							</button>
							<button onclick=\"jump('backup.php')\" style='width:25%;border:1px solid #bbb;padding:4px' class='btn btn-danger'>
								<i class='fa fa-database text-white'></i>
							</button>";
						}else{
							echo"
							<button onclick=\"jump('installers.php')\" style='width:75%;border:1px solid #bbb;padding:4px' class='btn btn-light'>
								View Technicians
							</button>
							<button onclick=\"jump('installers.php')\" style='width:25%;border:1px solid #bbb;padding:4px' class='btn btn-danger'>
								<i class='fa fa-cog text-white'></i>
							</button>
							";
						}
					?>						
				</div>
			</div>				
		</div>
	</div>
</header>

<script>
	function sessionEnd(){	
		if(confirm("Are you sure you want to Logout?")){
			window.location.href = 'logout.php';
		}
	}
</script>	