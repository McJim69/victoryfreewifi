<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="referrer" content="origin">
	<meta content="width=device-width, initial-scale=1.0" name="viewport">
	<title>Victory Free WiFi 4.0</title>
	<meta content="Victory Free WiFi" name="description">
	<meta content="Victory, Free WiFi" name="keywords">

	<!-- Favicons -->
	<link href="assets/img/favicon.png" rel="icon">
	<link href="assets/img/favicon.png" rel="apple-touch-icon">
	<link rel="stylesheet" href="assets/fonts/fonts.css">

	<!-- Base framework -->
	<link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

	<!-- Component plugins -->
	<link href="assets/vendor/owl.carousel/assets/owl.carousel.min.css" rel="stylesheet">
	<link href="assets/vendor/venobox/venobox.css" rel="stylesheet">
	<link href="assets/vendor/aos/aos.css" rel="stylesheet">
	<link href="assets/vendor/animate.css/animate.min.css" rel="stylesheet">

	<!-- Icon packs -->
	<link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
	<link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">
	<link href="assets/vendor/icofont/icofont.min.css" rel="stylesheet">
	<link href="awesome/css/all.min.css" rel="stylesheet"> <!-- Font Awesome -->

	<!-- Custom overrides -->
	<link href="assets/css/style.css" rel="stylesheet">

	<!-- Facebox Modal -->	
	<link href="facebox/facebox.css" media="screen" rel="stylesheet" type="text/css">	
	<script type="text/javascript" src="facebox/jquery-1.7.1.min.js"></script>
	<script src="facebox/facebox.js" type="text/javascript"></script>
	<script type="text/javascript">
		jQuery(document).ready(function($) {
		  $('a[rel*=facebox]').facebox({
			loadingImage : 'facebox/loading.gif',
			closeImage   : 'facebox/closelabel.png'
		  })
		})
	</script>					

	<!-- Chat Box -->	
	<script type="text/javascript">window.$crisp=[];window.CRISP_WEBSITE_ID="878e3b68-1d81-4862-b2a9-5b7c9f9b8d4f";(function(){ d=document;s=d.createElement("script"); s.src="https://client.crisp.chat/l.js"; s.async=1;d.getElementsByTagName("head")[0].appendChild(s);})(); </script>

	<!-- Inventory Header -->
	<script src="plugins/timepicker/bootstrap-timepicker.min.js"></script>	
	<script src="bower_components/sweetalert/sweetalert.js"></script>
	<link rel="stylesheet" href="dist/css/AdminLTE.min.css">
	<link rel="stylesheet" href="plugins/iCheck/all.css">
	<script src="plugins/iCheck/icheck.min.js"></script>

	<script src="chartjs/chartjs.min.js"></script>
	<script src="chartjs/canvasjs.min.js"></script>
	<script src="chartjs/chart.loader.js"></script>

	<script>
		if (window.XMLHttpRequest)
			xmlhttp=new XMLHttpRequest();
		else
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");				
		function conf(){
			return confirm("Are you sure??");
		}
		function jump(page){
			window.location=page;
		}
		function getID(id){
			return document.getElementById(id);
		}
		function setActive(id){
			getID(id).style.padding="5px";
			getID(id).style.color="#0759a2";			
			getID(id).style.marginTop="-4px";	
			getID(id).style.fontWeight="bold";			
			getID(id).style.borderRadius="5px";
			getID(id).style.background="#f6b024";
		}	
	</script>
</head>

<?php
	function jump($page){
		echo "<script>window.location='$page'</script>";
	}
	require("language.php");
?>
