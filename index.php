<?php 
	require("connect.php");
	require("header_all.php");
?>

<body>

<?php require("menunav.php");?>

<script>setActive("home");</script>

<script>
	function printF(){		
		getID('topbar').style.display='none';
		getID('header').style.display='none';
		getID('main').style.display='none';
		getID('hero').style.display='none';
		getID('heroCarousel').style.display='none';
		getID('icon-boxes').style.display='none';
		getID('clients').style.display='none';
		getID('why-us').style.display='none';
		getID('cta').style.display='none';
	//	getID('faq').style.display='none';
		getID('blog').style.display='none';
		getID('footer').style.display='none';
		getID('print-header').style.display='block';
		getID('print-footer').style.display='block';
		$(".report-header").css("display","none");
		$(".back-to-top").css("display","none");
		$(".modal-header").css("display","none");
		$(".modal-footer").css("display","none");
		$(".close").css("display","none");
		$(".hid").css("display","none");

	window.print();
		getID('topbar').style.display='block';
		getID('header').style.display='block';
		getID('main').style.display='block';
		getID('hero').style.display='block';
		getID('heroCarousel').style.display='block';
		getID('icon-boxes').style.display='block';
		getID('clients').style.display='block';
		getID('why-us').style.display='block';
		getID('cta').style.display='block';
	//	getID('faq').style.display='block';
		getID('blog').style.display='block';
		getID('footer').style.display='block';
		getID('print-header').style.display='none';
		getID('print-footer').style.display='none';
		$(".report-header").css("display","block");
		$(".back-to-top").css("display","block");
		$(".modal-header").css("display","block");
		$(".modal-footer").css("display","block");
		$(".close").css("display","block");
		$(".hid").css("display","block");

	}
</script>

<main id="main">

<?php 
	require("mainslider.php");
	require("featbox.php");
	require("techlogo.php");
	require("rollout_front.php");
	require("whyvictory.php");
//	require("testimonials.php");
//	require("target_progress.php");
	require("contact_front.php");
	require("colaboration.php");
	require("footer.php");
?>

</main>

</body>

</html>