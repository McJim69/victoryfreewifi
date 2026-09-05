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

<!-- ======= Header ======= -->
<header id="topbar" class="d-none d-lg-flex align-items-center fixed-top topbar-inner-pages">
  <div class="container d-flex align-items-center justify-content-between">
	<a href="index.php" class="logo scrollto"><img src="assets/img/logo.png" alt="VFW Logo" class="img-fluid" style="height:45px"></a> 
		<nav class="nav-menu d-none d-lg-block text-uppercase">
			<ul>
				<li><a href='index.php' id='home' class='menuli'>Home</a></li>
				<li><a href='about.php' id='about' class='menuli'>About</a></li>
				<li><a href='team.php' id='team' class='menuli'>Team</a></li>
				<li><a href='barangays.php' id='barangays' class='menuli'>Barangays</a></li>
				<li><a href='base_stations.php' id='bst' class='menuli'>Base-AP</a></li>
				<li><a href="sites_list.php" id="sites" class="menuli">Sites</a></li>
				<li><a href='documents.php' id='docs' class='menuli'>Docs</a></li>
				<li><a href='contact.php' id='contact' class='menuli'>Contact</a></li>
				<?php 
					if(isset($_SESSION['user'])){	
						echo"<li><a href='admin.php' id='admin' class='menuli'>Admin</a></li>";
						echo"<li><a href='logout.php' onclick=\"sessionEnd('usrid')\" class='menuli'>Logout</a></li>";
					}else{
						echo"<li><a rel='facebox' href='login.php' id='login' class='menuli'>Login</a></li>";
						echo"<li><a rel='facebox' href='register1.php' id='register' class='menuli'>Register</a></li>";			
					}
				?>
			</ul>
		</nav><!-- .nav-menu -->
	</div>
</header><!-- End Header -->