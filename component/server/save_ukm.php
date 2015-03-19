<?php
	/*save_mustahik.php*/
	session_start();
	include "../config/koneksi.php";
	include "../libraries/injection.php";
	if(ISSET($_POST['submit'])){
		$nama 		= clear_injection($_POST['nama']);
		$alamat		= clear_injection($_POST['alamat']);
		$telp		= clear_injection($_POST['telp']);
		$pj 		= clear_injection($_POST['pj']);
		$username 	= clear_injection($_POST['username']);
		$p1 	= clear_injection($_POST['p1']);
		
		$sql = mysql_query("INSERT INTO user (username,password,nama,alamat,hp, pj,level) VALUES ('$username',sha1(sha1(md5('$p1'))),'$nama','$alamat','$telp','$pj','2')");
		
		if($sql){
			$_SESSION['success'] = "Data UKM Berhasil Ditambah";
			echo "<meta http-equiv=\"refresh\" content=\"0; url=../../main.php?s=form_ukm\">";
			echo mysql_error();
		}else{
			$_SESSION['error'] = "Proses Gagal, Terjadi Kesalahan :".mysql_error();
			echo "<meta http-equiv=\"refresh\" content=\"0; url=../../main.php?s=form_ukm\">";
		}
		
		
	}else{
			$_SESSION['error'] = "Proses Dibatalkan";
			echo "<meta http-equiv=\"refresh\" content=\"0; url=../../main.php?s=form_ukm\">";
	}
	
?>