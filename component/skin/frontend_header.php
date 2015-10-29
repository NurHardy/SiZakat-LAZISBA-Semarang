<!DOCTYPE html>
<!--[if IE 7 ]><html class="ie ie7" lang="en"><![endif]-->
<!--[if IE 8 ]><html class="ie ie8" lang="en"><![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--><html lang="en"><!--<![endif]-->
<head>
	
	<!-- Basic Page Needs
	================================================== -->
	<meta charset="utf-8">
	<title><?php if (!empty($pageTitle)) echo $pageTitle; else echo "Untitled"; ?> - LAZISBA</title>
	<link rel="icon" href="images/favicon.ico">
	
	<!-- Mobile Specific
	================================================== -->
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	
	<!-- CSS
	================================================== -->
<?php if (empty($isSimplePage)) { //=== KHUSUS BUKAN HALAMAN LOGIN ======= ?>
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<link rel="stylesheet" type="text/css" href="css/boxed.css" id="layout">
<?php } else { //======================= ELSE IF ========================= ?>
	<link rel="stylesheet" href="css/bootstrap.min.css" />
	<link rel="stylesheet" href="css/bootstrap-glyphicons.css" />
	<link rel="stylesheet" href="css/unicorn.login.css" />
	<link rel="stylesheet" href="css/sizakat.css" />
<?php } //================== END IF ====================================== ?>
	
	<!-- Java Script
	================================================== -->
	<script src="js/jquery.js"></script>
	<script src="js/custom.js"></script>
	
	
	<!-- Styles Switcher
	================================================== -->
	<!-- <link rel="stylesheet" type="text/css" href="css/switcher.css">
	<script src="js/switcher.js"></script> -->
	
	</head>
	<body>
	
<?php if (empty($isSimplePage)) { //=== KHUSUS BUKAN HALAMAN LOGIN ======= ?>
	<!-- Wrapper Start -->
	<div id="wrapper">
	
	
	<!-- Header
	================================================== -->
	
	<!-- 960 Container -->
	<div class="container ie-dropdown-fix">
	
		<!-- Header -->
		<div id="header">
	
			<!-- Logo -->
			<div class="eight columns">
				<div id="logo">
					<a href="#"><img src="images/logo.png" alt="logo" style="width: 100px; position:absolute;top:-10px;" /></a>
					<div id="tagline" style='margin-left:120px;font-size:16px;font-weight:bolder;color:#000;'><?php echo $s[4]; ?></div>
					<div class="clear"></div>
				</div>
			</div>
	
			<!-- Social / Contact -->
			<div class="eight columns">
				
				<!-- Social Icons -->
				<ul class="social-icons">
					<li class="facebook"><a href="<?php echo $s[0]; ?>">Facebook</a></li>
					<li class="twitter"><a href="<?php echo $s[1]; ?>">Twitter</a></li>
				</ul>
				
				<div class="clear"></div>
				
				<!-- Contact Details -->
				<div id="contact-details">
					<ul>
						<li><i class="mini-ico-off"></i><a href="index.php?s=login">Login</a></li>
						<li><i class="mini-ico-envelope"></i><a href="<?php echo $s[2]; ?>">Mail</a></li>
						<li><i class="mini-ico-user"></i><?php echo $s[3]; ?></li>
					</ul>
				</div>
	
			</div>
	
		</div>
		<!-- Header / End -->
		
		<!-- Navigation -->
		<div class="sixteen columns">
			
			<div id="navigation">
				<ul id="nav">
					<li><a href="index.php">Beranda</a></li>
					<li><a href="#">Tentang Kami</a>
						<ul>
							<?php
								$q1 = $mysqli->query("SELECT * FROM informasi WHERE status = '1'");
								while($p1 = $q1->fetch_array(MYSQLI_ASSOC)){
									echo "<li><a href='index.php?s=detail_info&amp;id=".$p1['id_informasi']."'>".$p1['judul']."</a></li>";
								}
							?>
						</ul>
					</li>
					<?php //<!--<li><a href="index.php?s=daftar_penyaluran&q=3">Penyaluran Dana</a></li>-->?>
					<li><a href="#">Informasi</a>
						<ul>
							<li><a href='index.php?s=info&amp;status=2'>Artikel</a></li>
							<li><a href='index.php?s=info&amp;status=3'>Event/Acara</a></li>
						</ul>
					</li>
					<li><a href="index.php?s=keuangan">Laporan Keuangan</a></li>
					<?php
						/*if(empty($_SESSION['level'])){
							echo "
								<li><a href='login.php'>Login</a></li>
							";
						}
						else if($_SESSION['level'] == 1){
							echo "
								<li><a href='#'>Akun Muzakki</a>
									<ul>
									<li><a href='index.php?s=ganti_pass'>Ganti Password</a></li>
									<li><a href='#'>Riwayat Penyerahan Dana</a></li>
									</ul>
								</li>
								<li><a href='component/server/logout.php'>Logout</a></li>
							";
						}*/
					?>
					
				</ul>
	
				<!-- Search Form -->
				<div class="search-form" style='display:none;'>
					<form method="get" action="#">
						<input type="text" class="search-text-box" />
					</form>
				</div>
	
			</div> 
			<div class="clear"></div>
			
		</div>
		<!-- Navigation / End -->
	
	</div>
	<!-- 960 Container / End -->
	
	
	<!-- Content
	================================================== -->
	<!-- 960 Container -->
	<div class="container">
<?php } //================== END IF ============================== ?>