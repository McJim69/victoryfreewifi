<?php 
	error_reporting(0);
	require("connect.php");
	require("header.php");

	$value = isset($_GET['value']) ? $_GET['value'] : "";
				
	$mun = "";
	if (isset($_GET["municipality"]) && $_GET["municipality"] != "Municipality" && $_GET["municipality"] != "") {
		$mun = " and mcode='" . $_GET["municipality"] . "'";
	}

	$bar = "";
	if (isset($_GET["barangays"]) && $_GET["barangays"] != "Barangays" && $_GET["barangays"] != "") {
		$bar = " and barangay='" . $_GET["barangays"] . "'";
	}
		
	$site = "";
	if (isset($_GET["places"]) && $_GET["places"] != "Places" && $_GET["places"] != "") {
		$bar = " and place='" . $_GET["places"] . "'";
	}

	if (isset($_POST["b_search"], $_POST["t_search"])) {
		$value = $_POST["t_search"];
	}

	$rec = 200;
	$p = isset($_GET['page']) ? $_GET['page'] : 1;

	if ($p > 1) {
		$to = $rec;
		$from = ($p * $rec) - $rec;
		$i = (($p - 1) * $rec) + 1;
	} else {
		$to = $rec;
		$from = 0;
		$i = 1;
		$p = 1;
	}

	if (isset($_POST["t_search"])) {
		$value = $_POST["t_search"];
	}
					
	$ex=$link->query("select * from sites l where 
	   (l.sid like'%".$value."%' or
		l.mcode like'%".$value."%' or
		l.barangay like'%".$value."%' or
		l.place like'%".$value."%' or		
		l.coordinates like'%".$value."%' or		
		l.inst_date like'%".$value."%' or
		l.installer like'%".$value."%' or
		l.cont_person like'%".$value."%') $mun $bar $site order by inst_date DESC LIMIT $from,$to");	

	$ex1=$link->query("select * from sites l where 
	   (l.sid like'%".$value."%' or
		l.mcode like'%".$value."%' or
		l.barangay like'%".$value."%' or
		l.place like'%".$value."%' or		
		l.coordinates like'%".$value."%' or		
		l.inst_date like'%".$value."%' or
		l.installer like'%".$value."%' or
		l.cont_person like'%".$value."%') $mun $bar $site order by inst_date DESC");
	//
?>	

<body>


<?php 
	$file = basename(__FILE__);
	
	if ($file=="sites.php"){
		$view="List View";
		$goto="sites_list.php";
	} else {
		$view="Card View";
		$goto="sites.php";
	}
	
	require("menunav1.php"); 
	require("search_nav1.php"); 
?>

<script>setActive("sites");</script>

<header class="fixed-top header-inner-pages" style="margin-top:110px">
	<div class="container d-flex align-items-center justify-content-between" style="margin-bottom:-17px">
		<table class="table bg-secondary text-light">	
			<thead>
				<tr>
					<th width='4%' style='text-align:center' scope='col'>#</th>
					<th width='25%' scope='col'>Site Name</th>
					<th width='14%' scope='col'>Placement</th>
					<th width='13%' scope='col'>Coordinates</th>
					<th width='15%' scope='col'>Linked AP</th>
					<th width='8%'  scope='col'>Roll Date</th>
					<?php 
						if(isset($_SESSION['user'])){ 
							echo"<th width='12%' scope='col'>IP Address</th>";
							echo"<th width='8%'  scope='col'>Action</th>";
						}else{
							echo"<th width='8%'  scope='col'>Status</th>";
						}
					?>
				</tr>
			</thead>
		</table>
	</div>
</header>

<main id="main" style="margin-top:110px;min-height:610px">

<?php  if ($ex->num_rows > 0) { ?>

	<div class="container d-flex align-items-center justify-content-between">
		<table class="table bg-secondary text-light">	
			<thead>
				<tr>
					<th width='4%' style='text-align:center' scope='col'>#</th>
					<th width='25%' scope='col'>Site Name</th>
					<th width='14%' scope='col'>Placement</th>
					<th width='13%' scope='col'>Coordinates</th>
					<th width='15%' scope='col'>Linked AP</th>
					<th width='8%'  scope='col'>Roll Date</th>
					<?php 
						if(isset($_SESSION['user'])){ 
							echo"<th width='12%' scope='col'>IP Address</th>";
							echo"<th width='8%'  scope='col'>Action</th>";
						}else{
							echo"<th width='8%'  scope='col'>Status</th>";
						}
					?>
				</tr>
			</thead>
			<?php

				$valm="";
				$valb="";
				$valp="";
				$repm="";
				$repb="";
				$repp="";
				
				if (isset($_POST["t_search"])) {
					$value = $_POST["t_search"];
							
						$valm=ucwords(strtoupper($value));
						$valb=ucwords(strtolower($value));
						$valp=ucwords(strtoupper($value));

					if ($value == $_POST["t_search"]) {
						$repm="<b style='color:#0014d0;background:#ffa0a0'>$valm</b>";
						$repb="<b style='color:#0014d0;background:#ffa0a0'>$valb</b>";
						$repp="<b style='color:#0014d0;background:#ffa0a0'>$valp</b>";
					}
				}		
									
				while($rs=mysqli_fetch_array($ex)){

				//Sites Status
				if($rs["status"]==1){
					$stats="<img style='margin-top:-2px;height:10px;width:10px' src='assets/img/green-circle.png'>";
					$statx="<b style='color:green'>Active</b>";
					$statc="color:green";
				}else{
					$stats="<img style='margin-top:-2px;height:10px;width:10px' src='assets/img/red-circle.png'>";
					$statx="<b style='color:red'>Down!</b>";
					$statc="color:red";
				} 									

				//Remove Site
				if(isset($_POST["b_remove_$rs[0]"])){
					$link->query("delete from sites where sid='$rs[0]'");
					jump("");
				}
					
				$cont = $rs[0];
				$roll = sprintf("%04d", $cont);

				$cont = $rs["cont_person"];
				$cont = ucwords(strtolower($cont));

				$rema = $rs["remarks"];
				$rema = ucwords(strtolower($rema));

				$ex2=$link->query("select * from placement p where p.pcode='".$rs["place"]."'");
				$rs2=mysqli_fetch_array($ex2);		
				$place=$rs2["pname"];
				
				$ex3=$link->query("select * from municipality m where m.mcode='".$rs["mcode"]."'");
				$rs3=mysqli_fetch_array($ex3);		
				$ctm=$rs3["mname"];

				$cls="style='border-bottom:1px solid #bbb;height:20px;padding:4px' onclick=\"jump('site_details.php?sites=$rs[0]')\"";
				$cln="style='$statc;border-bottom:1px solid #bbb;height:20px;padding:4px' onclick=\"jump('site_details.php?sites=$rs[0]')\"";
				$ipa="style='border-bottom:1px solid #bbb;height:20px;padding:4px'";
				
				echo"
					<tbody style='border:1px solid #bbb'>";
						if($i%2==0) echo"<tr class='odd' id='tr_".$rs[0]."' >"; else echo"<tr class='even' id='tr_".$rs[0]."' >";
						echo"
							<td style='border-bottom:1px solid #bbb;height:20px;padding:4px;text-align:center' scope='row'><b>$i.</b></td>
							<td $cln><b>
								".str_replace($valm,$repm,$rs["mcode"])." 
								".str_replace($valb,$repb,$rs["barangay"])." 
								".str_replace($valp,$repp,$rs["place"])."</b>
							</td>
							<td $cls>$place</td>
							<td $cls>".$rs["coordinates"]."</td>
							<td $cls>".$rs["link_ap_bst"]."</td>
							<td $cls>".$rs["inst_date"]."</td>";
							
							if(isset($_SESSION['user'])){ 
							echo"<td $ipa><a href='https://".$rs["ip_address"]."' target='_blank'>".$rs["ip_address"]."</a></td>";
							echo"<td style='border-bottom:1px solid #bbb;height:20px;padding:5px'>
									<a rel='facebox' href='site_edit.php?sites=$rs[0]' title='Edit'><img src='assets/img/_edit.png' style='height:17px;padding:0'/></a>&nbsp;&nbsp;
									<input onclick=\"site_delete('$rs[0]');\" type=image src='assets/img/_delete.png' style='margin-bottom:-8px;height:23px;padding:0' title='Delete' />
								</td>";
							}else{
								echo"<td $cls>$stats $statx</td>";
							}
						echo"</tr>
					</tbody>";		
					$i++;
				}					
			?>
		</table>
	</div>
	<?php
		} else {
			echo"
			<div class='row justify-content-center'>
				<div style='margin-top:140px;color:red;text-align:center'>
				<h3>Searching<b class='blinking'>... $value </b></h3>
				<div><img src='assets/img/no_records.jpg' style='border-radius:10px;width:400px'></div>
				<h3><br>No records found!</h3>
			</div>";
		}
	?>	
</main>

<?php require("footer.php");?>

</body>

</html>

<script>
	function site_delete(sid){	
		if(confirm("Are you sure you want to Remove this Site?")){
			xmlhttp.onreadystatechange=function()
			{
				if (xmlhttp.readyState==4 && xmlhttp.status==200){
					if(xmlhttp.responseText=="Success"){
						$("#tr_"+sid).animate({
					opacity:0
					},500);
				}else{
					alert(xmlhttp.responseText);
					}
					$("#tr_"+sid).animate({
					opacity:0
					},500);
				}
			}					
			xmlhttp.open("GET","delete_site.php?sid="+sid,true);
			xmlhttp.send();
		}
	}

</script>

<script>	
	if ( window.history.replaceState ) {
	  window.history.replaceState( null, null, window.location.href );
	}
</script>