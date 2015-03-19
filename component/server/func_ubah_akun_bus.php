<?php
session_start();
	include"../config/koneksi.php";
	
	
	if($_POST['passwords'] == ""){
			$pas = "";
	}else{
			$pas = "password = sha1(sha1(md5('$_POST[passwords]'))),";
	}
	$nama = $_POST['nama'];
	$tmp_lahir = $_POST['tmp_lahir'];
	$tgl_lahir = $_POST['tgl_lahir'];
	$alamat = $_POST['alamat'];
	$kota = $_POST['kota'];
	$hp = $_POST['hp'];
	$email = $_POST['email'];
	$user = $_POST['user'];
		
	if(isset($_POST['save'])){
	
		
		$sql = mysqli_query($mysqli, "UPDATE user SET username = '$user', $pas nama = '$nama', tempat_lahir = '$tmp_lahir', tanggal_lahir = '$tgl_lahir' , alamat = '$alamat', kota = '$kota', hp = '$hp' , email = '$email' WHERE id_user = '$_SESSION[iduser]'");
		
		if($sql){
			$_SESSION['success'] = "Data Berhasil Diubah";
			echo "<meta http-equiv=\"refresh\" content=\"0; url=../../main.php?s=ubah_akun_bus&id=$_SESSION[iduser]\">";
		}else{
			$_SESSION['error'] = "Proses Gagal, Terjadi Kesalahan : ".((is_object($mysqli)) ? mysqli_error($mysqli) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false));
			//echo "<meta http-equiv=\"refresh\" content=\"0; url=../../main.php?s=ubah_akun_bus&id=$_SESSION[iduser]\">";
			echo ((is_object($mysqli)) ? mysqli_error($mysqli) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false));
		}
	}else{
		$_SESSION['error'] = "Proses Dibatalkan";
		echo "<meta http-equiv=\"refresh\" content=\"0; url=../../main.php?s=ubah_akun_bus\">";
	}


?>