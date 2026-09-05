<?php
	require("connect.php");
	require("header.php");
?>

<body>

<?php require("menunav.php");?>

<script>setActive("team");</script>

<script>setActive("office");</script>

<main id="main">
	<section id="team" class="team team-bg">
		<div class="container" data-aos="fade-up">
			<div class="section-title">
				<h2 style="color:darkblue">CISO Team<br>
					<?php
						if(isset($_SESSION['user'])){
							echo"<a rel='facebox' href='team_add.php'><input class='btn btn-sm btn-warning' value='Add Team Member'></a>";
						}
					?>
					<button class='btn btn-sm btn-warning' id="openModalButton" data-bs-toggle="modal" data-bs-target="#orgtModal" >Organizational Chart</button>

				</h2>
				<p class="pmargin" style="color: #fff;">
					With the power of a highly skilled team emphasizing equality, integrated systems, and assurance of reliability, 
					CISO strives to achieve a greater level of expertise and develop longlasting relationships with its clients.
				</p>
			</div>
			<form action="#" method="POST" enctype="multipart/form-data">
			  <div class="row">
				<?php
				  $ex = $link->query("SELECT * FROM ciso_team s ORDER BY cid");     
				  while($rs = mysqli_fetch_array($ex)){
				?>
				  <div class="col-sm-12 col-md-6 col-lg-6 mb-4" 
					   id="div_<?php echo $rs[0]; ?>"
					   onmouseout="getID('div_controls_<?php echo $rs[0]; ?>').style.visibility='hidden';" 
					   onmousemove="getID('div_controls_<?php echo $rs[0]; ?>').style.visibility='visible';">

					<div class="member d-flex align-items-start">
					  <div class="member-img me-3">
						<img class="img-fluid rounded-circle border border-secondary" src="
						  <?php 
							echo file_exists("assets/img/team/$rs[0].jpg") 
							  ? "assets/img/team/$rs[0].jpg?".date("H:i:s") 
							  : "assets/img/user.png?".date("H:i:s"); 
						  ?>" 
						/>
					  </div>

					  <div class="member-info flex-grow-1">
						<h4 class="text-primary"><?php echo $rs["fullname"]; ?></h4>
						<span><?php echo $rs["position"]; ?></span>
						<p><?php echo $rs["description"]; ?></p>
						<div class="social">
						  <a href="<?php echo $rs["socialmedia"]; ?>"><i class="ri-facebook-fill text-light"></i></a>
						  <a href="#"><i class="ri-phone-fill text-light"></i></a> &nbsp; 
						  <small><?php echo $rs["phonenumber"]; ?></small>
						</div>
					  </div>
					</div>

					<?php if(isset($_SESSION['user'])): ?>
					  <div class="editbtn mt-2" style="visibility:hidden" id="div_controls_<?php echo $rs[0]; ?>">
						<input type="file" name="b_file_<?php echo $rs[0]; ?>" id="b_file_<?php echo $rs[0]; ?>" 
							   style="display:none" onchange="if(this.value!='')$('#b_upImg_<?php echo $rs[0]; ?>').click();" /> 
						<input type="submit" name="b_upImg_<?php echo $rs[0]; ?>" id="b_upImg_<?php echo $rs[0]; ?>" 
							   value="Upload" style="display:none"/> 
						<a><input class="btn-team btn-sm edit btn-box" value="Change Pic" onclick="$('#b_file_<?php echo $rs[0]; ?>').click();"/></a><br>
						<a rel="facebox" href="team_edit.php?ciso_team=<?php echo $rs[0]; ?>"><input class="btn-team btn-sm edit btn-box" value="Edit Profile"/></a><br>
						<?php if($_SESSION["type"]=="Admin"): ?>
						  <a><input class="btn-team btn-sm edit btn-box" onclick="deleteTeam('<?php echo $rs[0]; ?>')" value="Delete Team"/></a>
						<?php endif; ?>
					  </div>
					<?php endif; ?>
				  </div>
				<?php } ?>
			  </div>
			</form>
		</div>
	</section>
</main>

<!-- MODAL -->
	<div class="modal fade" id="orgtModal" tabindex="-1" aria-labelledby="orgtModalLabel" aria-hidden="true" style="margin-left:-100px;">
		<div class="modal-dialog">
			<div class="modal-content" style="position:relative;text-align:center;width:900px;height:670px;">
				<div class="modal-header">
					<h5 class="modal-title" id="orgtModalLabel">Organizational Chart</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>

				<div class="modal-body" style="position:relative;text-align:center;width:900px;height:670px;">
					<div id='modcont'><img id='image' src='assets/img/org_chart.jpg'></div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-sm btn-danger" data-bs-dismiss="modal" style="margin-top:-10px">Close</button>
				</div>
			</div>
		</div>
	</div>
<!-- END MODAL -->

<?php require("footer.php");?>

</body>

</html>

<script>
	function deleteTeam(cid){	
		if(confirm("Are you sure you want to Remove this Team Member?")){
			xmlhttp.onreadystatechange=function(){
				if (xmlhttp.readyState==4 && xmlhttp.status==200){
					if(xmlhttp.responseText=="Success"){
						$("#div_"+cid).animate({
					opacity:0
					},500);
				}else{
					alert(xmlhttp.responseText);
					}
					$("#div_"+cid).animate({
					opacity:0
					},500);
				}
			}					
			xmlhttp.open("GET","delete_team.php?cid="+cid,true);
			xmlhttp.send();
		}
	}
</script>

<script>	
	if ( window.history.replaceState ) {
	  window.history.replaceState( null, null, window.location.href );
	}
</script>