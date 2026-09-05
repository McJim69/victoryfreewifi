<?php
	require("connect.php");	
	require("header.php");
?>

<body>

<?php require("menunav.php"); ?>

<script>setActive("admin");</script>
<script>setActive("servers");</script>

<main id="main">
	<section id="breadcrumbs" class="breadcrumbs" >
		<div class="container" style="margin-bottom:-15px">
			<ol>
				<li><a href="index.php">Home</a></li>
				<li><a href="admin.php">Admin</a></li> 
				<li><a href="servers_stats.php">Servers</a></li>
				<li><a href="servers_topology2.php">Topology</a></li>
			</ol>
			<h2>Servers &nbsp;
				<a href="servers_stats.php"><button class="btn btn-sm btn-danger">View Status</button></a>
			</h2>
		</div>
	</section>

	<section style="margin-top:-50px;min-height:600px">
		<div class="container">
			<div class="row justify-content-center" style="margin-bottom:-50px">
				<div class="col-lg-12">
					<iframe src='assets/files/servers_topology.pdf' style='border-radius:5px;width:100%;min-height:700px'></iframe>
				</div>
			</div>
		</div>
	</section>
</main>

<?php require("footer.php");?>

</body>

</html>