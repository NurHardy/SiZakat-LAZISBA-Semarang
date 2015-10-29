<?php 
/**
 * Antarmuka Login
 */

	session_start();
	include "component/config/koneksi.php";
	
	// Jika sudah login, ngapain masuk halaman form login?
	if (isset($_SESSION['username'])) {
		header("Location: main.php");
		exit;
	} else if (isset($_GET['s'])) {
		$actionWord = $_GET['s'];
		if ($actionWord == "recover") {
			include FCPATH."/file/user/resetpass/router_resetpassword.php";
		}
	}
	
	
	if (!empty($_GET['next'])) $nextUrl = $_GET['next'];
	$errorMessage = null;
	if(ISSET($_POST['siz_submit'])){
		$username = trim($_POST['siz_uname']);
		$username = mysqli_real_escape_string($mysqli, $username);
		$password = sha1(sha1(md5($_POST['siz_upass'])));
		
		$loginQuery = sprintf("SELECT * FROM user WHERE username='%s' AND password='%s'",
				$username, $password);
		
		$sql = mysqli_query($mysqli, $loginQuery);
		$csql = mysqli_num_rows($sql);
	
		if($csql >= 1){
			$r = mysqli_fetch_array($sql);
			/*inisialisasi sesi*/
			$_SESSION['level']		= $r['level'];
			$_SESSION['username']	= $r['username'];
			$_SESSION['iduser']		= $r['id_user'];
			if($r['level'] == '3'){
				$_SESSION['wil_bus'] = $r['wilayah_bus'];
			}
			// === Tambahan untuk modul perencanaan ==
			$_SESSION['siz_divisi']=$r['divisi'];
				
			// === Akhir tambahan ====================
			if (empty($nextUrl)) {
				$nextUrl = "main.php";
			}
			header("location: ".$nextUrl);
			exit;
		}else{
			$errorMessage = "Username atau password salah.";
		}
	}
?>
<html lang="en">
    <head>
        <title>SiZakat LAZISBA</title>
		<meta charset="UTF-8" />
		<link rel="icon" href="images/favicon.ico">
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<link rel="stylesheet" href="css/bootstrap.min.css" />
        <link rel="stylesheet" href="css/bootstrap-glyphicons.css" />
        <link rel="stylesheet" href="css/unicorn.login.css" />
        <link rel="stylesheet" href="css/sizakat.css" />
    </head>
    <body class="siz-login">
        <div id="container">
            <div id="logo">
                <img src="images/logo-white.png" alt="Logo LAZISBA" style="width: 100px;" />
            </div>
            <?php
	if (!empty($errorMessage)) {
		echo "<div class=\"alert alert-danger\">\n";
		echo "<span class=\"glyphicon glyphicon-alert\"></span> ".$errorMessage;
		echo "</div>\n";
	} else {
		
	}?>
            <div id="loginbox">
                <form id="loginform" method='post' action="<?php
                	echo "login.php";
 					if (!empty($nextUrl))
 						echo "?next=".urlencode($nextUrl); ?>">

					<p>Silakan login untuk melanjutkan.</p>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                        <input class="form-control" required type="text" placeholder="Username" name="siz_uname"/>
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                        <input class="form-control" required type="password" placeholder="Password" name="siz_upass" />
                    </div>
                      
                    <hr />
                    <div class="form-actions">
                       <div style="margin-bottom: 10px;">
	                       <div class='pull-left'><a href='index.php'>
	                       	<span class="glyphicon glyphicon-chevron-left"></span>&nbsp;Kembali</a></div>
	                       <div class="pull-right"><a href='index.php' class="grey">Lupa password?</a></div>
	                       <div class="divclear"></div>
                       </div>
                       
                       <button type="submit" class="btn btn-primary btn-block" name="login">
                       	<span class="glyphicon glyphicon-lock"></span>&nbsp;Login
                       </button>
                    </div>
					<input type="hidden" name="siz_submit" value="login" />
                </form>
                <p>Kontak: <a href="mailto:admin@siz.lazisba.org">admin@siz.lazisba.org</a></p>
				<div class="divclear"></div>
            </div>
        </div>
        
        <script src="js/jquery.min.js"></script>  
        <script src="js/unicorn.login.js"></script> 
    </body>
</html>
