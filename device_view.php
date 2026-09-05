<style>
	.box-title{
		width:auto;
		bottom:10px;
		font-size:20px;
		background:#fff;
		left:0%;right:0%;
		font-weight:bold;	
		position:absolute;		
		text-align:center;
	}
</style>
<?php 
	require("connect.php"); 
	
	$rec=1;

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
						
	$dev="";
	if($_GET["sites_detail"]!="")
		$dev=" and id='".$_GET["sites_detail"]."' ";
												
	$exd = $link->query("select * from sites_detail where id=id $dev order by id limit $from,$to ");
	  
	while($rsd = mysqli_fetch_array($exd)){	

	$exd = $link->query("select * from sites_detail where sites_detail.id='$rsd[0]' and sites_detail.id=sites_detail.id ");
	
		while($rsd = mysqli_fetch_array($exd)){	
			echo"
			<div class='row' style='margin:2px;background:#fff;border-radius:5px;position:relative;width:300px;height:300px'>			
				<img style='width:300px;opacity:.7' ";
					if(file_exists("assets/img/items/".$rsd["device_code"].".jpg")){			
						echo" src='assets/img/items/".$rsd["device_code"].".jpg?".date("h:i:s")."' />";
					}else{
						echo" src='assets/img/no_image.jpg' />";
					}	
				echo"
				<div class='box-title'>
				".$rsd["device_name"]."</div>
			</div>
			<div class='row' style='margin:12px 2px 2px 2px;position:relative;width:300px'>
				<button style='margin-bottom:10px' class='btn btn-light btn-block'> 
					Quantity: <b>".$rsd["device_qty"]." ".$rsd["device_unit"]."</b>
				</button>
				<button style='margin-bottom:10px' class='btn btn-light btn-block'> 
					Device Code: <b>".$rsd["device_code"]."</b>
				</button>
				<button style='margin-bottom:10px' class='btn btn-light btn-block'> 
					Installed Date: <b>".$rsd["inst_date"]."</b>
				</button>
				<button class='btn btn-light btn-block'> 
					SN/Mac Addres: <b>".$rsd["serial_mac"]."</b>
				</button>
			</div>";
		}			
	}				
?>			
