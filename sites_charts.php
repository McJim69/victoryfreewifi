<?php 
	$total = $allBAR;
	$brgys = $totBAR;
	$percent1 = ($brgys / $total) * 100;
	$percent2 = 100 - ($percent1);
	$dataPoints1 = array(
		array("label" => "Accomplishment", "y" => $percent1),
		array("label" => "Target Remaining", "y" => $percent2)
	);

	$qryACT=$link->query("SELECT COUNT(status) FROM sites WHERE status=1");
	$aryACT=mysqli_fetch_array($qryACT);
	$totACT=number_format($aryACT[0]);

	$qryDWN=$link->query("SELECT COUNT(status) FROM sites WHERE status=0");
	$aryDWN=mysqli_fetch_array($qryDWN);
	$totDWN=number_format($aryDWN[0]);	

	$sites = $totACT+$totDWN;
	$percent3 = ($totACT / $sites) * 100;
	$percent4 = ($totDWN / $sites) * 100;
	$dataPoints2 = array(
		array("label" => "Active Sites", "y" => $percent3),
		array("label" => "Downed Sites", "y" => $percent4)
	);
?>

<script>
	window.onload = function () {
		var chart1 = new CanvasJS.Chart("chartContainer1", {
			animationEnabled: true,
			title: { text: "Out of <?php echo $allBAR;?> Barangays" },
			data: [{
				type: "doughnut",
				yValueFormatString: "#,##0.00\"%\"",
				indexLabel: "{label} ({y})",
				dataPoints: <?php echo json_encode($dataPoints1, JSON_NUMERIC_CHECK); ?>
			}]
		});
		var chart2 = new CanvasJS.Chart("chartContainer2", {
			animationEnabled: true,
			title: { text: "Site ONLINE Status" },
			data: [{
				type: "doughnut",
				yValueFormatString: "#,##0.00\"%\"",
				indexLabel: "{label} ({y})",
				dataPoints: <?php echo json_encode($dataPoints2, JSON_NUMERIC_CHECK); ?>
			}]
		});
		chart1.render();
		chart2.render();
	}
</script>

<!--
CHARTS:
	type: "area",
	type: "bar",
	type: "bubble",
	type: "column",
	type: "doughnut",
	type: "stock",
	type: "spline",
	type: "pie",
-->