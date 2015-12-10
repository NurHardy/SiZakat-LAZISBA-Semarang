<?php 
/**
 * Antarmuka Login
 */

	// Jika sudah login, ngapain masuk halaman form login?
	if (isset($_SESSION['username'])) {
		ob_end_clean ( );
		header("Location: main.php");
		exit;
	}
	
	if (!empty($_GET['next'])) $nextUrl = $_GET['next'];
	$errorMessage = null;
	if(ISSET($_POST['siz_submit'])){
		// Load helper user
		require_once(COMPONENT_PATH."/libraries/helper_user.php");
		
		$username = trim($_POST['siz_uname']);
		$purePassword = $_POST['siz_upass'];
		$passOK = false;
		
		$r = cek_user($username, 'username');
		if (!empty($r)) {
			$passOK = cek_user_password($purePassword, $r['password']);
		}
		
		if($passOK){
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
			ob_end_clean ( );
			header("location: ".$nextUrl);
			exit;
		}else{
			$errorMessage = "Username atau password salah.";
		}
	}

?>
<div class="siz-login">
	<div id="container">
		<div id="logo">
			<img src="images/logo-white.png" alt="Logo LAZISBA"
				style="width: 100px;" />
		</div><?php
if (! empty ( $errorMessage )) {
	echo "<div class=\"alert alert-danger\">\n";
	echo "<span class=\"glyphicon glyphicon-alert\"></span> " . $errorMessage;
	echo "</div>\n";
}
?>
		<noscript>
			<div class="alert alert-danger">
				<span class="glyphicon glyphicon-alert"></span>Beberapa komponen sistem akan tidak
					berfungsi karena JavaScript tidak didukung. Pastikan fitur JavaScript telah diaktifkan.
			</div>
		</noscript>
            <div id="loginbox">
			<form id="loginform" method='post'
				action="<?php
				echo "index.php?s=login";
				if (! empty ( $nextUrl ))
					echo "&amp;next=" . urlencode ( $nextUrl );
				?>">

				<p class="form-instruction">Silakan login untuk melanjutkan.</p>
				<div class="input-group">
					<span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
					<input class="form-control" required type="text"
						placeholder="Username" name="siz_uname" />
				</div>
				<div class="input-group">
					<span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
					<input class="form-control" required type="password"
						placeholder="Password" name="siz_upass" />
				</div>

				<hr />
				<div class="form-actions">
					<div style="margin-bottom: 10px;">
						<div class='pull-left'>
							<a href='index.php'> <span
								class="glyphicon glyphicon-chevron-left"></span>&nbsp;Kembali
							</a>
						</div>
						<div class="pull-right">
							<a href='index.php?s=reset' class="grey">Lupa password?</a>
						</div>
						<div class="divclear"></div>
					</div>

					<button type="submit" class="btn btn-primary btn-block"
						name="login">
						<span class="glyphicon glyphicon-lock"></span>&nbsp;Login
					</button>
				</div>
				<input type="hidden" name="siz_submit" value="login" />
			</form>
			<p>
				Kontak: <a href="mailto:admin@siz.lazisba.org">admin@siz.lazisba.org</a>
			</p>
			<div class="divclear"></div>
		</div>
	</div>
</div>