<style>
.footnav{
	width:100%;
	padding:5px;
	position:fixed;
	left:0;
	bottom:0;
	background:#A91B0D;
	z-index:999;
}
.arrows{
	height:35px;
	width:35px;
	border-radius:35px;
	margin-top:5px;
	background:#1e67a8;		
}
.arrows:hover{
	background:#f6b024;
}
</style>

<!--FooterNAV-->
	<div class="footnav">
		<div style="padding:1px;text-align:center;" >
			<table style="margin:0 auto;">
				<tr style="background:transparent" >
					<td>
						<input class="arrows" type="image" value="Previous" src="assets/img/prev1.png"
						onclick="jump('?page=<?php echo ($_GET["page"] ?? 1) - 1; ?>&municipality=<?php echo $_GET["municipality"] ?? ''; ?>&barangays=<?php echo $_GET["barangays"] ?? ''; ?>&places=<?php echo $_GET["places"] ?? ''; ?>')">
					</td>
					<td>
						<select style="height:35px;padding:5px;margin:5px;text-align:center" id="s_pn" onchange="jump('?page='+this.value+'<?php echo "&municipality=" . ($_GET["municipality"] ?? ""); ?>&barangays=<?php echo $_GET["barangays"] ?? ""; ?>&places=<?php echo $_GET["places"] ?? "";?>')">
							<option>Page</option>
							<?php
								$page = $_GET["page"] ?? 1;
								$rec = $rec ?? 1;           
								$totalRows = mysqli_num_rows($ex1);
								$totalPages = ceil($totalRows / $rec); 

								for ($j = 1; $j <= $totalPages; $j++) {
									$selected = ($page == $j) ? "selected" : "";
									echo "<option $selected>$j</option>";
								}
							?>
						</select>
					</td>
					<td>
						<input class="arrows" type="image" value="Next" src="assets/img/next1.png"
						onclick="jump('?page=<?php echo ($_GET["page"] ?? 1) + 1; ?>&municipality=<?php echo $_GET["municipality"] ?? ''; ?>&barangays=<?php echo $_GET["barangays"] ?? ''; ?>&places=<?php echo $_GET["places"] ?? ''; ?>')">
					</td>
				</tr>
			</table>
		</div>
	</div>
<!--EO FooterNAV-->