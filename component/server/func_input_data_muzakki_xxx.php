<?php
	session_start();
	include "../config/koneksi.php";
	if(ISSET($_POST)){
	//include "../libraries/injection.php";
	$query = mysql_query("INSERT INTO Muzakki VALUES(
							'',
							'$_POST[namalengkap]',
							'$_POST[tempatlahir]',
							'$_POST[tanggallahir]',
							'$_POST[alamat]',
							'$_POST[kota]',
							'$_POST[telepon]',
							'$_POST[hp]',
							'$_POST[email]',
							'$_POST[pekerjaan]',
							'$_POST[penghasilan]',
							'$_POST[perusahaan]',
							'$_POST[alamatperusahaan]')");
	if($query){
			$_SESSION['success'] = "Data Muzakki Berhasil Ditambah";
			echo "<meta http-equiv=\"refresh\" content=\"0; url=../../main.php?s=form_muzakki\">";
		}else{
			$_SESSION['error'] = "Proses Gagal, Terjadi Kesalahan : ".mysql_error();
			echo "<meta http-equiv=\"refresh\" content=\"0; url=../../main.php?s=form_muzakki\">";
		}
	}else{
		$_SESSION['error'] = "Proses Dibatalkan";
			echo "<meta http-equiv=\"refresh\" content=\"0; url=../../main.php?s=form_muzakki\">";
	}
?>