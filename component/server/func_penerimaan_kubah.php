<?php
	/*save_mustahik.php*/
	session_start();
	include "../config/koneksi.php";
	include "../libraries/injection.php";
	if(ISSET($_POST['submit'])){
		$tanggal	= clear_injection($_POST['tanggal']);
		$notrans	= clear_injection($_POST['notrans']);
		$donatur	= clear_injection($_POST['donatur']);
		$pegawai	= clear_injection($_POST['pegawai']);
		$jumlah 	= clear_injection($_POST['jumlah']);
		$keterangan	= clear_injection($_POST['keterangan']);
		
		$sql = mysqli_query($mysqli, "INSERT INTO penerimaan VALUES ('','$tanggal','$donatur','$pegawai','$jumlah','$keterangan','1.9.','','')");
		if($sql){
			$_SESSION['success'] = "Penerimaan dana KUBAH Berhasil Ditambah";
			echo "<meta http-equiv=\"refresh\" content=\"0; url=../../main.php?s=form_terima_kubah\">";
		}else{
			$_SESSION['error'] = "Proses Gagal, Terjadi Kesalahan :".((is_object($mysqli)) ? mysqli_error($mysqli) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false));
			echo "<meta http-equiv=\"refresh\" content=\"0; url=../../main.php?s=form_terima_kubah\">";
		}
	
	}else{
		$_SESSION['error'] = "Proses Dibatalkan";
			echo "<meta http-equiv=\"refresh\" content=\"0; url=../../main.php?s=form_terima_kubah\">";
	}
	
?>