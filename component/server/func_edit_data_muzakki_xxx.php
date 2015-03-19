<?php
	session_start();
	include "../config/koneksi.php";
	if($_POST){
	//include "../libraries/injection.php";
	$id = $_GET['id'];
	$query = mysqli_query($mysqli, "UPDATE Muzakki SET
							Nama = '$_POST[namalengkap]',
							Tmp_Lahir = '$_POST[tempatlahir]',
							Tgl_Lahir = '$_POST[tanggallahir]',
							Alamat = '$_POST[alamat]',
							Kota = '$_POST[kota]',
							Telepon = '$_POST[telepon]',
							Hp = '$_POST[hp]',
							Email = '$_POST[email]',
							Pekerjaan = '$_POST[pekerjaan]',
							Penghasilan = '$_POST[penghasilan]',
							Perusahaan = '$_POST[perusahaan]',
							Alamat_Perusahaan = '$_POST[alamatperusahaan]'
							WHERE IdMuzakki = '$_GET[id]'
							");
	if($query){
			$_SESSION['success'] = "Data Muzakki Berhasil Diubah";
			echo "<meta http-equiv=\"refresh\" content=\"0; url=../../main.php?s=editmuzakki&id=$id\">";
		}else{
			$_SESSION['error'] = "Proses Gagal, Terjadi Kesalahan : ".((is_object($mysqli)) ? mysqli_error($mysqli) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false));
			echo "<meta http-equiv=\"refresh\" content=\"0; url=../../main.php?s=editmuzakki&id=$id\">";
		}
	}else{
		$_SESSION['error'] = "Proses Dibatalkan";
			echo "<meta http-equiv=\"refresh\" content=\"0; url=../../main.php?s=form_muzakki\">";
	}
?>