<!DOCTYPE html>
<html>
	<head>
		<?php include 'templates/config.php';?>
		<!-- Basic -->
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">	

		<title>Easy2Hire</title>	
		
		<!-- Favicon -->
		<link rel="shortcut icon" href="img/favicon.ico" type="image/x-icon" />
		<link rel="apple-touch-icon" href="img/apple-touch-icon.png">

		<!-- Mobile Metas -->
		<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1.0, shrink-to-fit=no">

		<!-- Web Fonts  -->
		<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800%7CShadows+Into+Light%7CPlayfair+Display:400" rel="stylesheet" type="text/css">

		<!-- Vendor CSS -->
		<link rel="stylesheet" href="vendor/bootstrap/css/bootstrap.min.css">
		<link rel="stylesheet" href="vendor/fontawesome-free/css/all.min.css">
		<link rel="stylesheet" href="vendor/animate/animate.min.css">
		<link rel="stylesheet" href="vendor/simple-line-icons/css/simple-line-icons.min.css">
		<link rel="stylesheet" href="vendor/owl.carousel/assets/owl.carousel.min.css">
		<link rel="stylesheet" href="vendor/owl.carousel/assets/owl.theme.default.min.css">
		<link rel="stylesheet" href="vendor/magnific-popup/magnific-popup.min.css">

<!-- Revolution Slider Addon - Typewriter -->
		<link rel="stylesheet" type="text/css" href="vendor/rs-plugin/revolution-addons/typewriter/css/typewriter.css" />

		<!-- Theme CSS -->
		<link rel="stylesheet" href="css/theme.css">
		<link rel="stylesheet" href="css/theme-elements.css">
		<link rel="stylesheet" href="css/theme-blog.css">
		<link rel="stylesheet" href="css/theme-shop.css">

		<!-- Current Page CSS -->
		<link rel="stylesheet" href="vendor/rs-plugin/css/settings.css">
		<link rel="stylesheet" href="vendor/rs-plugin/css/layers.css">
		<link rel="stylesheet" href="vendor/rs-plugin/css/navigation.css">
		
		<!-- Demo CSS -->
		<style>
			.img-thumbnail .img-fluid{
				max-width: 300px!important;
				max-height: 100px!important;
			}
			div.tp-caption h3.tp-caption{
				font-family: Courier, Monaco, monospace!important;
				margin-top: 3rem!important;
			}
		</style>

		<!-- Skin CSS -->
		<link rel="stylesheet" href="css/skins/skin.css"> 
		<!-- Theme Custom CSS -->
		<link rel="stylesheet" href="css/custom.css">
		
		<link rel="stylesheet" href="css/<?php echo str_replace(".php","",$PageName); ?>.css">
		<!-- Head Libs -->
		<script src="vendor/modernizr/modernizr.min.js"></script>

	</head>
	<?php if ($PageName == "index.php") {
		$ClassName = "home_page";
	} else {
		$ClassName = "inner_page";
	} ?>
	<body>
		<div class="body <?php echo $ClassName ?>">
			<header id="header" class="header-transparent header-effect-shrink" data-plugin-options="{'stickyEnabled': true, 'stickyEffect': 'shrink', 'stickyEnableOnBoxed': true, 'stickyEnableOnMobile': true, 'stickyChangeLogo': true, 'stickyStartAt': 30, 'stickyHeaderContainerHeight': 70}">
				<div class="header-body border-top-0 bg-dark box-shadow-none">
					<div class="header-container header-container-height-sm container container-lg">
						<div class="header-row">
							<div class="header-column">
								<div class="header-row">
									<div class="header-logo">
										<a href="<?php echo SITE_PATH ?>">
											<img alt="Easy2Hire" width="82" height="40" src="img/logo.png">
										</a>
									</div>
								</div>
							</div>
							<div class="header-column justify-content-end">
								<div class="header-row">
									<div class="header-nav header-nav-links header-nav-dropdowns-light header-nav-light-text order-2 order-lg-1">
										<div class="header-nav-main header-nav-main-mobile-dark header-nav-main-square header-nav-main-dropdown-no-borders header-nav-main-effect-2 header-nav-main-sub-effect-1">
											<nav class="collapse">
												<ul class="nav nav-pills" id="mainNav">
													<li>
														<a class="dropdown-item dropdown-toggle" href="<?php echo SITE_PATH ?>">
															Home
														</a>
													</li>
													<li class="dropdown">
														<a class="dropdown-item dropdown-toggle" href="<?php echo Overview ?>">
															Platform
														</a>
														<ul class="dropdown-menu">
															<li class="submenu">
																<a class="dropdown-item" href="<?php echo Overview ?>">Overview</a>
															</li>
															<li class="submenu">
																<a class="dropdown-item" href="<?php echo Dashboard ?>">Dashboard</a>	
															</li>
															<li class="submenu">
																<a class="dropdown-item" href="<?php echo Features ?>">Features</a>
															</li>
															<li class="submenu">
																<a class="dropdown-item" href="<?php echo Modules ?>">Modules</a>
															</li>
														</ul>
													</li>
													<li class="dropdown">
														<a class="dropdown-item dropdown-toggle" href="<?php echo Time_Saver ?>">
															Benefits
														</a>
														<ul class="dropdown-menu">
															<li class="submenu">
																<a class="dropdown-item" href="<?php echo Time_Saver ?>">Time Saver</a>
															</li>
															<li class="submenu">
																<a class="dropdown-item" href="<?php echo Transparent ?>">Transparent</a>	
															</li>
															<li class="submenu">
																<a class="dropdown-item" href="<?php echo Data_Analytics ?>">Data Analytics</a>
															</li>
														</ul>
													</li>
													<li>
														<a class="dropdown-item dropdown-toggle" href="<?php echo Coming_Soon ?>">
															Pricing
														</a>
													</li>
													<li>
														<a class="dropdown-item dropdown-toggle" href="<?php echo About_us ?>">
															About Us
														</a>
													</li>
													<li>
														<a class="dropdown-item dropdown-toggle" href="<?php echo Coming_Soon ?>">
															Clients
														</a>
													</li>
													<li>
														<a class="dropdown-item dropdown-toggle" href="<?php echo Coming_Soon ?>">
															Support
														</a>
													</li>
													<li>
														<a class="dropdown-item dropdown-toggle" href="<?php echo Coming_Soon ?>">
															Resources
														</a>
													</li>
													<li>
														<a class="dropdown-item dropdown-toggle" href="<?php echo Careers ?>">
															Careers
														</a>
													</li>
													<li>
														<a class="dropdown-item dropdown-toggle" href="<?php echo contact ?>">
															Contact
														</a>
													</li>													
												</ul>
											</nav>
										</div>
										<button class="btn header-btn-collapse-nav" data-toggle="collapse" data-target=".header-nav-main nav">
											<i class="fas fa-bars"></i>
										</button>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</header>
		<!-- </div> -->

