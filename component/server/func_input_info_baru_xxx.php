<?php
	session_start();
	include "../config/koneksi.php";
	$tanggal = date('d-m-Y');
	$query = mysql_query("INSERT INTO Informasi VALUES(
							'',
							'IdAdmin',
							'$_POST[judul]',
							'$tanggal',
							'$_POST[informasi]',
							'Status')");
	if($query){
		//echo"sukses";
		$_SESSION['success'] = "Proses memasukkan informasi ketakmiran berhasil";
		echo "<meta http-equiv=\"refresh\" content=\"0; url=../../main.php?s=form_info_baru\">";
	}
	else{
		$_SESSION['error'] = "Proses memasukkan informasi ketakmiran gagal, karena ".mysql_error();
		echo "<meta http-equiv=\"refresh\" content=\"0; url=../../main.php?s=form_info_baru\">";
	}
?>