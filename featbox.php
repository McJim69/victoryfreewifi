<!-- ======= Icon Boxes Section ======= -->
    <section id="icon-boxes" class="icon-boxes">
      <div class="container justify-content-center">
        <div class="row justify-content-center">
		
		<?php
						
			$ex=$link->query("SELECT * FROM sites ORDER BY inst_date DESC LIMIT 4");	

			while($rs=mysqli_fetch_array($ex)){

			$cont = $rs[0];
			$roll = sprintf("%04d", $cont);
			$date = date_create($rs["inst_date"]);
			
			$ex2=$link->query("select * from placement p where p.pcode='".$rs["place"]."'");
			$rs2=mysqli_fetch_array($ex2);		
			$place=$rs2["pname"];
				
			$ex3=$link->query("select * from municipality m where m.mcode='".$rs["mcode"]."'");
			$rs3=mysqli_fetch_array($ex3);		
			$muni=$rs3["mname"];

			$ex1=$link->query("select * from barangays b where b.barangay='".$rs["barangay"]."'");

			$rs1=mysqli_fetch_array($ex1);		

			echo"
				<div class='col-lg-3 col-md-6' data-aos='fade-up'>
					<div onclick=\"jump('site_details.php?sites=$rs[0]')\" style='cursor:pointer'>
						<div style='margin:10px;padding:20px;background:#fff;border-radius:10px;box-shadow: rgba(0, 0, 0, 0.16) 0px 3px 6px, rgba(0, 0, 0, 0.23) 0px 3px 6px;'>
						<div class='box-heading'>Latest Rollout</div><br>
							<h4 class='title'><a>".$rs1["barangay"]." ".$rs["place"]."</a></h4>
							<p class='description'>
								Mun: <a>$muni</a><br>
								Brgy: <a>".$rs1["barangay"]."</a><br>
								Site: <a>$place</a><br>
								Installer: <a>".$rs["installer"]."</a><br>
								Installation: <a>".date_format($date,"M d, Y")."</a>
							</p>
						</div>
					</div>
				</div>";
			}
		?>
		</div>
	</div>
</section><!-- End Icon Boxes Section -->
