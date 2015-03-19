<?php
	session_start();
	include "../config/koneksi.php";
	$tanggal = date('d-m-Y');
	$query = mysql_query("UPDATE informasi SET
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
		$_SESSION['gagal'] = "Proses mengubah informasi gagal, karena ".mysql_error();
		echo "<meta http-equiv=\"refresh\" content=\"0; url=../../main.php?s=daftar_info\">";
	}
?>