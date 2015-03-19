<?php
	session_start();
	include "../config/koneksi.php";
	$tanggal = date('d-m-Y');
	$query = mysqli_query($mysqli, "INSERT INTO Informasi VALUES(
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
		$_SESSION['error'] = "Proses memasukkan informasi ketakmiran gagal, karena ".((is_object($mysqli)) ? mysqli_error($mysqli) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false));
		echo "<meta http-equiv=\"refresh\" content=\"0; url=../../main.php?s=form_info_baru\">";
	}
?>