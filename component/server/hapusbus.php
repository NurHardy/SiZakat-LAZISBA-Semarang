<?php
	session_start();
	include "../config/koneksi.php";
	include "../libraries/injection.php";
	$id = clear_injection($_GET['id']);
	$tanggal = date('Y-m-d');
	if(ISSET($_GET['id'])){
		$sql = mysqli_query($mysqli, "UPDATE penerima_bus SET 
								is_off = '1',
								off_date = '$tanggal' WHERE id_penerima = '$id'");
		
		if($sql){
			$_SESSION['success'] = "Data Penerima Bus Berhasil Dihapus";
			echo "<meta http-equiv=\"refresh\" content=\"0; url=../../main.php?s=viewbus\">";
		}
		else {
			$_SESSION['error'] = "Proses Gagal, Terjadi Kesalahan : ".((is_object($mysqli)) ? mysqli_error($mysqli) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false));
			echo "<meta http-equiv=\"refresh\" content=\"0; url=../../main.php?s=viewbus\">";
		}
	
	}else{
		$_SESSION['error'] = "Proses Dibatalkan";
		echo "<meta http-equiv=\"refresh\" content=\"0; url=../../main.php?s=viewbus\">";
	}
?>