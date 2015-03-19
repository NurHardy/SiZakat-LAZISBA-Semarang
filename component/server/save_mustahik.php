<?php
	/*save_mustahik.php*/
	session_start();
	include "../config/koneksi.php";
	include "../libraries/injection.php";
	if(ISSET($_POST['submit'])){
		$nama 			= clear_injection($_POST['nama']);
		$tempat 		= clear_injection($_POST['tempat']);
		$tanggal 		= clear_injection($_POST['tanggal']);
		$alamat 		= clear_injection($_POST['alamat']);
		$kota 			= clear_injection($_POST['kota']);
		$telepon 		= clear_injection($_POST['telepon']);
		$hp 			= clear_injection($_POST['hp']);
		$email 			= clear_injection($_POST['email']);
		$pekerjaan 		= clear_injection($_POST['pekerjaan']);
		$penghasilan 	= clear_injection($_POST['penghasilan']);
		/*$sekolah 		= clear_injection($_POST['sekolah']);
		$aSekolah 		= clear_injection($_POST['aSekolah']);*/
		
		
		$sql = mysqli_query($mysqli, "
			INSERT INTO mustahik (IdMustahik,Nama,Tmp_Lahir,Tgl_Lahir,Alamat,Kota,Telepon, Hp, Email, Pekerjaan,Penghasilan) VALUES 
			('','$nama','$tempat','$tanggal','$alamat','$kota','$telepon','$hp','$email','$pekerjaan','$penghasilan')
		");
		
		if($sql){
			$_SESSION['success'] = "Data Mustahik Berhasil Ditambah";
			echo "<meta http-equiv=\"refresh\" content=\"0; url=../../main.php?s=form_mustahik\">";
		}else{
			$_SESSION['error'] = "Proses Gagal, Terjadi Kesalahan : ".((is_object($mysqli)) ? mysqli_error($mysqli) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false));
			echo "<meta http-equiv=\"refresh\" content=\"0; url=../../main.php?s=form_mustahik\">";
		}
	}else{
		$_SESSION['error'] = "Proses Dibatalkan";
			echo "<meta http-equiv=\"refresh\" content=\"0; url=../../main.php?s=form_mustahik\">";
	}
	
?>