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
		
		$sql = mysql_query("INSERT INTO penerimaan VALUES ('','$tanggal','$donatur','$pegawai','$jumlah','$keterangan','1.9.','','')");
		if($sql){
			$_SESSION['success'] = "Penerimaan dana KUBAH Berhasil Ditambah";
			echo "<meta http-equiv=\"refresh\" content=\"0; url=../../main.php?s=form_terima_kubah\">";
		}else{
			$_SESSION['error'] = "Proses Gagal, Terjadi Kesalahan :".mysql_error();
			echo "<meta http-equiv=\"refresh\" content=\"0; url=../../main.php?s=form_terima_kubah\">";
		}
	
	}else{
		$_SESSION['error'] = "Proses Dibatalkan";
			echo "<meta http-equiv=\"refresh\" content=\"0; url=../../main.php?s=form_terima_kubah\">";
	}
	
?>