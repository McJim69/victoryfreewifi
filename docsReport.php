<style>
	.report{
		font-size:14px;
		border-bottom:1px solid #bbb;
		height:18px;
		padding:0;
	}
	
	.sprint{
		color:#000;
		font-size:14px;
		border-bottom:1px solid #bbb;
		height:18px;
		padding:0;
	}
</style>

<section id="portfolio-details" class="portfolio-details">
	<div class="container">
		<div class="portfolio-details-container">

			<?php echo"<h3 class='report-header'>$post &nbsp; <a onclick=\"printF()\" style='cursor:pointer'><img src='assets/img/print1.png' height='30px'></a></h3>";?>

			<table class="table bg-secondary">
				<thead>
					<th class="report text-center" width="10">#</th>
					<th class="report">MUNICIPALITY</th>
					<th class="report text-center">BAR</th>
					<th class="report text-center">WFI</th>
					<th class="report text-center">CEN</th>
					<th class="report text-center">SCH</th>
					<th class="report text-center">OTH</th>
					<th class="report text-center">TOT</th>
				</thead>
				<tbody style='border:1px solid #bbb'>
				<?php
					$i=1;
					$qrym = $link->query("select * from municipality order by mname ") or die(mysqli_error($link));
					while($rsm = mysqli_fetch_array($qrym)){
						$mname = $rsm["mname"];
						$mcode = $rsm["mcode"];
						
					require("sites_count.php");
					
					$cls = "style='text-align:center;font-size:14px;border-bottom:1px solid #bbb;height:18px;padding:2px;'";
			
					if($i%2==0) 
						echo"<tr class='odd' id='tr_".$rsm[0]."'  onclick=\"jump('sites.php?municipality=$mcode')\">"; 
					else 
						echo"<tr class='even' id='tr_".$rsm[0]."' onclick=\"jump('sites.php?municipality=$mcode')\">";
					
					echo"
						<td $cls width='10'>$i.</td>
						<td style='text-transform:uppercase;font-size:14px;border-bottom:1px solid #bbb;height:18px;padding:2px;'>$mname</td>
						<td $cls>$totBar</td>
						<td $cls>$totWIN</td>
						<td $cls>$totBPM</td>
						<td $cls>$totSPM</td>
						<td $cls>$totOPM</td>
						<td $cls>$totwFi</td>
					</tr>";
					$i++;
					}
				?>
				</tbody>
				<tfoot style="font-weight:bold">
					<tr <?php echo $cls;?> class="text-light">
						<td <?php echo $cls;?>></td>
						<td <?php echo $cls;?>>TOTALS</td>
						<td <?php echo $cls;?>><?php echo $allBAR;?></td>
						<td <?php echo $cls;?>><?php echo $totBAR;?></td>
						<td <?php echo $cls;?>><?php echo $totBMH;?></td>
						<td <?php echo $cls;?>><?php echo $totSCH;?></td>
						<td <?php echo $cls;?>><?php echo $totOTH;?></td>
						<td <?php echo $cls;?>><?php echo $totALL;?></td>
					</tr>
				</tfoot>
			</table>
			<div class="hid" style="position:relative;padding:10px;border:1px solid #bbb">
				<table width="100%">
					<td width="70%">
						<div><b>BAR</b> = Total Number of Barangays    </div>
						<div><b>WFI</b> = Barangays with Installations </div>
						<div><b>CEN</b> = Insts on Brgys/Muns Center   </div>
						<div><b>SCH</b> = Insts on Schools (All level) </div>
						<div style="border-bottom:1px solid #bbb"><b>OTH</b> = Insts on Other Public Places </div>
						<div style="border-bottom:2px solid #bbb"><b>TOT</b> = Total FreeWiFi Installations </div>
					</td>
					<td width="30%" style="text-align:right">
						<div><b><?php echo $allBAR;?></b></div>
						<div><b><?php echo $totBAR;?></b></div>
						<div><b><?php echo $totBMH;?></b></div>
						<div><b><?php echo $totSCH;?></b></div>
						<div style="border-bottom:1px solid #bbb"><b><?php echo $totOTH;?></b></div>
						<div style="border-bottom:2px solid #bbb"><b><?php echo $totALL;?></b></div>
					</td>
				</table>
			</div>
		</div>
	</div>
</section>

