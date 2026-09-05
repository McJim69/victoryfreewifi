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

	$rec = 20;
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

<style>
	.sbox{
		margin:5px 0px 5px 0px;
	}
	.sbox:hover{
		box-shadow: 0 14px 28px rgba(0,0,0,0.25), 0 10px 10px rgba(0,0,0,0.22);
	}
</style>


<?php 
	$file = basename(__FILE__);
	
	if ($file=="sites.php"){
		$view="List View";
		$goto="sites_list.php";
	} else {
		$view="Box View";
		$goto="sites.php";
	}
	
	require("menunav1.php"); 
	require("search_nav1.php"); 
?>

<script>setActive("sites");</script>

<main id="main">
    <section style="margin: 60px 15px 0 15px;">
		<div class="container">
			<div class="row justify-content-center">	
				<?php
					$val='';
					$rep='';
					
					if (isset($_POST["t_search"])) {
						$value = $_POST["t_search"];
						
						$val=ucwords(strtoupper($value));

						if ($value == $_POST["t_search"]) {
							$rep = "<b style='color:#0014d0;background:#ffa0a0'>$val</b>";
						}
					}

					if ($ex->num_rows > 0) {
						
					while($rs=mysqli_fetch_array($ex)){
					
					$hosts = $rs["ip_address"];

					if($rs["status"]==1){
						$stats="<img style='margin:3px;padding:0;height:20px;width:20px;z-index:22' src='assets/img/green-circle.png'>";
						$statc="color:darkgreen";
						$statx="Online!";
						$statb="background:green;border-radius:7px";
						$stati="font-weight:bold;color:white;background:green";
					}else{
						$stats="<img style='margin:3px;padding:0;height:20px;width:20px;z-index:22' src='assets/img/red-circle.png'>";
						$statc="color:darkred";
						$statx="Offline!";
						$statb="background:red;border-radius:7px";
						$stati="font-weight:bold;color:white;background:red";
					} 
					
					//Remove Site
					if(isset($_POST["b_remove_$rs[0]"])){
						$link->query("delete from sites where sid='$rs[0]'");
						jump("");
					}
					//Upload Primary Photo
					if(isset($_POST["b_upImg_$rs[0]"])){
						move_uploaded_file($_FILES["b_file_$rs[0]"]["tmp_name"], "assets/img/sites/primary/$rs[0].jpg");
						$link->query("update sites set loc_img = 1 where sid='$rs[0]'");
						jump("");
					}
					//Upload Link Plan
					if(isset($_POST["b_upLink_$rs[0]"])){
						move_uploaded_file($_FILES["b_link_$rs[0]"]["tmp_name"], "assets/img/sites/linkplan/$rs[0].jpg");
						$link->query("update sites set link_plan = 1 where sid='$rs[0]'");
						jump("");
					}
					//Upload Speedtest Result
					if(isset($_POST["b_upTest_$rs[0]"])){
						move_uploaded_file($_FILES["b_test_$rs[0]"]["tmp_name"], "assets/img/sites/speedtest/$rs[0].jpg");
						$link->query("update sites set speedtest = 1 where sid='$rs[0]'");
						jump("sites.php?sites='$rs[0]'");
					}
					//Upload Additional Photo
					if(isset($_POST["b_upAdd_$rs[0]"])){
						move_uploaded_file($_FILES["b_add_$rs[0]"]["tmp_name"], "assets/img/sites/additional/$rs[0].jpg");
						$link->query("update sites set add_img = 1 where sid='$rs[0]'");
						jump("");
					}
					// Format Number into 4 Digits
					$cont = $rs[0];
					$roll = sprintf("%04d", $cont);

					$cont = $rs["cont_person"];
					$cont = ucwords(strtolower($cont));

					$ex2=$link->query("select * from placement p where p.pcode='".$rs["place"]."'");
					$rs2=mysqli_fetch_array($ex2);		
					$place=$rs2["pname"];
						
					$ex3=$link->query("select * from municipality m where m.mcode='".$rs["mcode"]."'");
					$rs3=mysqli_fetch_array($ex3);		
					$ctm=$rs3["mname"];

					$rema = $rs["remarks"];
					$rema = ucwords(strtolower($rema));

					$cbg = "background:rgba(255,255,255, 0.8)";
					
					$date=date_create($rs["inst_date"]); 
					
					echo"
						<div class='col-lg-3 col-md-6' id='div_$rs[0]' 
							   onmouseout=\"
								getID('div_controls_$rs[0]').style.visibility='hidden';
								getID('div_browse_$rs[0]').style.visibility='hidden';
								getID('div_speed_$rs[0]').style.visibility='hidden';
								getID('add_device_$rs[0]').style.visibility='hidden';
								getID('div_remove_$rs[0]').style.visibility='hidden';

							\" onmousemove=\"
								getID('div_controls_$rs[0]').style.visibility='visible';
								getID('div_browse_$rs[0]').style.visibility='visible';
								getID('div_speed_$rs[0]').style.visibility='visible';
								getID('add_device_$rs[0]').style.visibility='visible';
								getID('div_remove_$rs[0]').style.visibility='visible';
							\">
							
							<div class='sbox' style='$statb;padding-bottom:1px;position:relative'>";
							
								//Counter
								echo"
								<div style='position:absolute;left:5px;top:5px;z-index:2'>
									<div style=\"$stati;padding:5px;border-bottom-right-radius:15px;border-top-left-radius:5px;\">";
										$cont = $i;
										printf("%02d", $cont); 										
									echo"		
									</div>
								</div>";

								//Delete Button
								if (isset($_SESSION["type"]) && $_SESSION["type"] == "Admin") {
								echo"
								<div style='position:absolute;top:1px;right:0;z-index:99;visibility:hidden' id='div_remove_$rs[0]'>
									<img onclick=\"site_delete('$rs[0]');\" src='assets/img/_delete.png' style='height:40px;width:40px' title='Delete'/>
								</div>";
								}
								
								//Card Content
								echo"
								<div style='margin:20px 10px 10px 10px;padding:5px;position:relative;border-radius:5px'>";				
									echo"<img onclick=\"jump('site_details.php?sites=$rs[0]')\" 
										style='margin-top:10px;border-radius:5px;width:100%;height:160px;cursor:pointer' title='Click to View Details' ";
									//Card Profile Photo
									if(file_exists("assets/img/sites/primary/$rs[0].jpg")){			
										echo" src='assets/img/sites/primary/$rs[0].jpg?".date("h:i:s")."' />";
									}else{
										echo" src='assets/img/sample2.jpg' style='opacity:.5'/>";
									}
									//Restrict Unauthorized to Alter Data
									if(isset($_SESSION['user'])){
									echo"
									<div style='position:absolute;top:15px;left:13px;visibility:hidden' id='div_speed_$rs[0]'>";								
									//Upload Speedtest Result Button
									echo"
										<input type=file name='b_test_$rs[0]' id='b_test_$rs[0]' style='display:none' onchange=\"if(this.value!='')$('#b_upTest_$rs[0]').click();\"/> 
										<input type=submit name='b_upTest_$rs[0]' id='b_upTest_$rs[0]' value='Upload' style='display:none'/> 
										<input style='width:123px' class='btn-box' type=button value='Add Speedtest' onclick=\"$('#b_test_$rs[0]').click();\"/> &nbsp; "; 
							
									//Add Additional Photo Button
									echo"
										<input type=file name='b_add_$rs[0]' id='b_add_$rs[0]' style='display:none' onchange=\"if(this.value!='')$('#b_upAdd_$rs[0]').click();\"/> 
										<input type=submit name='b_upAdd_$rs[0]' id='b_upAdd_$rs[0]' value='Upload' style='display:none'/> 
										<input style='width:123px' class='btn-box' type=button value='Additional Pic' onclick=\"$('#b_add_$rs[0]').click();\"/>
									</div>
									
									<div style='position:absolute;bottom:10px;left:13px;visibility:hidden;text-align:center' id='div_browse_$rs[0]'>";
									//Upload Profile Photo Button
									echo"
										<input type=file name='b_file_$rs[0]' id='b_file_$rs[0]' style='display:none' onchange=\"if(this.value!='')$('#b_upImg_$rs[0]').click();\"/> 
										<input type=submit name='b_upImg_$rs[0]' id='b_upImg_$rs[0]' value='Upload' style='display:none'/> 
										<input style='width:123px' class='btn-box' type=button value='Change Photo' onclick=\"$('#b_file_$rs[0]').click();\"/> &nbsp; ";			
									//Upload Link Photo Button
									echo"
										<input type=file name='b_link_$rs[0]' id='b_link_$rs[0]' style='display:none' onchange=\"if(this.value!='')$('#b_upLink_$rs[0]').click();\"/> 
										<input type=submit name='b_upLink_$rs[0]' id='b_upLink_$rs[0]' value='Upload' style='display:none'/> 
										<input style='width:123px' class='btn-box' type=button value='Add Link Photo' onclick=\"$('#b_link_$rs[0]').click();\"/>
									</div>";
									}
								echo"
								</div>
								<div style='margin:10px 15px 15px 15px;position:relative;$cbg;padding:10px 10px 0px 10px;border-radius:5px'>
									<div style='font-weight:bold;font-size:20px'>
										<a href='site_details.php?sites=$rs[0]' style='$statc'>
											".$rs["barangay"]." ".$rs["place"]."
										</a>
									</div>
									<p class='description'>
										Mun: $ctm (".str_replace($val,$rep,$rs["mcode"]).")<br>
										Brgy: ".str_replace($val,$rep,$rs["barangay"])."<br>
										Site: $place (".str_replace($val,$rep,$rs["place"]).")<br>
										Installer: ".$rs["installer"]."<br>
										Inst Date: ".date_format($date,"M d, Y")."<br>";
										echo"Contact: "; 
											if($cont==""){
												echo"None<br>"; 
											}else{
												echo"$cont<br>"; 
											}
										echo"Number: "; 
											if($cont==""){
												echo"None<br>"; 
											}else{
												echo"".$rs["cont_number"]."<br>";
											}
										echo"Status: <b style='$statc'>$statx </b>"; 
											if(isset($_SESSION['user'])) { 
												echo $hosts; 
											} 
										echo"
									</p>";

									//Restrict Unauthorized to Alter Data
									if(isset($_SESSION['user'])){
									//Add Device Button
									echo"								
									<div style='text-align:center;position:absolute;top:5px;left:8px;visibility:hidden' id='add_device_$rs[0]'>
										<input onclick=\"jump('sites_add_dev.php?sites=".$rs["sid"]."')\" style='width:123px' class='btn-box' type=button value='Add Device' /> &nbsp;
										<input type='button' style='width:123px' class='btn-box' onclick=\"updateStatus(this.value,'$rs[0]')\" value='Add Remarks'/>
									</div>";
									
									//Update Button
									echo"
									<div style='text-align:center;position:absolute;bottom:10px;visibility:hidden' id='div_controls_$rs[0]'>
										<a rel='facebox' href='site_edit.php?sites=$rs[0]'>
											<input style='width:123px' class='btn-box' type='button' value='Update Site'></a> &nbsp; 
										<a href='https://$hosts' target='_blank'>
											<input style='width:123px' class='btn-box' type='button' value='Go to IP Address' title='Go to IP Address'></a>
									</div>";
									}
									echo"
								</div>
							</div>
						</div> ";
						$i++;						
					}

					} else {
					//No Records Found Error
					echo"
					<div style='text-align:center;color:red;font-size:25px'><br>
						<div>Searching <b class='blinking'>...</b> $value</div>
						<div><img src='assets/img/no_records.jpg' style='border-radius:10px;width:400px'></div>
						<div class='blinking'>No records found!</div>
					</div>";
					}
				?>
				</form>
			</div><br>
		</div>
	</section>	
