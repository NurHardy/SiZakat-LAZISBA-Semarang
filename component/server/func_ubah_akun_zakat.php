<?php
	session_start();
	
	if(ISSET($_POST['save'])){
		include "../config/koneksi.php";
		//include "../libraries/injection.php";
		
		if($_POST['password'] == ""){
				$pas = "";
		}else{
				$pas = "password = sha1(sha1(md5('$_POST[password]'))),";
		}
		$query = mysql_query("UPDATE user SET
								username = '$_POST[user]',
								$pas
								nama = '$_POST[namalengkap]',
								tempat_lahir = '$_POST[tempatlahir]',
								tanggal_lahir = '$_POST[tanggallahir]',
								alamat = '$_POST[alamat]',
								kota = '$_POST[kota]',
								hp = '$_POST[hp]',
								email = '$_POST[email]'
								WHERE id_user  = '$_SESSION[iduser]'
								");
		

		
				if($query){
					$_SESSION['success'] = "Data Berhasil Diubah";
					echo "<meta http-equiv=\"refresh\" content=\"0; url=../../main.php?s=ubah_akun_zakat&id=$_SESSION[iduser]\">";
					echo mysql_error();
				}else{
					$_SESSION['error'] = "Proses Gagal, Terjadi Kesalahan : ".mysql_error();
					echo "<meta http-equiv=\"refresh\" content=\"0; url=../../main.php?s=ubah_akun_zakat&id=$_SESSION[iduser]]\">";
				}
		}else{
			$_SESSION['error'] = "Proses Dibatalkan";
				echo "<meta http-equiv=\"refresh\" content=\"0; url=../../main.php?s=ubah_akun_zakat\">";
		}
?>