<?php
	session_start();
	
	if(ISSET($_POST['submit'])){
		include "../config/koneksi.php";
		include "../libraries/injection.php";
		$username = clear_injection($_POST['username']);
		$pass = clear_Injection($_POST['p1']);
		$kpass = clear_injection($_POST['p2']);
		$user = $_SESSION['iduser'];
		if($pass == $kpass){
			$query = mysql_query("UPDATE user SET username = '$username', password = sha1(sha1(md5('$pass'))) WHERE id_user = '$user'");
			if($query){
					$_SESSION['success'] = "Data akun UKM Berhasil Diubah";
					echo "<meta http-equiv=\"refresh\" content=\"0; url=../../main.php?s=gantipassukm\">";
			}else{
				$_SESSION['error'] = "Proses Gagal, Terjadi Kesalahan : ".mysql_error();
				echo "<meta http-equiv=\"refresh\" content=\"0; url=../../main.php?s=gantipassukm\">";
			}
		}
		else{
			$_SESSION['error'] = "Password yang anda masukkan tidak sesuai";
			echo "<meta http-equiv=\"refresh\" content=\"0; url=../../main.php?s=gantipassukm\">";
		}
	}
?>