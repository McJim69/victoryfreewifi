<style>
	#nav div{
		font-size:15px;
		cursor:pointer;
		background:#fff;
		padding:3px 5px 3px 5px;
		border-radius:5px;
		display:table-cell;

	#nav div:hover{
		background:#ff0000;
		color:#fff;
	}

	#nav #opt{
		font-size:15px;
		padding:3px 5px 3px 5px;
		text-align: center;
		vertical-align: middle;
	}	
</style>

<!-- ======= Search NAV ======= -->
<form method="post" enctype="multipart/form-data">
<header id="header" class="fixed-top header-inner-pages">
   <div class="container d-flex align-items-center justify-content-between">
	  <h1 class="logo"><a href="barangays.php" class="scrollto">BARANGAYS</a> &nbsp; <img onclick="printF()" src="assets/img/print1.png?<?php date("h:i:s");?>" height="35"></h1> 
		<nav class="nav-menu d-none d-lg-block">
			<ul>
				<li>
					<input style='padding:3px 5px 3px 5px;' type="text" class="btn btn-light" size="25" placeholder="Type a keyword" name="t_search" id="t_search" value="<?php if($_POST["t_search"]!=""){echo $_POST["t_search"];} ?>" required>
					<input style='padding:3px 5px 3px 5px;' type="submit" class="btn btn-light" name="b_search" value="Search">
					<?php 
						if(isset($_SESSION['user'])){ 
							echo"<input style='padding:3px 5px 3px 5px;' type='button' class='btn btn-light' size='10' value='Update' onclick=\"jump('barangays_update.php')\">";
						}
					?>
					<select style='padding:3px 5px 3px 5px;text-align:left' class="btn btn-light" onchange="if(this.value=='Municipality')jump('barangays.php'); else jump('barangays.php?municipality='+this.value+'&barangays=<?php echo $_GET["barangays"];?>&wifi=<?php echo $_GET["wifi"];?>&los=<?php echo $_GET["los"];?>')">
						<option>Municipality</option>
						<?php
							$ex2=$link->query("select mcode from barangays where barangay='".$_GET["barangays"]."' group by mcode order by mcode")or die(mysqli_error($link));			
							if($_GET["barangays"]=="" || $_GET["barangays"]=="Barangays")							
							$ex2=$link->query("select mcode from barangays group by mcode order by mcode")or die(mysqli_error($link));																	
							while($rs=mysqli_fetch_array($ex2)){
								echo "<option ";
							if($_GET["municipality"]===$rs[0])
								echo "selected";
								echo">$rs[0]</option>";
							}
						?>
					</select>
					<select style='padding:3px 5px 3px 5px;text-align:left' class="btn btn-light" onchange="jump('?municipality=<?php echo $_GET["municipality"];?>&wifi=<?php echo $_GET["wifi"];?>&los=<?php echo $_GET["los"];?>&barangays='+this.value)">
						<option>Barangays</option>
						<?php
							$ex2=$link->query("select barangay from barangays where mcode='".$_GET["municipality"]."' group by barangay order by barangay")or die(mysqli_error($link));
							if($_GET["municipality"]=="" || $_GET["municipality"]=="Municipality")
							$ex2=$link->query("select barangay from barangays group by barangay order by barangay")or die(mysqli_error($link));																
							while($rs=mysqli_fetch_array($ex2)){
								echo "<option ";
							if($_GET["barangays"]===$rs[0])
								echo "selected";
								echo">$rs[0]</option>";
							}
						?>
					</select>
					<select style='padding:3px 5px 3px 5px;text-align:left' class="btn btn-light" onchange="jump('?municipality=<?php echo $_GET["municipality"];?>&barangays=<?php echo $_GET["barangays"];?>&wifi=<?php echo $_GET["wifi"];?>&los='+this.value)">
						<option>LOS</option>
						<?php
							$ex2=$link->query("select los from barangays where mcode='".$_GET["municipality"]."' and barangay='".$_GET["barangays"]."' group by los order by los")or die(mysqli_error($link));
							if($_GET["barangays"]=="" || $_GET["barangays"]=="Barangays")
							$ex2=$link->query("select los from barangays group by los order by los")or die(mysqli_error($link));																	
							while($rs=mysqli_fetch_array($ex2)){
								echo "<option ";
							if($_GET["los"]===$rs[0])
								echo "selected";
								echo">$rs[0]</option>";
							}
						?>
					</select>	
					<select style='padding:3px 5px 3px 5px;text-align:left' class="btn btn-light" onchange="jump('?municipality=<?php echo $_GET["municipality"];?>&barangays=<?php echo $_GET["barangays"];?>&los=<?php echo $_GET["los"];?>&wifi='+this.value)">
						<option>WiFi</option>
						<?php
							$ex2=$link->query("select win from barangays where mcode='".$_GET["municipality"]."' and barangay='".$_GET["barangays"]."' group by win order by win")or die(mysqli_error($link));
							if($_GET["los"]=="" || $_GET["los"]=="LOS")
							$ex2=$link->query("select win from barangays group by win order by win")or die(mysqli_error($link));																	
							while($rs=mysqli_fetch_array($ex2)){
								echo "<option ";
							if($_GET["wifi"]===$rs[0])
								echo "selected";
								echo">$rs[0]</option>";
							}
						?>
					</select>	
				</li>
			</ul>
		</nav>
    </div>
	<div style="background:rgba(6, 98, 178, 0.9) !important;padding:4px;margin-top:-2px">
		<div class="container d-flex align-items-center justify-content-between">
			<?php for($j=1;$j<=mysqli_num_rows($ex1)/$rec+1;$j++) ?>
			<div id="nav" style="margin:-5px 0 0 0">
				<div style='border:0;background:transparent;padding:3px;display:block'></div>
				<div onclick="jump('?municipality=<?php echo $_GET["municipality"]; ?>&page=1&value=<?php echo $value."&barangays=".$_GET["barangays"]; ?>')">&laquo; first</div>
				<div style='border:0;background:transparent;padding:3px'></div>
				<div onclick="<?php if($_GET["page"]>1){echo "jump('?municipality=".$_GET["municipality"]."&page=".($_GET["page"]-1)."&value=$value&barangays=".$_GET["barangays"]."')";} ?>">&laquo; prev</div>
				<div style='border:0;background:transparent;padding:3px'></div>			
				<div style="background:#fff" >Showing Page: <?php echo $p." of ".number_format($j-1,0);?> Pages &nbsp;&nbsp;(Total: <?php echo number_format(mysqli_num_rows($ex1),0);?> Records)</div>
				<div style='border:0;background:transparent;padding:3px'></div>
				<div onclick="<?php if($_GET["page"]<$ex1->num_rows/$rec){echo "jump('?municipality=".$_GET["municipality"]."&page=";
				if($_GET["page"]=="")
					echo"2";   
				else
					echo ($_GET["page"]+1);
					echo"&value=$value&barangays=".$_GET["barangays"]."');";} ?>" >&raquo; next
				</div>
				<div style='border:0;background:transparent;padding:3px'></div>
				
				<div onclick="jump('?municipality=<?php echo $_GET["municipality"]; ?>&page=<?php echo (number_format($ex1->num_rows/$rec,0)); echo"&value=$value&barangays=".$_GET["barangays"]; ?>')">&raquo; last</div>
				<div style='border:0;background:transparent;padding:3px'></div>

				<div id="opt">Goto page #: 
					<select id='s_pn' onchange="jump('?municipality=<?php echo $_GET["municipality"]; ?>&page='+getID('s_pn').value+'&value=<?php echo $value."&barangays=".$_GET["barangays"]; ?>')" >
						<?php
							for($j=1;$j<=$ex1->num_rows/$rec+1;$j++){
							echo "<option ";
							if($_GET["page"]==$j)
							echo "selected";
							ECHO" >$j</option>";
							}
						?>
					</select>
				</div> 
				<div style='border:0;background:transparent;padding:3px'></div>
				<div style="text-align:right;background:transparent;color:#fff">
					<small>
						<b style="color:orange"> LOS</b>* Line of Sight to Base Station &nbsp; &nbsp; 
						<b style="color:orange"> WiFi</b>* Barangay with WiFi Installation
					</small>
				</div> 
			</div>
		</div>
		<div class="container d-flex align-items-center justify-content-between">
			<table class="table bg-secondary text-light" style="margin:5px 0 -4px 0">
				<thead style='border:1px solid #535353'>
					<tr>
						<th width='3%' style='text-align:center'>#</th>
						<th width='16%'>Municipality</th>
						<th width='15%'>Barangay</th>
						<th width='15%'>STN Location</th>
						<th width='18%'>Potential Link</th>
						<th width='15%'>Link Location</th>
						<th width='5%'>LOS*</th>
						<th width='5%'>WiFi*</th>
						<?php 
							if(isset($_SESSION['user'])){ 
								echo"<th>Action</th>";
							}
						?>
					</tr>
				</thead>
			</table>
		</div>
	</div>
</header>
</form>