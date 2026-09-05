<?php 
	require("connect.php");
	require("header.php");	

	$rec = 1;
	$p = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;

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
							
	$site="";
	if($_GET["sites"]!="")
		$site=" and sid='".$_GET["sites"]."' ";
												
	$ex = $link->query("select * from sites where sid=sid $site order by sid limit $from,$to ");
	  
	while($rs = mysqli_fetch_array($ex)){	
	$ex = $link->query("select * from sites where sites.sid='$rs[0]' and sites.sid=sites.sid ");
	
	while($rs = mysqli_fetch_array($ex)){	
	
	$cont = $rs[0];
	$roll = sprintf("%04d", $cont);

	$ex2=$link->query("select * from placement p where p.pcode='".$rs["place"]."'");
	$rs2=mysqli_fetch_array($ex2);		
	$place=$rs2["pname"];
		
	$ex3=$link->query("select mname from municipality m where m.mcode='".$rs["mcode"]."'");
	$rs3=mysqli_fetch_array($ex3);		
	$ctm=$rs3[0];
	
	$ex4=$link->query("select * from installer i where i.leader='".$rs["installer"]."'");
	$rs4=mysqli_fetch_array($ex4);		
	$pon=$rs4["phone"];
?>

<body>

<?php require("menunav.php");?>

<script>setActive("sites");</script>

<script>
	function printF(){		
		getID('topbar').style.display='none';
		getID('header').style.display='none';
		getID('footer').style.display='none';
		getID('print-header').style.display='none';
		$(".back-to-top").css("display","none");

	window.print();
		getID('topbar').style.display='block';
		getID('header').style.display='block';
		getID('footer').style.display='block';
		getID('print-header').style.display='block';
		$(".back-to-top").css("display","block");
	}
</script>

<section style="background:#535353"><center>
	<div id="print-header" class="content-wrapper text-light" style="font-size:22px;background:#eee;border:1px solid #bbb;border-radius:5px;width:780px;margin-top:70px">
		<div style="color:#0d5697">Installation Certificate &nbsp; &nbsp;
			<i class="fa fa-print" title="Print" onclick="printF()" style="cursor:pointer"></i> &nbsp; &nbsp;
			<i class="fa fa-close" title="Close" onclick="jump('javascript:history.back()')" style="cursor:pointer"></i>
		</div>
	</div>
	<div class="box cert content-wrapper">
		<img src="assets/img/certificate.png?<?php echo date("h:i:s");?>"/>

		<?php
			echo"		
				<div style='text-align:center;width:240px;position:absolute;left:315px;top:190px;border:1px solid #bbb;border-radius:5px'>
					<small>Rollout ID: <b class='text-danger'>"; $cont = $rs[0]; printf("%04d", $cont); echo"</b> &bull; Date: <b class='text-success'>".$rs["inst_date"]."</b></small>
				</div>
				<small>
				<div style='text-align:left;width:620px;position:absolute;left:125px;top:230px'>
					Mun: <b class='text-success'>$ctm</b> &nbsp;
					Brgy: <b class='text-success'>".$rs["barangay"]."</b> &nbsp;
					Site: <b class='text-success'>".$place."</b><br>
					Coordinates: <b class='text-success'>".$rs["coordinates"]."</b> &nbsp; 
					Linked BST: <b class='text-success'>".$rs["link_ap_bst"]."</b> &nbsp;
					
				</div>
				</small>
				<div style='text-transform:uppercase'><small>
					<div style='width:265px;text-align:center;position:absolute;left:185px;top:685px'>
						<b class='text-success'>".$rs["installer"]."</b>
					</div>
					<div style='width:225px;text-align:center;position:absolute;right:25px;top:685px'>
						<b class='text-success'>".$rs4["phone"]."</b>
					</div>
					<div style='width:265px;text-align:center;position:absolute;left:185px;top:722px'>
						<b class='text-success'>".$rs["cont_person"]."</b>
					</div>
					<div style='width:225px;text-align:center;position:absolute;right:25px;top:722px'>
						<b class='text-success'>".$rs["cont_number"]."</b>
					</div>
					<div style='width:565px;text-align:center;position:absolute;left:190px;top:760px'>
						<b class='text-success'>".$rs["remarks"]."</b>
					</div></small>
				</div>";
			
				$exd=$link->query("SELECT * FROM sites_detail WHERE site_id='".$rs[0]."' ");
				if ($exd->num_rows > 0) {
			?>	
				<div style='width:620px;position:absolute;left:125px;top:320px;'>
					<table style='width:100%'>
						<thead style="border:1px solid #bbb">
							<tr>
								<th class='certth' style='text-align:center'><small>NO</small></th>
								<th class='certth'><small>CODE</small></th>
								<th class='certth'><small>NAME</small></th>
								<th class='certth'><small>QTY</small></th>
								<th class='certth'><small>UNIT</small></th>
								<th class='certth'><small>MAC/SN</small></th>
								<th class='certth'><small>INST DATE</small></th>
							</tr>
						</thead>
					<?php
						while($rsd=mysqli_fetch_array($exd)){
						$cls="style='border-bottom:1px solid #bbb;height:20px;padding:0'";
						echo"
						<tbody style='border:1px solid #bbb'>";
							if($i%2==0) echo"<tr class='odd' id='tr_".$rsd[0]."' >"; else echo"<tr class='even' id='tr_".$rsd[0]."' >";
							echo"
								<td style='border-bottom:1px solid #bbb;height:20px;padding:0;text-align:center'><small>$i</small></td>
								<td $cls><small>".$rsd["device_code"]."</small></td>
								<td $cls><small>".$rsd["device_name"]."</small></td>
								<td $cls><small>".$rsd["device_qty"]."</small></td>
								<td $cls><small>".$rsd["device_unit"]."</small></td>
								<td $cls><small>".$rsd["serial_mac"]."</small></td>
								<td $cls><small>".$rsd["inst_date"]."</small></td>
							</tr>
						</tbody>";
						$i++;
					}
				} 
			?>
			</table>
		</div>
	</div>
</section>

<?php } } ?>

<?php require("footer.php");?>

</body>

</html>
