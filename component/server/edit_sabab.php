<?php
	session_start();
	include"../config/koneksi.php";
	include "../libraries/injection.php";
	
	if($_POST['passwords'] == ""){
			$pas = "";
	}else{
			$pas = "password = sha1(sha1(md5('$_POST[passwords]'))),";
	}
	
	if(isset($_POST['save'])){
		 $id = clear_injection($_GET['id']);
		$nama = clear_injection($_POST['nama']);
		$tmp_lahir = clear_injection($_POST['tmp_lahir']);
		$tgl_lahir = clear_injection($_POST['tgl_lahir']);
		$alamat = clear_injection($_POST['alamat']);
		$kota = clear_injection($_POST['kota']);
		$hp = clear_injection($_POST['hp']);
		$email = clear_injection($_POST['email']);
		$user = clear_injection($_POST['user']);
		
		$ket = clear_injection($_POST['keterangan']);
		$sql = mysql_query("UPDATE user SET username = '$user', $pas nama = '$nama', tempat_lahir = '$tmp_lahir', tanggal_lahir = '$tgl_lahir' , alamat = '$alamat', kota = '$kota', hp = '$hp' , email = '$email', keterangan = '$ket' WHERE id_user = '$id'");
		
		
		if($sql){
			if($_SESSION['level'] == 99){
				$_SESSION['success'] = "Data Sabab Berhasil Diubah";
				echo "<meta http-equiv=\"refresh\" content=\"0; url=../../main.php?s=form_sabab&id=$id\">";
				
			}else{
				$_SESSION['success'] = "Data Pribadi Berhasil Diubah";
				echo "<meta http-equiv=\"refresh\" content=\"0; url=../../main.php?s=ubah_akun_sabab&id=$id\">";
			
			}
		}else{
			$_SESSION['error'] = "Proses Gagal, Terjadi Kesalahan : ".mysql_error();
			echo "<meta http-equiv=\"refresh\" content=\"0; url=../../main.php?s=form_sabab&id=$id\">";
		}
	}else{
		$_SESSION['error'] = "Proses Dibatalkan";
		echo "<meta http-equiv=\"refresh\" content=\"0; url=../../main.php?s=form_sabab\">";
	}

?>