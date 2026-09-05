<?php 
	require("connect.php");
	require("header.php");

	$value = $_GET["value"] ?? "";

	$mun = "";
	if (isset($_GET["municipality"]) && $_GET["municipality"] != "Municipality" && $_GET["municipality"] != "") {
		$mun = " and mcode='" . $_GET["municipality"] . "'";
	}

	$bar = "";
	if (isset($_GET["barangays"]) && $_GET["barangays"] != "Barangays" && $_GET["barangays"] != "") {
		$bar = " and barangay='" . $_GET["barangays"] . "'";
	}

	if (isset($_POST["b_search"])) {
		$value = $_POST["t_search"] ?? "";
	}   

	$rec = 1000;
	$p = isset($_GET['page']) ? (int)$_GET['page'] : 1;

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

	$ex = $link->query("SELECT * FROM barangays l WHERE 
	   (l.bid LIKE '%".$value."%' OR
		l.mcode LIKE '%".$value."%' OR
		l.barangay LIKE '%".$value."%') $mun $bar 
	   ORDER BY bid LIMIT $from,$to");
	//
?>


<body>

<?php 
	require("menunav1.php"); 
	require("search_nav2.php"); 
?>

<script>setActive("barangays");</script>

	<div style="background:#A91B0D; !important;padding:4px;margin-top:-10px">
		<div class="container d-flex align-items-center justify-content-between">
			<table class="table bg-secondary text-light" style="margin:5px 0 -4px 0">
				<thead style="border:1px solid #535353">
					<tr>
						<th width='3%' style='text-align:center'>#</th>
						<th width='12%'>Municipality</th>
						<th width='15%'>Barangay</th>
						<th width='15%'>STN Location</th>
						<th width='18%'>Potential Link</th>
						<th width='15%'>Link Location</th>
						<th width='7%'>AP Link</th>
						<th width='7%'>On WiFi</th>
					</tr>
				</thead>
			</table>
		</div>
	</div>
<main id="main" style="margin-top:78px;min-height:580px">
	<?php if ($ex->num_rows > 0) { ?>
	<div class="container d-flex align-items-center justify-content-between">
		<table class="table bg-secondary text-light">
			<thead>
				<tr>
					<th width='3%' style='text-align:center'>#</th>
					<th width='12%'>Municipality</th>
					<th width='15%'>Barangay</th>
					<th width='15%'>STN Location</th>
					<th width='18%'>Potential Link</th>
					<th width='15%'>Link Location</th>
					<th width='7%'>AP Link</th>
					<th width='7%'>On WiFi</th>
				</tr>
			</thead>
			
			<?php
				$valb='';
				$repb='';
				if (isset($_POST["t_search"])) {
					$value = $_POST["t_search"];
							
						$valm=ucwords(strtoupper($value));
						$valb=ucwords(strtolower($value));

					if ($value == $_POST["t_search"]) {
						$repm="<b style='color:#0014d0;background:#ffa0a0'>$valm</b>";
						$repb="<b style='color:#0014d0;background:#ffa0a0'>$valb</b>";
					}
				}							
									
				while($rs=mysqli_fetch_array($ex)){		
				
				if(isset($_POST["b_remove_$rs[0]"])){
					$link->query("delete from barangays where bid='$rs[0]'");
					jump("");
				}
					
				$ex3=$link->query("select mname from municipality m where m.mcode='".$rs["mcode"]."'");
				$rs3=mysqli_fetch_array($ex3);		
				$ctm=$rs3[0];
				
				$mcd=$rs["mcode"];
				$brg=$rs["barangay"];
				
				if($rs["win"]=="Yes"){
					$instalink="onclick=\"jump('sites.php?municipality=$mcd&barangays=$brg')\" title='Click to View Installation'";
				}else{
					$instalink="";
				}

				if($rs["ap_link_bst"]!==""){
					$link->query("UPDATE barangays set los='Yes' where bid='".$rs[0]."'");
				}
				
				echo"
				<tbody style='border:1px solid #535353'>";

					$cls="style='height:20px;padding:4px'";

					if($i%2==0) echo"<tr $instalink class='odd' id='tr_".$rs[0]."'>"; else echo"<tr $instalink class='even' id='tr_".$rs[0]."'>";

					echo"
						<td style='height:20px;padding:4px;text-align:center'><b>$i.</b></td>
						<td style='height:20px;padding:4px;text-transform:uppercase'>$ctm</td>
						<td $cls>".str_replace($valb,$repb,$rs["barangay"])."</td>
						<td $cls>".$rs["stn_coordinates"]."</td>
						<td $cls>".$rs["ap_link_bst"]."</td>
						<td $cls>".$rs["bst_coordinates"]."</td>
						<td $cls>";if($rs["los"]=="Yes") echo"<b style='color:green'>Yes</b>"; else echo"<b style='color:red'>No</b>"; echo"</td>
						<td $cls>";if($rs["win"]=="Yes") echo"<b style='color:green'>Yes</b>"; else echo"<b style='color:red'>No</b>"; echo"</td>
					</tr>
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
				<div style='margin-top:100px;color:red;text-align:center'>
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
