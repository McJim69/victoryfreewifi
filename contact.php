<?php 
	require("connect.php");
	require("header.php");
	require("webform.php")
?>

<body>

<?php require("menunav.php");?>

<script>setActive("contact");</script>

<main id="main"><br><br>

    <!-- ======= Contact Section ======= -->
    <section id="contact" class="contact" style="background:linear-gradient(rgba(255, 0, 0, 0.1), rgba(255, 0, 0, 0.2));" >
      <div class="container" data-aos="fade-up" style="min-height:435px">
        <div class="section-title">
          <h2>Contact Us</h2>
        </div>

        <div class="row mt-1 d-flex justify-content-end" data-aos="fade-right" data-aos-delay="100">

          <div class="col-lg-5">
            <div class="info">
              <div class="address">
                <i class="icofont-google-map bg-danger"></i>
                <h4>Postal Address:</h4>
                <p>Urro Street, Santo Nino, Pagadian City<br>7016 Zamboanga del Sur, Philippines</p>
              </div>

              <div class="email">
                <i class="icofont-envelope bg-danger"></i>
                <h4>Email Address:</h4>
                <p>admin@victoryfreewifi.net</p>
              </div>

              <div class="phone">
                <i class="icofont-phone bg-danger"></i>
                <h4>Phone Number:</h4>
                <p>+639776848642</p>
              </div>

            </div>

          </div>

          <div class="col-lg-6 mt-5 mt-lg-0" data-aos="fade-left" data-aos-delay="100">

            <form action="https://api.web3forms.com/submit" method="post" role="form">
			  <input type="hidden" name="access_key" value="<?php echo WEBFORM_API;?>">
              
			  <div class="row">
                <div class="col-md-6 form-group">
                  <input type="text" name="name" class="form-control" style="background:#eee" placeholder="Your Name" required />
                  <div></div>
                </div>
                <div class="col-md-6 form-group mt-3 mt-md-0">
                  <input type="email" class="form-control" name="email" style="background:#eee" placeholder="Your Email" required />
                  <div></div>
                </div>
              </div>
              <div class="form-group mt-3">
                <input type="text" class="form-control" name="subject" style="background:#eee" placeholder="Subject" required />
                <div></div>
              </div>
              <div class="form-group mt-3">
                <textarea class="form-control" name="message" rows="5" style="background:#eee" placeholder="Message" required ></textarea>
                <div></div>
				<input type="checkbox" name="botcheck" class="hidden" style="display: none;">
				<input type="hidden" name="redirect" value="https://victoryfreewifi.net/web/thanks.php">
              </div>
              <div class="mb-3">
                <div class="loading"></div>
              </div>
              <div class="text-center"><button class="btn btn-danger" type="submit">Send Message</button></div>
            </form>

          </div>

        </div>

      </div>
    </section><!-- End Contact Section -->

</main><!-- End #main -->

<?php require("footer.php");?>

</body>

</html>