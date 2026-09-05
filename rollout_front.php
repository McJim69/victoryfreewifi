<?php 
	require("sites_count.php"); 
	
	$exd=$link->query("SELECT * FROM video_link where vid=vid") or die (mysqli_error($link));
					
	$vids=mysqli_fetch_array($exd);

	if($vids["source"]=="null" || $vids["source"]==""){
		$source="https://www.youtube.com/watch?v=iAyx4VcclPs";
	}else{ 
		$source=$vids["source"];
	}	
?>

<section id="why-us" class="why-us" style="background:linear-gradient(rgba(255, 0, 0, 0.1), rgba(255, 0, 0, 0.3));">
	<div class="container-fluid">
		<div class="row">
			<div class="col-lg-5 align-items-stretch position-relative video-box" style="background-image: url('assets/img/updates.png?<?php date("h.i.s");?>')" data-aos="fade-right">
				<div class="content text-center">
					<div style="color:#545454;font-size:30px">
						<b>NEWS UPDATE</b> 
						<?php 
							if(isset($_SESSION['user'])){	
								echo"<br><small style='font-size:15px'><input onclick=\"updateLink(this.value,'$vids[0]')\" value='Change Video' class='btn btn-sm btn-danger' /></small>";
								//Default: https://www.youtube.com/watch?v=iAyx4VcclPs
							}
						?>
					</div>
				</div><a href="<?php echo $source;?>" class="venobox play-btn mb-4" data-vbtype="video" data-autoplay="true"></a>
			</div>

			<div class="col-lg-7 d-flex flex-column justify-content-center align-items-stretch" data-aos="fade-left">

				<div class="content">
					<div style="font-size:24px">Over 30,000 unique users served, with more than 2,000 concurrent users.</div>
					<p>
						Victory Free WiFi continues to roll out, especially in remote communities, as we work toward our goal of serving over 600 barangay centers and schools throughout the province.
					</p>
				</div>
				<div class="accordion-list">
					<ul>				
						<li data-aos="fade-up" data-aos-delay="100" style="background:#eee"> 
							<a data-bs-toggle="collapse" data-bs-target="#accordion-list-1" class="collapsed">
								<span>01</span> Total Installations: <b class="text-success"><?php echo $totALL;?></b>
								<small>(Still being updated)</small>
								<i class="bx bx-chevron-down icon-show"></i><i class="bx bx-chevron-up icon-close"></i>
							</a>
							<div id="accordion-list-1" class="collapse show" data-bs-parent=".accordion-list">
								<p style="margin:0 0 0 35px">	
									&bull; Barangay / Mun. Centers: <b class="text-success"><?php echo $totBMH;?></b><br>
									&bull; Public Schools (All Levels): <b class="text-success"><?php echo $totSCH;?></b><br>
									&bull; Other Stations and Places: <b class="text-success"><?php echo $totOTH;?></b><br>
								</p>
								<div style="margin:10px 0 0 45px">
									<button class="btn btn-sm btn-danger">
										<a rel="facebox" href="reportModal.php" style="padding:0;color:#fff;width:100px">
											<small>View Reports</small>
										</a>
									</button> &nbsp;
									<button class="btn btn-sm btn-danger">
										<a href="sites_status_mon.php" style="padding:0;color:#fff;width:100px">
											<small style="text-align:center;color:#fff">Sites Status</small>
										</a>
									</button>
								</div>
							</div>							
						</li>
						<li data-aos="fade-up" data-aos-delay="200" style="background:#eee">
							<a data-bs-toggle="collapse" data-bs-target="#accordion-list-2" class="collapsed">
								<span>02</span> Deployed Devices: <b class="text-success"><?php echo $totDEV;?></b> 
								<small>(Still being updated)</small>
								<i class="bx bx-chevron-down icon-show"></i><i class="bx bx-chevron-up icon-close"></i>
							</a>
							<div id="accordion-list-2" class="collapse" data-bs-parent=".accordion-list">
								<p style="margin-left:35px">	
									&bull; Router & Switch: <b class="text-success"><?php echo $allNRS;?></b><br>
									&bull; Backhaul Radios: <b class="text-success"><?php echo $allRAD;?></b><br>
									&bull; Client Access Points: <b class="text-success"><?php echo $allAPS;?></b><br>
									&bull; Other Deployed Devices: <b class="text-success"><?php echo $allOTH;?></b>
								</p>
							</div>
						</li>
						<li data-aos="fade-up" data-aos-delay="300" style="background:#eee">
							<a data-bs-toggle="collapse" data-bs-target="#accordion-list-3" class="collapsed">
								<span>03</span> DHCP Client Leased <b class="text-success"> ≈ 25,000</b> <small>(Average Daily)</small>
								<i class="bx bx-chevron-down icon-show"></i><i class="bx bx-chevron-up icon-close"></i>
							</a>
							<div id="accordion-list-3" class="collapse" data-bs-parent=".accordion-list">
								<p style="margin-left:35px">
									&bull; Network Area 1 - Capitol NET <b class="text-success"> ≈ 3,900 </b><br>
									&bull; Network Area 2 - Palpalan NET <b class="text-success"> ≈ 7,800 </b><br>
									&bull; Network Area 3 - Dipalutao NET <b class="text-success"> ≈ 9,600 </b><br>
									&bull; Network Area 4 - Kapamanok NET <b class="text-success"> ≈ 3,900 </b>
								</p>
							</div>
						</li>
					</ul>
				</div>
			</div>
		</div>
	</div>
</section>

<script>
	function updateLink(value,vid){	
		if(value=="Change Video"){
			var rem=prompt("Enter New Video Link :");
			updateSource(vid,rem);
		}
	}
	
	function updateSource(vid,source){	
		xmlhttp.onreadystatechange=function(){
			if (xmlhttp.readyState==4 && xmlhttp.status==200){
				if(xmlhttp.responseText==""){
					jump("");
				}else
					alert(xmlhttp.responseText);
			}
		}						
		xmlhttp.open("GET","change_video.php?vid="+vid+"&source="+source,true);
		xmlhttp.send();
	}
</script>
