<?php
	session_start();
	/*save_mustahik.php*/
	include "../config/koneksi.php";
	include "../libraries/injection.php";
	if(ISSET($_POST['submit'])){
		$id 			= clear_injection($_GET['id']);
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
			UPDATE mustahik SET Nama='$nama',Tmp_Lahir='$tempat',Tgl_Lahir='$tanggal',Alamat='$alamat',Kota='$kota',Telepon='$telepon', Hp='$hp', Email='$email', Pekerjaan='$pekerjaan',Penghasilan='$penghasilan' WHERE IdMustahik='$id'");
		
		if($sql){
			$_SESSION['success'] = "Data Mustahik Berhasil Ditambah";
			echo "<meta http-equiv=\"refresh\" content=\"0; url=../../main.php?s=editmustahik&id=$id\">";
		}else{
			$_SESSION['error'] = "Proses Gagal, Terjadi Kesalahan : ".((is_object($mysqli)) ? mysqli_error($mysqli) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false));
			echo "<meta http-equiv=\"refresh\" content=\"0; url=../../main.php?s=editmustahik&id=$id\">";
		}
	}else{
		$_SESSION['error'] = "Proses Dibatalkan";
			echo "<meta http-equiv=\"refresh\" content=\"0; url=../../main.php?s=form_mustahik\">";
	}
	
?>