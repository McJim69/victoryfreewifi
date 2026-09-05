<?php 
	require("connect.php");
	require("header_all.php");      

	// Query active/inactive counts
	$qryStatus = $link->query("SELECT SUM(status = 1) AS active, SUM(status = 0) AS inactive FROM sites");
	$row = $qryStatus->fetch_assoc();
	$rawACT = (int)$row['active'];
	$rawDWN = (int)$row['inactive'];

	// Totals and percentages
	$total = $rawACT + $rawDWN;
	$percent1 = ($total > 0) ? ($rawACT / $total) * 100 : 0;
	$percent2 = 100 - $percent1;

	// Format for display
	$totACT = number_format($rawACT);
	$totDWN = number_format($rawDWN);

	// Chart data
	$dataPoints1 = array(
		array("label" => "Online Sites", "y" => $percent1),
		array("label" => "Offline Sites", "y" => $percent2)
	);
?>

<body>

<?php require("menunav.php");?>

<script>
	setActive("sites"); 
	setActive("stats");
</script>

<script>
	window.onload = function () {
		var chart1 = new CanvasJS.Chart("chartContainer1", {
			animationEnabled: true,
			title: { text: "Out of <?php echo $total;?> Sites" },
			data: [{
				type: "bar",
				yValueFormatString: "#,##0.00\"%\"",
				indexLabel: "{label} ({y})",
				dataPoints: <?php echo json_encode($dataPoints1, JSON_NUMERIC_CHECK); ?>
			}]
		});
		chart1.render();
	}
</script>

<main style="background: rgba(255,0,0,0.2) url(assets/img/about-bg.png) no-repeat">
<div class="container" style="text-align:center;margin-top:120px">
    <div class="row justify-content-center text-center">
        <!-- Online Sites -->
        <div class="col-lg-3" style="margin-top:35px">
            <div style="border:1px solid #bbb;border-radius:5px;padding:5px;background:#bbb;color:green"><b>ONLINE SITES (<?php echo $totACT;?>)</b></div>
            <div style="border:1px solid #bbb;border-radius:5px;padding:10px;background:#fff;color:green;text-align:left;height:543px;overflow:auto;margin-bottom:30px">
            <?php 
                $i=1;
                $ex=$link->query("SELECT * FROM sites WHERE status=1 ORDER BY mcode");
                while($rs=mysqli_fetch_assoc($ex)){
                    echo "<div>$i. <a href=\"site_details.php?sites=".(int)$rs["sid"]."\">".$rs["mcode"]." ".$rs["barangay"]." ".$rs["place"]."</a></div>";
                    $i++;
                }
            ?>
            </div>
        </div>

        <!-- Center Chart + Totals -->
        <div class="col-lg-6" style="border:1px solid #bbb;border-radius:5px;margin-top:35px;margin-bottom:30px;background-color: rgba(255,0,0,0.2);">
            <div class="row justify-content-center" style="font-size:50px">
				<b class="text-success">Sites Status</b>
			</div>  
			<div class="row justify-content-center" style="margin:-10px 0 0 0">			
				<div style="font-size:30px">
					<a href="sites_status_mon.php"><i class="fa fa-refresh"></i></a>
					<b class="text-danger" id="countdown"></b>
					<a href="sites_status_mon.php"><i class="fa fa-refresh"></i></a>
				</div>
			</div>
			<div class="row justify-content-center" style="width:100%;margin-top:10px">
				<div id="chartContainer1" style="height:165px"></div>          
			</div> 
            <div class="row justify-content-center" style="margin-top:15px">
                <div>
                    <button class="btn btn-primary" style="width:100%;background:#369ead;font-size:30px">
                        Active: <b><?php echo $totACT;?></b> (<b><?php echo number_format($percent1);?></b>%)
                    </button> 
                </div>
            </div>          
            <div class="row justify-content-center" style="margin-top:10px">
                <div>
                    <button class="btn btn-danger" style="width:100%;background:#c24642;font-size:30px">
                        Down: <b><?php echo $totDWN;?></b> (<b><?php echo number_format($percent2);?></b>%)
                    </button>
                </div>
            </div>
            <div class="row justify-content-center" style="margin-top:10px;margin-bottom:10px">
                <div>
                    <a rel="facebox" href="reportModal2.php">
                        <button class="btn btn-success" style="width:100%;font-size:30px;opacity:.7">
                            <b class="text-light">Total Sites: <?php echo $total;?></b>
                        </button>
                    </a>
                </div>
            </div>
        </div>

        <!-- Offline Sites -->
        <div class="col-lg-3" style="margin-top:35px">
            <div style="border:1px solid #bbb;border-radius:5px;padding:5px;background:#bbb;color:red"><b>OFFLINE SITES (<?php echo $totDWN;?>)</b></div>
            <div style="border:1px solid #bbb;border-radius:5px;padding:10px;background:#fff;color:red;text-align:left;height:543px;overflow:auto;margin-bottom:30px">
            <?php 
                $i=1;
                $ex=$link->query("SELECT * FROM sites WHERE status=0 ORDER BY mcode");
                while($rs=mysqli_fetch_assoc($ex)){
                    echo "<div>$i. <a href=\"site_details.php?sites=".(int)$rs["sid"]."\">".$rs["mcode"]." ".$rs["barangay"]." ".$rs["place"]."</a></div>";
                    $i++;
                }
            ?>
            </div>
        </div>
    </div>
</div>
</main>

<!-- Auto refresh after 5 minutes -->
<script>
	setTimeout(function(){
		window.location.reload();
	}, 300000);
</script>

<!-- Countdown timer -->
<script>
	function countdown(elementName, minutes, seconds){
	  var element, endTime, hours, mins, msLeft, time;
	  function twoDigits(n){ return (n <= 9 ? "0" + n : n); }
	  function updateTimer(){
		msLeft = endTime - (+new Date);
		if (msLeft < 1000) {
		  element.innerHTML = "00:00";
		} else {
		  time = new Date(msLeft);
		  hours = time.getUTCHours();
		  mins = time.getUTCMinutes();
		  element.innerHTML = (hours ? hours + ':' + twoDigits(mins) : mins) + ':' + twoDigits(time.getUTCSeconds());
		  setTimeout(updateTimer, time.getUTCMilliseconds() + 500);
		}
	  }
	  element = document.getElementById(elementName);
	  endTime = (+new Date) + 1000 * (60*minutes + seconds) + 500;
	  updateTimer();
	}
	countdown("countdown", 5, 0);
</script>

<?php require("footer.php");?>

</body>
</html>
