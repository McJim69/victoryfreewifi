<?php 
	require("connect.php");
	require("header.php");
    
	function fill_team($pdo){
		$output= '';
		$select = $pdo->prepare("SELECT * FROM installer order by leader");
		$select->execute();
		$result = $select->fetchAll();

		foreach($result as $row){
			$cont = $row["leader"];
			$cont = ucwords(strtolower($cont));
			
			$output.='<option value="'.$cont.'">'.$cont.'</option>';
		} 	return $output;
	}
	
	$lead="";
	
	if(isset($_POST["search"])){
		
		if($_POST["lead"]!==""){
			$lead=$_POST["lead"];
			$inst="and installer='".$_POST["lead"]."'";
			$repr="and repair_team='".$_POST["lead"]."'";
		}else{
			$lead="Installer (All)";
			$inst="and installer=installer";
			$repr="and repair_team=repair_team";
		}

		if($_POST["from"]!==""){
			$dafr=$_POST["from"];
		}else{
			$datQ=$link->query("SELECT inst_date AS date FROM sites WHERE inst_date is not NULL ORDER BY inst_date LIMIT 1");
			$datR=mysqli_fetch_array($datQ);
			$dafr=$datR["date"];
		}
		
		if($_POST["dato"]!==""){
			$dato=$_POST["dato"];
		}else{
			$dato=date("Y-m-d");
		}
			   
		$exi=$link->query("select * from sites where inst_date between '$dafr' and '$dato' $inst order by mcode") or die(mysqli_error($link));		
		$exr=$link->query("select * from sites where repair_date between '$dafr' and '$dato' $repr order by mcode") or die(mysqli_error($link));		
	}	
	$link->query("UPDATE sites set inst_date='2022-06-20' WHERE inst_date=null ");
?>

<body>

<?php require("menunav.php"); ?>

<script>setActive("admin");</script>
<script>setActive("rollout");</script>

<form action="#" method="post" enctype="multipart/form-data">

