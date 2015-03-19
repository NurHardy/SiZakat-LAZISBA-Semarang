<?php
	session_start();
	include "component/config/koneksi.php";
	
	$ops = $mysqli->query("SELECT * FROM opsi WHERE name='general'");
	$f = $ops->fetch_array(MYSQLI_ASSOC);
	$s = explode('#',$f['value']);
	
	function setActiveMenu($link){
		$get = @$_GET['s'];
		if($link == $get){
			echo "class='active'";
		}
	}
	
	function setActiveOpen($link){
		$get = @$_GET['s'];
		if(in_array($get,$link)){
			echo "class='active open'";
		}else{
			echo "class='submenu'";
		}
	}
	

?>
<!DOCTYPE html>
<!--[if IE 7 ]><html class="ie ie7" lang="en"><![endif]-->
<!--[if IE 8 ]><html class="ie ie8" lang="en"><![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--><html lang="en"><!--<![endif]-->
<head>

<!-- Basic Page Needs
================================================== -->
<meta charset="utf-8">
<title>LAZISBA</title>

<!-- Mobile Specific
================================================== -->
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

<!-- CSS
================================================== -->
<link rel="stylesheet" type="text/css" href="css/style.css">
<link rel="stylesheet" type="text/css" href="css/boxed.css" id="layout">


<!-- Java Script
================================================== -->
<script src="js/jquery.js"></script>
<script src="js/custom.js"></script>


<!-- Styles Switcher
================================================== -->
<link rel="stylesheet" type="text/css" href="css/switcher.css">
<script src="js/switcher.js"></script>

</head>
<body>

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
					<li><i class="mini-ico-off"></i><a href="login.php">Login</a></li>
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
	<?php 
		
		if(ISSET($_GET['s'])){
			if($_GET['s'] == 'info'){
				include "info_lazis.php";
			}else if($_GET['s'] == 'ganti_pass'){
				include "form_ganti_pass.php";
			}else if($_GET['s'] == 'daftar_penyaluran'){
				include "daftar_penyaluran.php";
			}else if($_GET['s'] == 'detail_penyaluran'){
				include "detail_penyaluran.php";
			}else if($_GET['s'] == 'detail_info'){
				include "detail_info.php";
			}else if($_GET['s'] == 'keuangan'){
				include "keuangan.php";
			}
		}
		else{
			
			
			$q2 = $mysqli->query("SELECT * FROM informasi i, user u WHERE u.id_user= i.id_user");
			$p2 = $q2->fetch_array(MYSQLI_ASSOC);
					
			echo "	<div class='post post-page'>
						<div class='post-img picture'>
							<a href='images/baiturrahman.jpg' rel='image' title='Baiturrahman Semarang'>
								<img src='images/baiturrahman.jpg' alt=''/>
								<div class='image-overlay-zoom'></div>
							</a>
						</div>
					";
			//dari sini
			$i=1;
			$q1 = $mysqli->query("SELECT * FROM informasi WHERE status = '2' LIMIT 0,2");
			while($p1 = $q1->fetch_array(MYSQLI_ASSOC)){
				$is= "";
				$is = explode(" ",$p1['isi']);
				
				$fa = "";
				
				if(count($is) >= 50){
					for($i=0;$i<50;$i++){
						$fa .= $is[$i]." ";
					}
				}else{
					$fa = implode(" ",$is);
				}
			
				if(count($is) >= 50){
					echo "	
						<a href='#' class='post-icon standard'></a>
						<div class='post-content'>
								<div class='post-title'>
									<h2>".$p1['judul']."</h2>
								</div>
								
								<div class='post-meta'>
									<span>
										<i class='mini-ico-user'></i>By <a href='#'>".$p2['nama']."</a>
									</span> 
									<span><i class='mini-ico-comment'></i></span>
								</div>
								
								<div class='post-description' style='text-align:justify;'>
									<p>$fa .... 
										<a href='index.php?s=detail_info&id=$p1[id_informasi]' >
											<i>read more</i>
										</a>
									</p>
								</div>
						</div>";
						
				}else{
					echo "	
						<a href='#' class='post-icon standard'></a>
						<div class='post-content'>
							<div class='post-title'><h2>".$p1['judul']."</h2></div>
							<div class='post-meta'><span><i class='mini-ico-user'></i>By <a href='#'>".$p2['nama']."</a></span> <span><i class='mini-ico-comment'></i></span></div>
							<div class='post-description' style='text-align:justify;'>
								<p>$fa </p>
							</div>
						</div>";
				
				}
				$i++;
				//smpe disini
			}			
				
				echo"</div>";
		}
	?>
</div>
<!-- 960 Container / End -->
</div>
<!-- Wrapper / End -->


<!-- Footer
================================================== -->

<!-- Footer Start -->
<div id="footer">
	<!-- 960 Container -->
	<div class="container">
	
		<!-- About -->
		<div class="five columns">
			<div class="footer-headline"><h4>About Us</h4></div>
			<p><?php echo $s[5]; ?></p>
		</div>
		
		<!-- Useful Links -->
		<div class="five columns">
			<div class="footer-headline"><h4>Link</h4></div>
			<ul class="links-list">
				<?php
					$query = $mysqli->query("SELECT * FROM opsi WHERE name = 'link'");
					$d= $query->fetch_array(MYSQLI_ASSOC);
					
					
					$ket = nl2br(trim($d['value']));
					$pres = explode('<br />',$ket); 

					for($i=0;$i<count($pres);$i++){
						$dt = explode('|',$pres[$i]);
						echo"<li><a href=\"".$dt[1]."\">".$dt[0]."</a></li>";
							
					}
				?>
			</ul>
		</div>
		<div class="five columns">
			<div class="footer-headline"><h4>Peta Lokasi Lazisba</h4></div>
			<iframe width="100%" height="150%" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.co.id/maps?f=q&amp;source=s_q&amp;hl=id&amp;geocode=&amp;q=simpang+lima,+semarang&amp;aq=&amp;sll=-6.914709,113.58324&amp;sspn=6.583894,10.821533&amp;ie=UTF8&amp;hq=simpang+lima,&amp;hnear=Semarang,+Jawa+Tengah&amp;t=m&amp;fll=-6.989133,110.425193&amp;fspn=0.006432,0.010568&amp;st=107658235038825379151&amp;rq=1&amp;ev=zi&amp;split=1&amp;ll=-6.989069,110.422516&amp;spn=0.000799,0.00228&amp;z=18&amp;output=embed"></iframe><br /><small><a href="https://maps.google.co.id/maps?f=q&amp;source=embed&amp;hl=id&amp;geocode=&amp;q=simpang+lima,+semarang&amp;aq=&amp;sll=-6.914709,113.58324&amp;sspn=6.583894,10.821533&amp;ie=UTF8&amp;hq=simpang+lima,&amp;hnear=Semarang,+Jawa+Tengah&amp;t=m&amp;fll=-6.989133,110.425193&amp;fspn=0.006432,0.010568&amp;st=107658235038825379151&amp;rq=1&amp;ev=zi&amp;split=1&amp;ll=-6.989069,110.422516&amp;spn=0.000799,0.00228&amp;z=18" style="color:#0000FF;text-align:left">Lihat Peta Lebih Besar</a></small>
		</div>
		
		<!-- Footer / Bottom -->
		<div class="sixteen columns">
			<div id="footer-bottom">
				© Copyright <?php echo date('Y');?> by <a href="#">Takmir & Lazisba Online</a>. All rights reserved.
				<div id="scroll-top-top"><a href="#"></a></div>
			</div>
		</div>

	</div>
	<!-- 960 Container / End -->

</div>
<!-- Footer / End -->


</body>
</html>