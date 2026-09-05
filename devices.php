<?php
	error_reporting(0);
	require("connect.php");
	require("header.php");
	
	if(!isset($_SESSION['user'])){
		header("location:index.php");
		exit();
	}	
	
	$value = isset($_GET['value']) ? $_GET['value'] : "";
	
	$dev = "";
	if (isset($_GET["devices"]) && $_GET["devices"] !== "" && $_GET["devices"] !== "Devices") {
		$dev = " and device_name='" . $_GET["devices"] . "'";
	}

	$cod = "";
	if (isset($_GET["codes"]) && $_GET["codes"] !== "" && $_GET["codes"] !== "Codes") 
		$cod = " and device_code='" . $_GET["codes"] . "'";

	$cat = "";
	if (isset($_GET["categories"]) && $_GET["categories"] !== "" && $_GET["categories"] !== "Categories") 
		$cat = " and device_category='" . $_GET["categories"] . "'";

	if(isset($_POST["b_search"])){
		$value=$_POST["t_search"];
	}

	$rec = 200;
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

	$ex=$link->query("select * from device d where 
	   (d.device_id like'%".$value."%' or
		d.device_code like'%".$value."%' or
		d.device_name like'%".$value."%' or
		d.device_category like'%".$value."%') $dev $cod $cat order by device_id DESC LIMIT $from,$to ");
	//
?>

<style>
	.dev-card{
		margin:15px;
		background:#eee;
		border-radius:10px;
		box-shadow: rgba(0, 0, 0, 0.16) 0px 3px 6px, rgba(0, 0, 0, 0.23) 0px 3px 6px;
	}
	.dev-card:hover{
		margin:15px;
		background:darkred;
		color:#fff;
		border-radius:10px;
		box-shadow: rgba(0, 0, 0, 0.3) 0px 19px 38px, rgba(0, 0, 0, 0.22) 0px 15px 12px;
	}
</style>

<body>

<?php require("menunav.php");?>

<script>setActive("admin");</script>
<script>setActive("devices");</script>

<form action='#' method='POST' enctype='multipart/form-data'>

<main id="main">
	<section id="breadcrumbs" class="breadcrumbs">
		<div class="container" style="margin-bottom:-15px">
			<ol>
				<li><a href="index.php">Home</a></li>
				<li><a href="admin.php">Admin</a></li> 
				<li><a href="categories.php">Category</a></li>
				<li><a href="devices.php">Devices</a></li> 
			</ol>
			<h2><a href="devices.php">Devices</a> &nbsp; 
				<a rel="facebox" href="device_add.php" title="Add Device">
					<input style="cursor:pointer;width:100px" type="text" class="btn-rollout" value="+Add Device">
				</a>
				<input class="btn-rollout" type="button" onclick="jump('devices_deployed.php')" value="Deployed">
				<input style="padding:6px" type="text" class="btn-device" placeholder="Type a keyword" name="t_search" id="t_search" value="<?php if (!empty($_POST["t_search"])) {echo htmlspecialchars($_POST["t_search"]);}?>" required>
				<input type="submit" class="btn-rollout" name="b_search" value="Search">
				<select style="padding:5px" class="btn-device" onchange="if(this.value=='Categories')jump('devices.php'); else jump('devices.php?categories='+this.value+'&devices=<?php echo $_GET["devices"];?>')">
					<option>Categories</option>
					<?php
						$device = isset($_GET["devices"]) ? $_GET["devices"] : "";
						$category = isset($_GET["categories"]) ? $_GET["categories"] : "";

						if ($device === "" || $device === "Devices") {
							$ex2 = $link->query("SELECT device_category FROM device GROUP BY device_category ORDER BY device_category") or die(mysqli_error($link));
						} else {
							$ex2 = $link->query("SELECT device_category FROM device WHERE device_name='" . $device . "' GROUP BY device_category ORDER BY device_category") or die(mysqli_error($link));
						}

						while ($rs = mysqli_fetch_array($ex2)) {
							echo "<option ";
							if ($category === $rs[0]) echo "selected";
							echo ">" . htmlspecialchars($rs[0]) . "</option>";
						}
					?>
				</select>				
				<select style="padding:5px" class="btn-device" onchange="jump('?categories=<?php echo $_GET["categories"];?>&devices='+this.value)">
					<option>Devices</option>
					<?php
						$category = isset($_GET["categories"]) ? $_GET["categories"] : "";
						$device = isset($_GET["devices"]) ? $_GET["devices"] : "";

						// Escape input to prevent SQL injection
						$escapedCategory = $link->real_escape_string($category);

						if ($category === "" || $category === "Categories") {
							$ex2 = $link->query("SELECT device_name FROM device GROUP BY device_name ORDER BY device_name") or die(mysqli_error($link));
						} else {
							$ex2 = $link->query("SELECT device_name FROM device WHERE device_category='$escapedCategory' GROUP BY device_name ORDER BY device_name") or die(mysqli_error($link));
						}

						while ($rs = mysqli_fetch_array($ex2)) {
							$selected = ($device === $rs[0]) ? "selected" : "";
							echo "<option $selected>" . htmlspecialchars($rs[0]) . "</option>";
						}
					?>

				</select>					
			</h2>
		</div>
	</section>
	<section style="margin-top:-60px;min-height:550px;" >
		<div class="container" data-aos="fade-up">
			<div class="row justify-content-center" style="padding:20px;margin-bottom:-50px">		
				<?php	
					if ($ex->num_rows > 0) {				

					while($rs=mysqli_fetch_array($ex)){	
		
						$did=$rs["device_id"];					
						$cod=$rs["device_code"];
						$nam=$rs["device_name"];
						$cat=$rs["device_category"];
						$stk=$rs["device_stock"];
						$mst=$rs["min_stock"];
						$unt=$rs["device_unit"];
						$img=$rs["img"];	

						if(isset($_POST["b_upImg_$rs[0]"])){
							move_uploaded_file($_FILES["b_file_$rs[0]"]["tmp_name"], "assets/img/items/$cod.jpg");
							$link->query("update device set img=1 where device_id='$rs[0]'");
							jump("");
						}	
					
						echo"
						<div class='col-lg-2 dev-card'>
							<div style='text-alig:center'>
								<div style='margin-left:-5px;margin-top:7px;text-align:center;background:#bbb;padding:5px;border-radius:50%;color:#545454;width:35px'>
									<strong>$i</strong>
								</div>
								<div style='text-align:center;margin-top:-30px'>
									<a rel='facebox' href='device_edit.php?device=$rs[0]' title='Edit'>
										<img style='border-radius:50%' width='100' height='100'";
										if(file_exists("assets/img/items/$cod.jpg")){			
											echo" src='assets/img/items/$cod.jpg?".date("h:i:s")."'/>";
										}else{
											echo" src='assets/img/cron.png?".date("h:i:s")."' />";
										} 
									echo"</a>
								</div>
								<div style='text-align:center'>
									<h6 class='fa' style='letter-spacing:2px'>$cod</h6><br>
									<small>$nam</small><br>";
								
								if($stk <= $mst) echo" <b style='color:red'>$stk</b>"; else echo" <b>$stk</b>"; 
								if($stk==1 or $stk==0){
									echo" $unt Remaining";
								}else{
									if($unt=="Box"){
									echo" $unt"; echo"es Remaining";
									}else{
									echo" $unt"; echo"s Remaining";
									}
								} echo"</small>
								</div>
								<div style='text-align:center;margin-bottom:15px'>
								<input type=file name='b_file_$rs[0]' id='b_file_$rs[0]' style='display:none' onchange=\"if(this.value!='')$('#b_upImg_$rs[0]').click();\"/> 
								<input type=submit name='b_upImg_$rs[0]' id='b_upImg_$rs[0]' value='Upload' style='display:none'/> 
								<a onclick=\"$('#b_file_$rs[0]').click();\" title='Change Photo'>
									<i class='btn-inst fa fa-image bg-success' style='margin-top:-5px;cursor:pointer'></i>
								</a>
								<a rel='facebox' href='device_edit.php?device=$rs[0]' title='Edit'>
									<i class='btn-inst fa fa-edit bg-info'></i>
								</a>";
								if($_SESSION["type"]=="Admin"){
									echo"<a onclick=\"deviceDelete('$rs[0]')\" title='Remove'>
									<i class='btn-inst fa fa-trash bg-danger' style='cursor:pointer'></i></a>";
									}
								echo"
								</div>
							</div>
						</div>";
					$i++;
					}
				}else{
					echo"
					<div style='text-align:center;color:red;font-size:25px'><br>
						<div>Searching... <b class='blinking'>$value</b></div>
						<div><img src='assets/img/no_records.jpg' style='border-radius:10px;width:400px'></div>
						<div>No records found! &nbsp; 
							<span class='text-primary' style='cursor:pointer' onclick=\"jump('devices.php')\"><small>Refresh</small></span>
						</div>
					</div>";
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

<script>
	function deviceDelete(device_id){	
		if(confirm("Are you sure you want to Remove this Team Member?")){
			xmlhttp.onreadystatechange=function(){
				if (xmlhttp.readyState==4 && xmlhttp.status==200){
					if(xmlhttp.responseText=="Success"){
						$("#div_"+device_id).animate({
					opacity:0
					},500);
				}else{
					alert(xmlhttp.responseText);
					}
					$("#div_"+device_id).animate({
					opacity:0
					},500);
				}
			}					
			xmlhttp.open("GET","device_delete.php?device_id="+device_id,true);
			xmlhttp.send();
		}
	}
</script>

<script>	
	if ( window.history.replaceState ) {
	  window.history.replaceState( null, null, window.location.href );
	}
</script>