
<footer id="footer">
	<div class="container">
		<div class="row py-5 my-4">
			<div class="col-md-3 col-lg-3 mb-4 mb-lg-0">
				<h5 class="text-3 mb-3">About Us</h5>
				<p class="pr-1">Keep up on our always evolving product features and technology. Enter your e-mail address and subscribe to our newsletter.</p>
			</div>
			<div class="col-md-2 col-lg-2 mb-4 mb-lg-0">
				<h5 class="text-3 mb-3">Quick Links</h5>
				<div id="tweet" class="twitter" data-plugin-tweets data-plugin-options="{'username': 'oklerthemes', 'count': 2}">
					<ul>
						<li><a href="#">Link 1</a></li>
						<li><a href="#">Link 2</a></li>
						<li><a href="#">Link 3</a></li>
						<li><a href="#">Link 4</a></li>
						<li><a href="#">Link 5</a></li>
					</ul>
				</div>
			</div>
			<div class="col-md-3 col-lg-3 mb-4 mb-md-0">
				<div class="contact-details">
					<h5 class="text-3 mb-3">CONTACT US</h5>
					<ul class="list list-icons list-icons-lg">
						<li class="mb-1"><i class="far fa-dot-circle text-color-primary"></i><p class="m-0">234 Street Name, City Name</p></li>
						<li class="mb-1"><i class="fab fa-whatsapp text-color-primary"></i><p class="m-0"><a href="tel:8001234567">(800) 123-4567</a></p></li>
						<li class="mb-1"><i class="far fa-envelope text-color-primary"></i><p class="m-0"><a href="mailto:mail@example.com">mail@example.com</a></p></li>
					</ul>
				</div>
			</div>
			<div class="col-md-4 col-lg-4">
				<div class="row">
					<div class="col">
						<h5 class="text-3 mb-3">FOLLOW US</h5>
						<ul class="social-icons">
							<li class="social-icons-facebook"><a href="http://www.facebook.com/" target="_blank" title="Facebook"><i class="fab fa-facebook-f"></i></a></li>
							<li class="social-icons-twitter"><a href="http://www.twitter.com/" target="_blank" title="Twitter"><i class="fab fa-twitter"></i></a></li>
							<li class="social-icons-linkedin"><a href="http://www.linkedin.com/" target="_blank" title="Linkedin"><i class="fab fa-linkedin-in"></i></a></li>
						</ul>
					</div>
				</div>
				<div class="row mt-4">
					<div class="col">	
						<h5 class="text-6 text-transform-none font-weight-semibold text-color-light mb-4">Newsletter</h5>
						<p class="text-4 mb-1">Get all the latest informationon Sales and Offers.</p>
						<p class="text-4">Sign up for newsletter today.</p>
						<div class="alert alert-success d-none" id="newsletterSuccess">
							<strong>Success!</strong> You've been added to our email list.
						</div>
						<div class="alert alert-danger d-none" id="newsletterError"></div>
						<form id="newsletterForm" action="php/newsletter-subscribe.php" method="POST" class="mw-100" novalidate="novalidate">
							<div class="input-group input-group-rounded">
								<input class="form-control form-control-sm bg-light px-4 text-3" placeholder="Email Address..." name="newsletterEmail" id="newsletterEmail" type="text">
								<span class="input-group-append">
									<button class="btn btn-primary text-color-light text-2 py-3 px-4" type="submit"><strong>SUBSCRIBE!</strong></button>
								</span>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="footer-copyright">
		<div class="container py-2">
			<div class="row py-4">
				<div class="col-lg-1 d-flex align-items-center justify-content-center justify-content-lg-start mb-2 mb-lg-0">
					<a href="index.php" class="logo pr-0 pr-lg-3">
						<img alt="Easy2Hire" width="82" height="40" src="img/logo.png">
					</a>
				</div>
				<div class="col-lg-7 d-flex align-items-center justify-content-center justify-content-lg-start mb-4 mb-lg-0">
					<p>© Copyright 2018. All Rights Reserved.</p>
				</div>
				<div class="col-lg-4 d-flex align-items-center justify-content-center justify-content-lg-end">
					<nav id="sub-menu">
						<ul>
							<li><i class="fas fa-angle-right"></i><a href="#" class="ml-1 text-decoration-none"> FAQ's</a></li>
							<li><i class="fas fa-angle-right"></i><a href="#" class="ml-1 text-decoration-none"> Sitemap</a></li>
							<li><i class="fas fa-angle-right"></i><a href="contact_us.php" class="ml-1 text-decoration-none"> Contact Us</a></li>
						</ul>
					</nav>
				</div>
			</div>
		</div>
	</div>
</footer>
</div>

<!-- Vendor -->
<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/jquery.appear/jquery.appear.min.js"></script>
<script src="vendor/jquery.easing/jquery.easing.min.js"></script>
<script src="vendor/jquery.cookie/jquery.cookie.min.js"></script>
<script src="vendor/popper/umd/popper.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
<script src="vendor/common/common.min.js"></script>
<script src="vendor/jquery.validation/jquery.validate.min.js"></script>
<script src="vendor/jquery.easy-pie-chart/jquery.easypiechart.min.js"></script>
<script src="vendor/jquery.gmap/jquery.gmap.min.js"></script>
<script src="vendor/jquery.lazyload/jquery.lazyload.min.js"></script>
<script src="vendor/isotope/jquery.isotope.min.js"></script>
<script src="vendor/owl.carousel/owl.carousel.min.js"></script>
<script src="vendor/magnific-popup/jquery.magnific-popup.min.js"></script>
<script src="vendor/vide/jquery.vide.min.js"></script>

<!-- Theme Base, Components and Settings -->
<script src="js/theme.js"></script>

<!-- Current Page Vendor and Views -->
<script src="vendor/rs-plugin/js/jquery.themepunch.tools.min.js"></script>
<script src="vendor/rs-plugin/js/jquery.themepunch.revolution.min.js"></script>

<!-- Revolution Slider Addon - Typewriter -->
<script type="text/javascript" src="vendor/rs-plugin/revolution-addons/typewriter/js/revolution.addon.typewriter.min.js"></script>

<!-- Counter -->
<script src="vendor/counter/waypoints.min.js"></script>
<script src="vendor/counter/counterup.min.js"></script>
<!-- Theme Custom -->
<script src="js/custom.js"></script>

<!-- Theme Initialization Files -->
<script src="js/theme.init.js"></script>

</body>
</html>
