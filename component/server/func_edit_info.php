<?php
	session_start();
	include "../config/koneksi.php";
	$tanggal = date('d-m-Y');
	$query = mysqli_query($mysqli, "UPDATE informasi SET
							judul = '$_POST[judul]',
							isi = '$_POST[informasi]',
							tanggal = '$tanggal',
							status = '$_POST[tipe]'
							WHERE id_informasi = '$_GET[id]'");
	if($query){
		$_SESSION['sukses'] = "Proses mengubah informasi berhasil";
		echo "<meta http-equiv=\"refresh\" content=\"0; url=../../main.php?s=daftar_info\">";
	}
	else{
		$_SESSION['gagal'] = "Proses mengubah informasi gagal, karena ".((is_object($mysqli)) ? mysqli_error($mysqli) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false));
		echo "<meta http-equiv=\"refresh\" content=\"0; url=../../main.php?s=daftar_info\">";
	}
?>