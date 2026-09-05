<?php 
	require("connect.php"); 
	
	$rec=1;

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
				
	$cat="";
	if($_GET["category"]!="")
		$cat=" and cat_id='".$_GET["category"]."' ";
												
	$ex = $link->query("select * from category where cat_id=cat_id $cat order by cat_id limit $from,$to ");

	while($rs = mysqli_fetch_array($ex)){	

	$ex = $link->query("select * from category c where c.cat_id='$rs[0]' and c.cat_id=c.cat_id ");
	$ii=1;
?>

<section id="contact" class="contact">
	<div class="container">
		<div class="text-center" style="margin-top:-40px;border-radius:5px;font-size:20px;font-weight:bold">
			<?php
				echo"<img style='height:200px;border-radius:50%;border:5px solid #bbb' ";
				if(file_exists("assets/img/categories/$rs[0].png")){			
					echo" src='assets/img/categories/$rs[0].png? ".date("h:i:s")." ' />";
				}else{
					echo" src='assets/img/inventory.png' style='opacity:.5' />";
				}
			?>				
		</div>
	
			<?php
					
				while($rs = mysqli_fetch_array($ex)){	
				
				echo"	
				<form action='category_edit_proc.php' method='POST' enctype='multipart/form-data'>
					<div class='mt-5 mt-lg-0' style='width:300px;text-align:center'>			
						<div class='row'>				
							<div style='padding-top:10px' class='form-group mt-3 mt-md-0'>
								<input type='hidden' class='form-control' name='cat_id' value='".$rs["cat_id"]."' readonly>
								<input type='text' class='form-control' name='cat_name' value='".$rs["cat_name"]."' placeholder='Category Name' required >
							</div>
						</div>
						<div class='row' style='margin-bottom:-40px'>
							<div class='form-group mt-3'>
								<div style='text-align:center'>
									<button type='SUBMIT' class='form-control btn btn-primary' name='upDate'>Update</button>
								</div>
							</div>
						</div>
					</div> 
				</form>";
			  }		
		   }			
		?>					
	</div>
</section>