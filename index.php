<?php

	define('SELF', pathinfo(__FILE__, PATHINFO_BASENAME));
	define('FCPATH', str_replace(SELF, '', __FILE__));
	define('COMPONENT_PATH', FCPATH."component");

	session_start();
	require_once COMPONENT_PATH."/config/koneksi.php";
	require_once COMPONENT_PATH."/libraries/injection.php";
	
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
	

	// Start buffering...
	ob_start();
	
	// This is not optimized yet
	if (($_GET['s'] == 'login')||($_GET['s'] == 'reset')) {
		$isSimplePage = true;
		if ($_GET['s']=='login') {
			$pageTitle = "Login";
		} else {
			$pageTitle = "Reset Password";
		}
	}
	include COMPONENT_PATH."/skin/frontend_header.php";
		
		if(ISSET($_GET['s'])){
			if($_GET['s'] == 'info'){
				include "component/file/frontend/info_lazis.php";
			}else if($_GET['s'] == 'login'){
				include COMPONENT_PATH."/file/user/resetpass/form_login.php";
			}else if($_GET['s'] == 'reset'){
				include COMPONENT_PATH."/file/user/resetpass/router_resetpassword.php";
			}else if($_GET['s'] == 'daftar_penyaluran'){
				include "component/file/frontend/daftar_penyaluran.php";
			}else if($_GET['s'] == 'detail_penyaluran'){
				include "component/file/frontend/detail_penyaluran.php";
			}else if($_GET['s'] == 'detail_info'){
				include "component/file/frontend/detail_info.php";
			}else if($_GET['s'] == 'keuangan'){
				include "component/file/frontend/keuangan.php";
			}
		} else {
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
	
	include COMPONENT_PATH."/skin/frontend_footer.php";
	
	ob_flush();
	
