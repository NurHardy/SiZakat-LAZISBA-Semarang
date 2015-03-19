<?php
	session_start();
	include "../config/koneksi.php";
	include "../libraries/injection.php";
	if(ISSET($_POST)){
	$tanggal = date('d-m-Y');
	$id = clear_injection($_GET['id']);
	$query = mysqli_query($mysqli, "UPDATE informasi SET
							Judul = '$_POST[judul]',
							Isi = '$_POST[informasi]',
							Tanggal = '$tanggal'
							WHERE IdInformasi = '$id'");
		if($query){
			$_SESSION['success'] = "Proses mengubah informasi berhasil";
			echo "<meta http-equiv=\"refresh\" content=\"0; url=../../main.php?s=edit_info&id=$id\">";
		}
		else{
			$_SESSION['error'] = "Proses mengubah informasi gagal, karena ".((is_object($mysqli)) ? mysqli_error($mysqli) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false));
			echo "<meta http-equiv=\"refresh\" content=\"0; url=../../main.php?s=edit_info&id=$id\">";
		}
	}else{
			$_SESSION['error'] = "Proses Dibatalkan";
			echo "<meta http-equiv=\"refresh\" content=\"0; url=../../main.php?s=form_info_baru\">";
	}
?>