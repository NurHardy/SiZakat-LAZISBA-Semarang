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
		
		$level = clear_injection($_POST['level']);
		$sql = mysqli_query($mysqli, "UPDATE user SET username = '$user', $pas nama = '$nama', tempat_lahir = '$tmp_lahir', tanggal_lahir = '$tgl_lahir' , alamat = '$alamat', kota = '$kota', hp = '$hp' , email = '$email' WHERE id_user = '$id'");
		
		if($sql){
			$_SESSION['success'] = "Data Amilin Berhasil Diubah";
			echo "<meta http-equiv=\"refresh\" content=\"0; url=../../main.php?s=edit_amilin&id=$id\">";
		}else{
			$_SESSION['error'] = "Proses Gagal, Terjadi Kesalahan : ".((is_object($mysqli)) ? mysqli_error($mysqli) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false));
			echo "<meta http-equiv=\"refresh\" content=\"0; url=../../main.php?s=edit_amilin&id=$id\">";
		}
	}else{
		$_SESSION['error'] = "Proses Dibatalkan";
		echo "<meta http-equiv=\"refresh\" content=\"0; url=../../main.php?s=edit_amilin\">";
	}

?>