</main>

<?php require("footerNAV.php");?>

<div id="preloader"></div>

<!-- Vendor JS Files -->
<script src="assets/vendor/jquery/jquery.min.js"></script>
<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/vendor/jquery.easing/jquery.easing.min.js"></script>
<script src="assets/vendor/php-email-form/validate.js"></script>
<script src="assets/vendor/owl.carousel/owl.carousel.min.js"></script>
<script src="assets/vendor/venobox/venobox.min.js"></script>
<script src="assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
<script src="assets/vendor/aos/aos.js"></script>

<!-- Template Main JS File -->
<script src="assets/js/main.js"></script>

</body>

</html>

<script>
	function site_delete(sid){	
		if(confirm("Are you sure you want to Remove this Site?")){
			xmlhttp.onreadystatechange=function()
			{
				if (xmlhttp.readyState==4 && xmlhttp.status==200){
					if(xmlhttp.responseText=="Success"){
						$("#div_"+sid).animate({
					opacity:0
					},500);
				}else{
					alert(xmlhttp.responseText);
					}
					$("#div_"+sid).animate({
					opacity:0
					},500);
				}
			}					
			xmlhttp.open("GET","delete_site.php?sid="+sid,true);
			xmlhttp.send();
			}
		}

	function updateStatus(value,sid){	
		if(value=="Add Remarks"){
			var rem=prompt("Enter Remarks:");
			updateRemarks(sid,rem);
		}
	}
	
	function updateRemarks(sid,remarks){	
		xmlhttp.onreadystatechange=function(){
			if (xmlhttp.readyState==4 && xmlhttp.status==200){
				if(xmlhttp.responseText==""){
					jump("");
				}else
					alert(xmlhttp.responseText);
			}
		}						
		xmlhttp.open("GET","sites_update_remarks.php?sid="+sid+"&remarks="+remarks,true);
		xmlhttp.send();
	}

	function sessionEnd(usrid){	
		if(confirm("Are you sure you want to Logout?")){
			window.location.href = 'logout.php';
		}
	}

</script>