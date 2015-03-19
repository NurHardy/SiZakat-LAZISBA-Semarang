<?php
	session_start();
		include "../config/koneksi.php";
		include "../libraries/injection.php";
	
	if(ISSET($_POST['save'])){
	
			
		if($_POST['password'] == ""){
			$pas = "";
		}else{
			$pas = "password = sha1(sha1(md5('$_POST[password]')))";
		}
		
		$query = mysql_query("UPDATE user SET
								nama = '$_POST[nama]',
								alamat = '$_POST[alamat]',
								hp = '$_POST[telp]',
								pj = '$_POST[pj]',
								username = '$_POST[username]',
								$pas
								WHERE id_user = '$_SESSION[iduser]'
								");
			
			if($query){
					$_SESSION['success'] = "Data Pribadi Berhasil Diubah";
					echo "<meta http-equiv=\"refresh\" content=\"0; url=../../main.php?s=ubah_akun_ukm&id=$_SESSION[iduser]\">";
					echo mysql_error();
		}else{
				$_SESSION['error'] = "Proses Gagal, Terjadi Kesalahan : ".mysql_error();
				echo "<meta http-equiv=\"refresh\" content=\"0; url=../../main.php?s=ubah_akun_ukm&id=$_SESSION[iduser]\">";
			}
		}else{
			$_SESSION['error'] = "Proses Dibatalkan";
			echo "<meta http-equiv=\"refresh\" content=\"0; url=../../main.php?s=ubah_akun_ukm&id=$_SESSION[iduser]\">";
		}
	
?>