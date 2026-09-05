<!-- ======= Header ======= -->
<header class="fixed-top header-inner-pages" style="z-index:9999999">
  <div class="container d-flex align-items-center justify-content-between" >
	<h1 class="logo">
		<a href="index.php" class="logo scrollto">
			<img src="assets/img/logo.png?<?php date("h:i:s");?>" alt="VFW Logo" class="img-fluid" style="height:40px;margin-top:4px">
		</a>
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
</header>

<script>
	function sessionEnd(){	
		if(confirm("Are you sure you want to Logout?")){
			window.location.href = 'logout.php';
		}
	}
</script>	