<div id="toprint" style="margin-top:50px;display:none">
	<div style="text-align:center">
		<table width="100%">
			<td width="20%">
				<img src="assets/img/zdslogo.png" style="height:90px;position:float-left">
			</td>
			<td width="60%">
				<small>
					Republic of the Philippines<br>
					<b>PROVINCE OF ZAMBOANGA DEL SUR</b><br>
					Communications and Information System Office<br>
					Provincial Capitol, Pagadian City<br><br>
				</small>
			</td>
			<td width="20%">
				<img src="assets/img/ciso.png" style="height:90px;position:float-right">
			</td>
		</table>
		<div>
			<img src="assets/img/logo.png" style="height:50px;padding:5px 10px 5px 10px;background:#1e67a8;border-radius:5px">
			<h5 style="margin-top:10px;margin-bottom:-5px">Installation Summary Report</h5>
			<small>As of <?php echo date("M d, Y");?></small>
		</div>
	</div><br>	

	<table class="table bg-secondary">
		<thead>
			<th class="report text-center" width="10">#</th>
			<th class="report">MUNICIPALITY</th>
			<th class="report text-center">BAR</th>
			<th class="report text-center">WFI</th>
			<th class="report text-center">CEN</th>
			<th class="report text-center">SCH</th>
			<th class="report text-center">OTH</th>
			<th class="report text-center">TOT</th>
		</thead>
		<tbody style='border:1px solid #bbb'>
		<?php
			//require("connect2.php");
			$i=1;
			$qrym = $link->query("select * from municipality order by mname ") or die(mysqli_error($link));
			while($rsm = mysqli_fetch_array($qrym)){
				$mname = $rsm["mname"];
				$mcode = $rsm["mcode"];
				
			require("sites_count.php");
			
			$cls = "style='text-align:center;font-size:14px;border-bottom:1px solid #bbb;height:18px;padding:2px;'";
	
			if($i%2==0) echo"<tr class='odd' id='tr_".$rsm[0]."' >"; else echo"<tr class='even' id='tr_".$rsm[0]."' >";
			echo"
				<td $cls width='10'>$i.</td>
				<td style='text-transform:uppercase;font-size:14px;border-bottom:1px solid #bbb;height:18px;padding:2px;'>$mname</td>
				<td $cls>$totBar</td>
				<td $cls>$totWIN</td>
				<td $cls>$totBPM</td>
				<td $cls>$totSPM</td>
				<td $cls>$totOPM</td>
				<td $cls>$totwFi</td>
			</tr>";
			$i++;
			}
		?>
		</tbody>
		<tfoot style="text-decoration:bold">
			<tr <?php echo $cls;?> class="text-light">
				<td <?php echo $cls;?>></td>
				<td <?php echo $cls;?>>TOTALS</td>
				<td <?php echo $cls;?>><?php echo $allBAR;?></td>
				<td <?php echo $cls;?>><?php echo $totBAR;?></td>
				<td <?php echo $cls;?>><?php echo $totBMH;?></td>
				<td <?php echo $cls;?>><?php echo $totSCH;?></td>
				<td <?php echo $cls;?>><?php echo $totOTH;?></td>
				<td <?php echo $cls;?>><?php echo $totALL;?></td>
			</tr>
		</tfoot>
	</table>
	<table width="100%">
		<td width="60%">
			<div>
				<table width="65%">
					<td width="45%">
						<div><b>BAR</b> = Total Number of Barangays    </div>
						<div><b>WFI</b> = Barangays with Installations </div>
						<div><b>CEN</b> = Insts on Brgys/Muns Center   </div>
						<div><b>SCH</b> = Insts on Schools (All level) </div>
						<div style="border-bottom:1px solid #bbb"><b>OTH</b> = Insts on Other Public Places </div>
						<div style="border-bottom:2px solid #bbb"><b>TOT</b> = Total FreeWiFi Installations </div>
					</td>
					<td width="5%" style="text-align:right">
						<div><b><?php echo $allBAR;?></b></div>
						<div><b><?php echo $totBAR;?></b></div>
						<div><b><?php echo $totBMH;?></b></div>
						<div><b><?php echo $totSCH;?></b></div>
						<div style="border-bottom:1px solid #bbb"><b><?php echo $totOTH;?></b></div>
						<div style="border-bottom:2px solid #bbb"><b><?php echo $totALL;?></b></div>
					</td>
				</table>
			</div>
		</td>
		<td width="40%">
			<div><br>
				Certified Correct:<br><br>
				<b>JUSTIN AHMED PAOLO C. HERERRA</b><br>
				VFW Project Manager<br>
				CISO Office Head
			</div>
		</td>
	</table>
</div>
