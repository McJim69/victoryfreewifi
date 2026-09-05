<style>
	#nav div{
		cursor:pointer;
		background:#fff;
		padding: 5px;
		border-radius:5px;
		display:table-cell;

	#nav div:hover{
		background:#ff0000;
		color:#fff;
	}

	#nav #opt{
		padding: 50px;
		text-align: center;
		vertical-align: middle;
	}	
</style>

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
	</div>