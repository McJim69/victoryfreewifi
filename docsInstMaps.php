<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBLFwro7oy5NJvweRCRIBF1956rONihokE"></script>

<style>
	#map {
		height: 65vh;
		width: 100%;
	}
</style>

<script>
	function downloadFile(url) {
	  const xhr = new XMLHttpRequest();
	  const bar = document.querySelector('.progress-bar');
	  const container = document.getElementById('downloadProgress');

	  container.style.display = 'block';

	  xhr.open("GET", url, true);
	  xhr.responseType = "blob";

	  xhr.onprogress = function (e) {
		if (e.lengthComputable) {
		  const percent = Math.round((e.loaded / e.total) * 100);
		  bar.style.width = percent + '%';
		  bar.setAttribute('aria-valuenow', percent);
		  bar.textContent = percent + '%';
		}
	  };

	  xhr.onload = function () {
		if (xhr.status === 200) {
		  bar.textContent = 'Download complete!';   
		  const blob = xhr.response;
		  const link = document.createElement('a');
		  link.href = window.URL.createObjectURL(blob);
		  link.download = "assets/files/<?php echo $file;?>.zip"; 
		  link.click();
		} else {
		  bar.classList.add('bg-danger');
		  bar.textContent = 'Download Failed';
		}
	  };

	  xhr.onerror = function () {
		bar.classList.add('bg-danger');
		bar.textContent = 'Error during download';
	  };

	  xhr.send();
	}
</script>

<section id="portfolio-details" class="portfolio-details">
	<div class="container" style="margin-bottom:-40px">
		<div class="portfolio-details-container">
			<div style="margin:5px 0 15px 0;display:block">
				<b style="font-size:30px"><?php echo"$post";?></b> &nbsp; 
				KMZ file too large to display. 
				<a onclick="downloadFile('assets/files/<?php echo $file;?>.zip')" href="#"> 
				<b>DOWNLOAD</b> </a>it here to open in Google Earth.
			</div>
			<div id="map"></div>
		</div>
	</div>
</section>

<script>
	function initMap() {
		const map = new google.maps.Map(document.getElementById("map"), {
			center: { lat: 12.412520, lng: 121.447930 }, 
			zoom: 6,
			mapTypeId: "terrain",
		});
		const kmlLayer = new google.maps.KmlLayer({
			url: "https://victoryfreewifi.net/maps/<?php echo $file;?>.kml",
			map: map,
		});
	}
	window.onload = initMap;
		
	kmlLayer.addListener('status_changed', function () {
	  const status = kmlLayer.getStatus();
	  console.log("KML Layer Status:", status);
	  if (status !== google.maps.KmlLayerStatus.OK) {
		alert("Failed to load KMZ data. Please download it manually.");
	  }
	});
</script>