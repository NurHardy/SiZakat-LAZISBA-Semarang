<?php
	session_start();
	include "../config/koneksi.php";
	$tanggal = date('d-m-Y');
	$query = mysql_query("INSERT INTO Informasi VALUES(
							'',
							'$_SESSION[iduser]',
							'$_POST[judul]',
							'$tanggal',
							'$_POST[informasi]',
							'$_POST[tipe]')");
	if($query){
		//echo"sukses";
		//$_SESSION['sukses'] = "Proses memasukkan informasi ketakmiran berhasil";
		echo "<meta http-equiv=\"refresh\" content=\"0; url=../../main.php?s=daftar_info\">";
	}
	else{
		$_SESSION['gagal'] = "Proses memasukkan informasi ketakmiran gagal, karena ".mysql_error();
		echo "<meta http-equiv=\"refresh\" content=\"0; url=../../main.php?s=daftar_info\">";
	}
?>