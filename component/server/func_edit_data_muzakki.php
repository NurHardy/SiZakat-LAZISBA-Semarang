<?php
	session_start();
	
	if(ISSET($_POST)){
	include "../config/koneksi.php";
	//include "../libraries/injection.php";
	
	if($_POST['passwords'] == ""){
			$pas = "";
	}else{
			$pas = "password = sha1(sha1(md5('$_POST[passwords]'))),";
	}

	$query = mysqli_query($mysqli, "UPDATE user SET
							username = '$_POST[user]',
							$pas
							nama = '$_POST[namalengkap]',
							tempat_lahir = '$_POST[tempatlahir]',
							tanggal_lahir = '$_POST[tanggallahir]',
							alamat = '$_POST[alamat]',
							kota = '$_POST[kota]',
							hp = '$_POST[hp]',
							email = '$_POST[email]',
							pekerjaan = '$_POST[pekerjaan]',
							penghasilan = '$_POST[penghasilan]',
							perusahaan = '$_POST[perusahaan]',
							alamat_perusahaan = '$_POST[alamatperusahaan]',
							jns_donatur = '$_POST[jenis]'
							WHERE id_user  = '$_GET[id]'
							");
	
	/*if($_POST['passwords'] == ""){
		$pas = "";
	}else{
		$pas = ", password='".sha1(sha1(md5($_POST['passwords'])))."' ";
	}*/
	

	
	if($query){
			$_SESSION['success'] = "Data Mustahik Berhasil Diubah";
			echo "<meta http-equiv=\"refresh\" content=\"0; url=../../main.php?s=editmuzakki&id=$_GET[id]\">";
			echo ((is_object($mysqli)) ? mysqli_error($mysqli) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false));
		}else{
			$_SESSION['error'] = "Proses Gagal, Terjadi Kesalahan : ".((is_object($mysqli)) ? mysqli_error($mysqli) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false));
			echo "<meta http-equiv=\"refresh\" content=\"0; url=../../main.php?s=editmuzakki&id=$_GET[id]\">";
		}
	}else{
		$_SESSION['error'] = "Proses Dibatalkan";
			echo "<meta http-equiv=\"refresh\" content=\"0; url=../../main.php?s=form_muzakki\">";
	}
?>