<main id="main">
	<section id="breadcrumbs" class="breadcrumbs" >
		<div class="container" style="margin-bottom:-10px">
			<ol>
				<li><a href="index.php">Home</a></li>
				<li><a href="admin.php">Admin</a></li>
				<li>Rollout</li>
			</ol>
			<h2>Rollout || Accomplishment</h2>
		</div>
	</section>	
	<section style="min-height:495px">
		<div class="container" style="margin-top:-45px;margin-bottom:15px">	
			<input  class="btn btn-sm btn-light" style="width:150px;margin-top:5px;border:1px solid #bbb" onfocus="(this. type='date')" placeholder="Date from" name="from">
			<input  class="btn btn-sm btn-light" style="width:150px;margin-top:5px;border:1px solid #bbb" onfocus="(this. type='date')" placeholder="Date to" name="dato">
			<select class="btn btn-sm btn-light" style="width:150px;margin-top:5px;border:1px solid #bbb;text-align:left" name="lead">
				<option value=""><?php if($lead=="") echo"All Teams"; else echo $lead;?></option>
				<?php echo fill_team($pdo);?>
			</select>
			<input  class="btn btn-sm btn-primary" style="width:90px;margin-top:5px;border:1px solid #bbb" type="submit" name="search" onchange="this.form.submit()" value="Search">
			<input  class="btn btn-sm btn-danger"  style="width:90px;margin-top:5px;border:1px solid #bbb" type="submit" name="search" onclick="jump('accomplishment.php')" value="Reset">
			<input  class="btn btn-sm btn-success" style="width:90px;margin-top:5px;border:1px solid #bbb" type="button" onclick="jump('addRollout.php')" value="Add Rollout">
		</div>
		<div class="container" style="margin-bottom:-50px">			
			<?php	
			  $i=1;

				if(isset($_POST["search"])){
					
				$date1=date_create($dafr);
				$date2=date_create($dato);

				$inst=number_format(mysqli_num_rows($exi),0);
				$repr=number_format(mysqli_num_rows($exr),0);
				$total=$inst+$repr;
					
				echo"
				<div style='padding:10px; margin-bottom:15px;background:#eee;border:1px solid #bbb;border-radius:5px'>	
					<div class='row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-6 row-cols-xl-6'>
						<div class='col'>&nbsp;<small>Team Leader:</small> 
							<div class='btn-sm form-control text-center text-primary text-uppercase'>
								<b>$lead</b>
							</div>
						</div>
						<div class='col'>&nbsp;<small>From Date:</small>  
							<div class='btn-sm form-control text-center text-primary'>
								<b>".date_format($date1,"M d, Y")."</b>
							</div>
						</div>
						<div class='col'>&nbsp;<small>To Date:</small>  
							<div class='btn-sm form-control text-center text-primary'>
								<b>".date_format($date2,"M d, Y")."</b>
							</div>
						</div>
						<div class='col'>&nbsp;<small>Installation:</small>  
							<div class='btn-sm form-control text-center text-primary'>
								<b>$inst</b>
							</div>
						</div>
						<div class='col'>&nbsp;<small>Maintenance:</small>  
							<div class='btn-sm form-control text-center text-primary'>
								<b>$repr</b>
							</div>
						</div>
						<div class='col'>&nbsp;<small>Total:</small> 
							<div class='btn-sm form-control text-center text-primary'>
								<b>$total</b>
							</div>
						</div>
					</div>
				</div>";
					
				if ($exi->num_rows > 0 || $exr->num_rows > 0) {	
				echo"	
				<div style='overflow-x:auto'>
					<table class='table table-responsive bg-secondary text-light'>
						<thead>
							<tr>
								<th width='2%' scope='col' style='text-align:center'><small>#</small></th>
								<th scope='col'><small>Site Name</small></th>
								<th scope='col'><small>Team</small></th>
								<th scope='col'><small>Job</small></th>
								<th scope='col'><small>Date</small></th>
							</tr>
						</thead>";

						while($rsi=mysqli_fetch_array($exi)){	

						$cls="style='border-bottom:1px solid #bbb;height:20px;padding:5px' onclick=\"jump('site_details.php?sites=$rsi[0]')\" ";

						echo"
						<tbody style='border:1px solid #bbb;font-size:15px;margin-bottom:-40px'>";
						if($i%2==0) echo"<tr class='odd' id='tr_".$rsi[0]."' >"; else echo"<tr class='even' id='tr_".$rsi[0]."' >";
							echo"
								<td scope='row' style='text-align:center;border-bottom:1px solid #bbb;height:20px;padding:5px'><small><b>$i.</b></small></td>
								<td $cls><small>
									".$rsi["mcode"]." 
									".$rsi["barangay"]." 
									".$rsi["place"]."</small>
								</td>
								<td $cls><small>";
									$string=$rsi["installer"];
									$split = explode(" ", $string);
									echo $split[count($split)-1];
								echo"</small>
								</td>
								<td $cls><small style='color:green'>INS</small></td>
								<td $cls><small>".$rsi["inst_date"]."</small></td>
							</tr>
						</tbody>";

						$i++; 
						
						} 
						
						while($rsr=mysqli_fetch_array($exr)){
												
						$cls='';
						if(!empty($rsr[0])){
							$cls="style='border-bottom:1px solid #bbb;height:20px;padding:5px' onclick=\"jump('site_details.php?sites={$rsr[0]}')\"";
						}

						echo"
						<tbody style='border:1px solid #bbb;font-size:15px'>";
						if($i%2==0) echo"<tr class='odd' id='tr_".$rsr[0]."' >"; else echo"<tr class='even' id='tr_".$rsr[0]."' >";
							echo"
								<td scope='row' style='text-align:center;border-bottom:1px solid #bbb;height:20px;padding:5px'><small><b>$i.</b></small></td>
								<td $cls><small>
									".$rsr["mcode"]." 
									".$rsr["barangay"]." 
									".$rsr["place"]."</small>
								</td>
								<td $cls><small>";
									$string=$rsr["repair_team"];
									$split = explode(" ", $string);
									echo $split[count($split)-1];
								echo"</small>
								</td>
								<td $cls><small style='color:red'>REP</small></td>
								<td $cls><small>".$rsr["repair_date"]."</small></td>
							</tr>
						</tbody>";
						$i++; 
						}
					
					}else{
						echo"<br><b style='color:red'>No records found!</b>";
					}
					
					echo"</table>";
				
					}else{

						if(isset($_POST['year'])){ 
							$post = $_POST['year'];
							$year = "inst_date BETWEEN '$post-01-01' and '$post-12-31'";
						}else{	
							$post = date("Y");
							$year = "inst_date BETWEEN '".date("Y")."-01-01' and '".date("Y")."-12-31'";
						}
				
						$rsq = $link->query("SELECT DATE_FORMAT(inst_date, '%Y') AS year FROM sites GROUP BY YEAR(inst_date) ORDER BY inst_date DESC");

						echo"
						<div style='background:#bbb;border:1px solid #545454;padding:10px;border-radius:5px'>
							<select class='btn btn-sm btn-light' style='background:#eee;text-align:left;border:1px solid #bbb' name='year' type='submit' onchange='this.form.submit()'>	
								<option value=''>Year</option>";
								while($rsy = $rsq->fetch_assoc()){
								echo"<option value='".$rsy["year"]."'>".$rsy["year"]."</option>";
								}
							echo"
							</select> &nbsp; ";

							$month = $link->query("SELECT DATE_FORMAT(inst_date, '%M') AS month, DATE_FORMAT(inst_date, '%Y') AS year, COUNT(sid) AS count FROM sites WHERE $year GROUP BY MONTH(inst_date), YEAR(inst_date)");

							$qSite = $link->query("SELECT COUNT(sid) FROM sites WHERE $year") or die(mysqli_error($link));
							$aSite = mysqli_fetch_array($qSite);	
							$tSite = number_format($aSite[0]);

							if($month){							

							echo"<b>$post - ALL TEAMS - INSTALLATIONS</b> 
								<table class='table table-responsive bg-secondary text-light'>
									<thead style='text-align:center'>
										<tr>
											<th>#</th>
											<th>Team</th>
											<th>Year</th>
											<th>Month</th>
											<th>Total</th>
										</tr>
									</thead>
									
									<tbody style='border:1px solid #bbb;font-size:15px'>";
									
										while($row = $month->fetch_assoc()) {
										if($i%2==0) echo"<tr class='odd'>"; else echo"<tr class='even'>";
										$cls="style='text-align:center;height:20px;padding:4px;font-size:15px'";
											echo"
											<td $cls>$i.</td>
											<td $cls>All Teams</td>
											<td $cls>".$row['year']."</td>
											<td $cls>".$row['month']."</td>
											<td $cls>".$row['count']."</td>
										</tr>";
										$i++;
										}
										$cls='';
										if(!empty($rsr[0])){
											$cls="style='border-bottom:1px solid #bbb;height:20px;padding:5px' onclick=\"jump('site_details.php?sites={$rsr[0]}')\"";
										}
										echo"
										<td $cls></td>
										<td $cls></td>
										<td $cls></td>
										<td $cls></td>
										<td $cls><b>$tSite</b></td>
									</tbody>
								</table>
							</div>";
						}				
					}
				?>
			</div>			
		</div>
	</section>
</main>

</form>			

<?php require("footer.php");?>

</body>

</html>
