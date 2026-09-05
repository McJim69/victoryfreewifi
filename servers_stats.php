<?php
	require("connect.php");	
	require("header.php");
?>

<body>

<?php require("menunav.php"); ?>

<script>setActive("admin");</script>
<script>setActive("servers");</script>

<style>
.loader {
  height: 4px;
  width: 100%;
  --c:no-repeat linear-gradient(#6100ee 0 0);
  background: var(--c),var(--c),#d7b8fc;
  background-size: 60% 100%;
  animation: l16 3s infinite;
}
@keyframes l16 {
  0%   {background-position:-150% 0,-150% 0}
  66%  {background-position: 250% 0,-150% 0}
  100% {background-position: 250% 0, 250% 0}
}
</style>

<main id="main">
<form action="servers_topology.php" method="post" enctype="multipart/form-data">	
	<section id="breadcrumbs" class="breadcrumbs" >
		<div class="container" style="margin-bottom:-15px">
			<ol>
				<li><a href="index.php">Home</a></li>
				<li><a href="admin.php">Admin</a></li> 
				<li><a href="servers_stats.php">Servers</a></li>
				<li><a href="servers_stats.php">Status</a></li>
			</ol>
			<h2>Servers &nbsp;
				<input style="border:1px solid #bbb" class="btn btn-sm btn-danger" type="button" value="View Topology" onclick="jump('servers_topology2.php')">
			</h2>
		</div>
	</section>
</form>

<section>
	<div class="container">
		<div class="row justify-content-center" style="margin-top:-40px">
			<?php			
				$i=1;
				$ex = $link->query("select * from servers order by host_id");
				while($rs = mysqli_fetch_array($ex)){
	
				$hsid=$rs[0];
				$name=$rs["server"];
				$host=$rs["localhost"];
				$port=$rs["port"];
				$role=$rs["purpose"];

				$prot = $rs["protocol"];
				$lans = $rs["localhost"];
				$webs = $rs["cloudhost"];	
				$subs = $rs["subfolder"];
				
				if($fp=fsockopen($host,$port,$errCode,$errStr,1)){				
					$stats = "Online!";					
					$color = "class='text-success'";
				}else{
					$stats = "Offline!";
					$color = "class='text-danger'";
				} 
				fclose($fp);

				$wan="180.193.203.18";
				$lan="10.0.10.1";

				if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
					$ip = $_SERVER['HTTP_CLIENT_IP'];
				} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
					$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
				} else {
					$ip = $_SERVER['REMOTE_ADDR'];
				}

				echo"
					<div style='border:1px solid #bbb;background:#eee' class='deployed-box align-items-center mobile col-lg-3'>									
						<div class='deployed-content' style='padding-left:5px'>";
						
						if (($ip)!==$wan || ($ip)!==$lan){
							echo"<a href='$prot$lans:$port$subs' target='_blank'>";
						}else{
							echo"<a href='$prot$webs:$port$subs' target='_blank'>";
						}
					
						echo"		
							<div>
								<img src='assets/img/servers/$hsid.png?".date("h:i:s")."' style='float:right;width:80px;margin-right:-10px;margin-top:0;opacity:.6' alt='Image'/>
							</div>
							<div><h6>$i. <b $color>$name</b></h6></div>
							<div class='dep-cont-tit'><small class='dep-cont-tent'>$host</small></div>
							<div class='dep-cont-tit'><small class='dep-cont-tent'>$role</small></div>
							<div class='dep-cont-tit'>Status: <b $color>"; echo $stats; echo"</b>";
							echo"
							</div>
							<div class='loader'></div>
						  </a>
						</div>		
					</div>";
					$i++;			
				}
			?>
		</div>
	</div>
</section>

<?php require("footer.php");?>

</body>

</html>
