<div style="background:#fff">
	<div style="padding:10px;min-width:380px;overflow:auto;height:auto">
		<div style="border:0">
			<div id="print-header" style="display:none;text-align:center;;margin-top:10px">
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
					<img src="assets/img/vfw-logo.jpg" style="height:50px;padding:5px 10px 5px 10px;background:#1e67a8;border-radius:5px">
					<h5>Installation Summary Report</h5>
					<small>As of <?php echo date("M d, Y");?></small>
				</div>
			</div><br>	
			<div class="report-header" style="text-align:center;padding:10px;margin-top:-20px">			
				<h5 class="modal-title" id="rolloutModalLabel">
					Installation Summary Report
				</h5>
			</div>
			<div>
				<div style="width:auto;height:auto">
					<table class="table bg-secondary">
						<thead style="border:1px solid #545454">
							<th class="report text-center" width="10">#</th>
							<th class="report">MUINICIPALITY</th>
							<th class="report text-center">BAR</th>
							<th class="report text-center">WFI</th>
							<th class="report text-center">CEN</th>
							<th class="report text-center">SCH</th>
							<th class="report text-center">OTH</th>
							<th class="report text-center">TOT</th>
						</thead>
						<tbody style='border:1px solid #bbb'>
						<?php
							require("connect.php");

							$i=1;
							$qryMun=$link->query("SELECT * FROM municipality");

							while($rsm = mysqli_fetch_array($qryMun)){
								$mname = $rsm["mname"];
								$mcode = $rsm["mcode"];

							require("sites_count.php");
														
							$cls = "style='text-align:center;font-size:14px;border-bottom:1px solid #bbb;height:18px;padding:2px;'";
					
							if($i%2==0) 
								echo"<tr class='odd'  id='tr_".$rsm[0]."' onclick=\"jump('sites.php?municipality=$mcode')\">"; 
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
					<div id="print-footer" style="position:relative;display:none">
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
				</div>
			</div>
		</div>
	</div>
</div>
