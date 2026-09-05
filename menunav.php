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
				if(!isset($_SESSION['user'])){	
					echo"<i class='fa fa-lock text-warning'></i>
					<a style='background:transparent' rel='facebox' href='login.php'>
					<li class='blinking' style='cursor:pointer'>				
						<span class='text-light'> 
							Login for Admin Privilege
						</span>
					</li>
					</a>";	
				}else{					

				$exu=$link->query("SELECT * FROM users WHERE usrid='".$_SESSION["usid"]."' ");
				$uid=mysqli_fetch_array($exu);
				$usr=$uid[0];
				
					$uname=$_SESSION["user"];
					$uname=ucwords(strtolower($uname));

					$fname=$_SESSION["fnam"];
					$fname=ucwords(strtolower($fname));

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
					echo"<span onclick=\"jump('site_details.php?sites=$news')\" class='blinking text-light' style='cursor:pointer'>";
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
	<a href="index.php" class="logo scrollto"><img src="assets/img/logo.png?<?php date("h:i:s");?>" alt="VFW Logo" class="img-fluid" style="margin-top:4px"></a>
		<nav class="nav-menu d-none d-lg-block text-uppercase">
			<ul>
				<li><a href='index.php' id='home' class='menuli'>Home</a></li>
				<li><a href='about.php' id='about' class='menuli'>About</a></li>
				<li class='drop-down'><a href='team.php' id='team' class='menuli'>Team</a>
					<ul>
						<li><a id='office' href='team.php'>Management</a></li>
						<li><a id='installers' href='installers.php'>Field Technician</a></li>
					</ul>
				</li>
				<li><a href='barangays.php' id='barangays' class='menuli'>Barangays</a></li>
				<!--<li><a href='base_stations.php' id='bst' class='menuli'>Base STN</a></li>-->
				<li class='drop-down'><a href='sites_list.php' id='sites' class='menuli'>Sites</a>
					<ul>
						<li><a href='sites_list.php'>List View</a></li>
						<li><a href='sites.php'>Card View</a></li>
						<li><a id='stats' href='sites_status_mon.php'>Sites Status</a></li>
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
</header>

<script>
	function sessionEnd(){	
		if(confirm("Are you sure you want to Logout?")){
			window.location.href = 'logout.php';
		}
	}
</script>	